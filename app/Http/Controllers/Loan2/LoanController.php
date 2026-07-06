<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use App\Models\Loan;
use App\Models\GroupCenter;
use App\Models\RepaymentSchedule;
use App\Models\LoanCategory; // Needed to pre-fill loan details
use App\Models\Client; // Needed to select the borrower
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class LoanController extends Controller
{
    // ... index, create methods remain here ...
public function index(Request $request)
    {
        $query = Loan::with('client', 'category');

        // Search by loan number or client name (assuming 'client' relationship is set)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('loan_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%"); // Assuming 'name' field on Client model
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by loan category
        if ($request->filled('category')) {
            $query->where('loan_category_id', $request->input('category'));
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = LoanCategory::where('is_active', true)->get(['id', 'name']);
        
        return view('in.loans.loans.index', compact('loans', 'categories'));
    }

    /**
     * Show the form for creating a new loan.
     */
    public function create()
    {
        $categories = LoanCategory::where('is_active', true)->get();
        $clients = Client::all(); 
        $centers = GroupCenter::where('is_active', true)->get();
        return view('in.loans.loans.create', compact('categories', 'clients', 'centers'));
    }
    /**
     * Store a newly created loan in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'loan_category_id' => 'required|exists:loan_categories,id',
            'group_center_id' => 'nullable|exists:group_centers,id',
            
            'principal_amount' => 'required|numeric|min:1',
            // REMOVED: 'interest_rate' validation - now derived from category
            'term_months' => 'required|integer|min:1',
            // REMOVED: 'repayment_frequency' validation - now derived from category
            
            'disbursement_date' => 'required|date',
        ]);
        
        // 1. Fetch Loan Category to get standard rates and frequency
        $category = LoanCategory::findOrFail($validated['loan_category_id']);

        // 2. Define calculation variables using category data
        $principal = (float) $validated['principal_amount'];
        // **USE CATEGORY RATE**
        $annualRate = (float) $category->interest_rate / 100;
        $termMonths = (int) $validated['term_months'];
        // **USE CATEGORY FREQUENCY**
        $frequency = $category->repayment_frequency;
        $disbursementDate = new \DateTime($validated['disbursement_date']);

        // 3. Calculate schedule totals (using helper methods)
        list($i, $n) = $this->getPeriodRateAndCount($annualRate, $termMonths, $frequency);

        if ($n <= 0 || $i < 0) {
            return back()->withInput()->withErrors(['calculation' => 'Invalid loan term or frequency for calculation.']);
        }

        // Calculate the installment amount (PMT)
        $installment = ($i > 0) 
            ? $principal * ($i * pow((1 + $i), $n)) / (pow((1 + $i), $n) - 1)
            : $principal / $n;

        $totalPayable = $installment * $n;
        $totalInterest = $totalPayable - $principal;
        
        // 4. Create Loan Record
        $loanData = array_merge($validated, [
            'loan_number' => 'L-' . strtoupper(Str::random(8)) . '-' . date('Y'),
            'status' => 'approved',
            'interest_rate' => (float) $category->interest_rate, // Store the definitive rate
            'repayment_frequency' => $category->repayment_frequency, // Store the definitive frequency
            'total_interest' => round($totalInterest, 2),
            'total_payable' => round($totalPayable, 2),
            'outstanding_principal' => round($principal, 2),
            'total_outstanding' => round($totalPayable, 2),
            'created_by' => Auth::id(),
        ]);

        $loan = Loan::create($loanData);

        // 5. Generate and Store Repayment Schedule (CRITICAL NEW STEP)
        try {
            $firstDueDate = $this->generateRepaymentSchedule($loan, $principal, round($installment, 2), $i, $n, $disbursementDate, $frequency);

            // 6. Update Loan with First Payment Date
            $loan->update([
                'first_payment_date' => $firstDueDate,
                'next_payment_date' => $firstDueDate,
            ]);

        } catch (\Exception $e) {
            // Log the error and possibly delete the loan if schedule generation fails
            $loan->delete();
            // Log::error("Schedule generation failed for loan: " . $loan->loan_number . " Error: " . $e->getMessage());
            return back()->withInput()->withErrors(['schedule_error' => 'Failed to generate repayment schedule. Please check loan category settings.']);
        }

        return redirect()->route('loans.show', $loan)->with('success', 'Loan created and schedule generated successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan)
    {
        // Load categories if needed for selection dropdown
        $categories = LoanCategory::all(); 
        
        // Ensure only pending loans can be edited (optional guardrail)
        if ($loan->status !== 'pending') {
             return redirect()->route('loans.show', $loan)->with('error', 'Only loans with pending status can be edited.');
        }        
        $clients = Client::all();
        $centers = GroupCenter::where('is_active', true)->get(); // <-- Get active centers
        return view('in.loans.loans.edit', compact('loan', 'categories', 'clients', 'centers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        // Add a check to prevent updating active/completed loans
        if (!in_array($loan->status, ['pending', 'approved'])) {
            return back()->with('error', 'Loan is already active and cannot be updated.');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'loan_category_id' => 'required|exists:loan_categories,id',
            'group_center_id' => 'nullable|exists:group_centers,id',
            
            'principal_amount' => 'required|numeric|min:1',
            // REMOVED: 'interest_rate' validation
            'term_months' => 'required|integer|min:1',
            // REMOVED: 'repayment_frequency' validation
            
            'disbursement_date' => 'required|date',
        ]);
        
        // 1. Fetch Loan Category to get standard rates and frequency
        $category = LoanCategory::findOrFail($validated['loan_category_id']);

        // 2. Define calculation variables using category data
        $principal = (float) $validated['principal_amount'];
        $annualRate = (float) $category->interest_rate / 100;
        $termMonths = (int) $validated['term_months'];
        $frequency = $category->repayment_frequency;
        $disbursementDate = new \DateTime($validated['disbursement_date']);

        // 3. Recalculate financial totals
        list($i, $n) = $this->getPeriodRateAndCount($annualRate, $termMonths, $frequency);

        if ($n <= 0 || $i < 0) {
            return back()->withInput()->withErrors(['calculation' => 'Invalid loan term or frequency for recalculation.']);
        }

        $installment = ($i > 0) 
            ? $principal * ($i * pow((1 + $i), $n)) / (pow((1 + $i), $n) - 1)
            : $principal / $n;
        
        $totalPayable = $installment * $n;
        $totalInterest = $totalPayable - $principal;

        // 4. Update Loan Data
        $loanData = array_merge($validated, [
            'interest_rate' => (float) $category->interest_rate, // Store the definitive rate
            'repayment_frequency' => $category->repayment_frequency, // Store the definitive frequency
            'total_interest' => round($totalInterest, 2),
            'total_payable' => round($totalPayable, 2),
            'outstanding_principal' => round($principal, 2),
            'total_outstanding' => round($totalPayable, 2), // Reset outstanding totals before disbursement
        ]);

        $loan->update($loanData);

        // 5. If successful, delete existing schedule and regenerate (only for pending/approved loans)
        if ($loan->repaymentSchedules()->count() > 0) {
            $loan->repaymentSchedules()->delete();
        }

        try {
            $firstDueDate = $this->generateRepaymentSchedule($loan, $principal, round($installment, 2), $i, $n, $disbursementDate, $frequency);

            // 6. Update Loan with First Payment Date
            $loan->update([
                'first_payment_date' => $firstDueDate,
                'next_payment_date' => $firstDueDate,
            ]);

        } catch (\Exception $e) {
            // Log the error
            // Log::error("Schedule regeneration failed for loan: " . $loan->loan_number . " Error: " . $e->getMessage());
            return back()->withInput()->withErrors(['schedule_error' => 'Failed to regenerate repayment schedule after update.']);
        }


        return redirect()->route('loans.show', $loan)->with('success', 'Loan updated and schedule successfully recalculated.');
    }

    public function show(Loan $loan)
    {
        $loan->load('client', 'category');
        return view('in.loans.loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified loan.
     */




    // ----------------------------------------------------------------------
    // PRIVATE AMORTIZATION HELPER METHODS (These methods remain unchanged)
    // ----------------------------------------------------------------------

    /**
     * Helper to get the period rate (i) and total number of periods (n).
     */
    private function getPeriodRateAndCount(float $annualRate, int $termMonths, string $frequency): array
    {
        $periodsPerYear = 12; // Default to monthly

        switch ($frequency) {
            case 'daily':
                $periodsPerYear = 360;
                break;
            case 'weekly':
                $periodsPerYear = 52;
                break;
            case 'bi_weekly':
                $periodsPerYear = 26;
                break;
            case 'quarterly':
                $periodsPerYear = 4;
                break;
            case 'monthly':
            default:
                $periodsPerYear = 12;
                break;
        }

        $totalPeriods = ($termMonths / 12) * $periodsPerYear;

        $ratePerPeriod = $annualRate / $periodsPerYear;
        return [$ratePerPeriod, (int) ceil($totalPeriods)];
    }

    /**
     * Generates and saves the full repayment schedule.
     */
    private function generateRepaymentSchedule(Loan $loan, float $principal, float $installment, float $ratePerPeriod, int $totalPeriods, \DateTime $disbursementDate, string $frequency): string
    {
        $schedule = [];
        $remainingPrincipal = $principal;
        $totalInterestPaid = 0;
        $totalPrincipalPaid = 0;
        $currentDate = clone $disbursementDate;

        for ($k = 1; $k <= $totalPeriods; $k++) {
            
            // 1. Determine Due Date
            $currentDate = $this->calculateNextDueDate($currentDate, $frequency, $k == 1);
            $dueDate = $currentDate->format('Y-m-d');
            
            // 2. Calculate Components
            $interestPayment = $remainingPrincipal * $ratePerPeriod;
            $principalPayment = $installment - $interestPayment;

            // Handle the last payment (to clear any rounding errors)
            if ($k === $totalPeriods) {
                $principalPayment = $remainingPrincipal;
                $installment = $principalPayment + $interestPayment;
            }

            $remainingPrincipal -= $principalPayment;
            $remainingPrincipal = max(0, $remainingPrincipal); // Ensure it doesn't go negative

            $totalInterestPaid += $interestPayment;
            $totalPrincipalPaid += $principalPayment;

            $schedule[] = [
                'loan_id' => $loan->id,
                'group_center_id' => $loan->group_center_id,
                'installment_number' => $k,
                'due_date' => $dueDate,
                'principal_due' => round($principalPayment, 2),
                'interest_due' => round($interestPayment, 2),
                'fees_due' => 0.00, // Assuming no fees for simple amortization
                'total_due' => round($installment, 2),
                'principal_outstanding' => round($remainingPrincipal, 2),
                'status' => 'pending',
                'created_by' => Auth::id(),
                'created_at' => now(),
            ];
        }

        // Batch insert the schedule
        RepaymentSchedule::insert($schedule);

        return $schedule[0]['due_date']; // Return the first due date
    }

    /**
     * Calculates the next due date based on frequency.
     * Note: This is a simplified function and may need refinement for weekends/holidays in a production environment.
     */
    private function calculateNextDueDate(\DateTime $currentDate, string $frequency, bool $isFirstInstallment): \DateTime
    {
        $interval = match ($frequency) {
            'daily' => '1 day',
            'weekly' => '1 week',
            'bi_weekly' => '2 weeks',
            'monthly' => '1 month',
            'quarterly' => '3 months',
            default => '1 month',
        };

        // For the first installment, advance the date by the interval.
        // For subsequent installments, continue from the last calculated date.
        // We use $currentDate as the starting point (disbursement date) for the first iteration.
        $nextDate = clone $currentDate;
        
        if ($isFirstInstallment) {
            $nextDate->modify('+' . $interval);
        } else {
            // For subsequent installments, $currentDate already holds the previous due date
            $nextDate->modify('+' . $interval);
        }

        return $nextDate;
    }
}
