<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use App\Models\LoanCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanCategoryController extends Controller
{
    /**
     * Display a listing of the loan categories with search & filters.
     */
    public function index(Request $request)
    {
        $query = LoanCategory::query();

        // 🔍 Search by name or conditions
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('conditions', 'like', "%{$search}%");
            });
        }

        // 🎯 Filter by status (active/inactive)
        if ($request->filled('status')) {
            $status = $request->input('status') === 'active' ? 1 : 0;
            $query->where('is_active', $status);
        }

        // 💰 Filter by currency
        if ($request->filled('currency')) {
            $query->where('currency', $request->input('currency'));
        }

        // 📅 Filter by repayment frequency
        if ($request->filled('frequency')) {
            $query->where('repayment_frequency', $request->input('frequency'));
        }

        $loanCategories = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('in.loans.loan_categories.index', compact('loanCategories'));
    }

    /**
     * Show the form for creating a new loan category.
     */
    public function create()
    {
        return view('in.loans.loan_categories.create');
    }

    /**
     * Store a newly created loan category in storage.
     */
    public function store(Request $request)
    {
        // 1. Define base validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'interest_rate' => 'required|numeric|min:0',
            'max_term_months' => 'required|integer|min:1',
            'max_term_days' => 'nullable|integer|min:1',
            'principal_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gte:min_amount',
            'repayment_frequency' => 'required|in:daily,weekly,bi_weekly,monthly,quarterly',
            'conditions' => 'required|string',
            'is_active' => 'boolean',
            
            // Add a flag to indicate the user wants calculation
            'calculate_amounts' => 'nullable|boolean',
        ];

        // 2. Adjust validation for installment and total amounts
        // They are required UNLESS the user chooses to calculate them.
        if ($request->input('calculate_amounts')) {
            $rules['installment_amount'] = 'nullable|numeric'; // We'll calculate it
            $rules['total_repayable_amount'] = 'nullable|numeric'; // We'll calculate it
        } else {
            // If not calculating, the user must provide the final values
            $rules['installment_amount'] = 'required|numeric|min:0';
            $rules['total_repayable_amount'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

        // 3. Calculation Logic
        if ($request->input('calculate_amounts')) {
            $principal = (float) $validated['principal_amount'];
            $annualRate = (float) $validated['interest_rate'] / 100;
            $termMonths = (int) $validated['max_term_months'];
            $frequency = $validated['repayment_frequency'];

            // Determine the number of payment periods (n) and the rate per period (i)
            list($i, $n) = $this->getPeriodRateAndCount($annualRate, $termMonths, $frequency);

            if ($n > 0 && $i >= 0) {
                // Amortization Formula for Installment Amount (PMT)
                if ($i > 0) {
                    $installment = $principal * ($i * pow((1 + $i), $n)) / (pow((1 + $i), $n) - 1);
                } else {
                    // Simple case: 0% interest
                    $installment = $principal / $n;
                }
                
                $totalRepayable = $installment * $n;

                // Update validated data with calculated values (rounded to 2 decimal places)
                $validated['installment_amount'] = round($installment, 2);
                $validated['total_repayable_amount'] = round($totalRepayable, 2);
            } else {
                // Fallback for invalid calculation scenarios (e.g., termMonths is 0)
                return back()->withInput()->withErrors(['calculation' => 'Cannot calculate amounts with the given term/frequency.']);
            }
        }
        
        // Ensure the boolean field is correctly handled for creation
        unset($validated['calculate_amounts']); 

        $validated['created_by'] = Auth::id();

        LoanCategory::create($validated);

        return redirect()->route('loan_categories.index')->with('success', 'Loan category created successfully.');
    }

    /**
     * Helper function to determine the rate and number of periods for amortization.
     * Note: This is a simplified model assuming a fixed relationship between months and periods.
     */
    private function getPeriodRateAndCount(float $annualRate, int $termMonths, string $frequency): array
    {
        $periodsPerYear = 12; // Base periods (months)

        switch ($frequency) {
            case 'daily':
                // Daily payments (360 days in a loan year for simplification)
                $periodsPerYear = 360;
                $totalPeriods = ($termMonths / 12) * 360;
                break;
            case 'weekly':
                // 52 weeks per year
                $periodsPerYear = 52;
                $totalPeriods = ($termMonths / 12) * 52;
                break;
            case 'bi_weekly':
                // 26 bi-weeks per year
                $periodsPerYear = 26;
                $totalPeriods = ($termMonths / 12) * 26;
                break;
            case 'monthly':
                $periodsPerYear = 12;
                $totalPeriods = $termMonths; // Total periods is simply the months
                break;
            case 'quarterly':
                // 4 quarters per year
                $periodsPerYear = 4;
                $totalPeriods = $termMonths / 3;
                break;
            default:
                $totalPeriods = 0; // Invalid frequency
                break;
        }

        $ratePerPeriod = $annualRate / $periodsPerYear;
        
        // Ensure total periods is an integer (rounded up for safety/simplicity)
        return [$ratePerPeriod, (int) ceil($totalPeriods)];
    }

    /**
     * Display the specified loan category.
     */
    public function show(LoanCategory $loanCategory)
    {
        return view('in.loans.loan_categories.show', compact('loanCategory'));
    }

    /**
     * Show the form for editing the specified loan category.
     */
    public function edit(LoanCategory $loanCategory)
    {
        return view('in.loans.loan_categories.edit', compact('loanCategory'));
    }

    /**
     * Update the specified loan category in storage.
     */
    public function update(Request $request, LoanCategory $loanCategory)
    {
        // 1. Define base validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'interest_rate' => 'required|numeric|min:0',
            'max_term_months' => 'required|integer|min:1',
            'max_term_days' => 'nullable|integer|min:1',
            'principal_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'required|numeric|gte:min_amount',
            'repayment_frequency' => 'required|in:daily,weekly,bi_weekly,monthly,quarterly',
            'conditions' => 'required|string',
            'is_active' => 'boolean',
            
            // Flag to indicate the user wants calculation
            'calculate_amounts' => 'nullable|boolean',
        ];

        // 2. Adjust validation for installment and total amounts based on the user's choice
        if ($request->input('calculate_amounts')) {
            $rules['installment_amount'] = 'nullable|numeric'; // We'll calculate it
            $rules['total_repayable_amount'] = 'nullable|numeric'; // We'll calculate it
        } else {
            // If not calculating, the user must provide the final values
            $rules['installment_amount'] = 'required|numeric|min:0';
            $rules['total_repayable_amount'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

        // 3. Calculation Logic
        if ($request->input('calculate_amounts')) {
            $principal = (float) $validated['principal_amount'];
            $annualRate = (float) $validated['interest_rate'] / 100;
            $termMonths = (int) $validated['max_term_months'];
            $frequency = $validated['repayment_frequency'];

            // Determine the number of payment periods (n) and the rate per period (i)
            list($i, $n) = $this->getPeriodRateAndCount($annualRate, $termMonths, $frequency);

            if ($n > 0 && $i >= 0) {
                // Amortization Formula for Installment Amount (PMT)
                if ($i > 0) {
                    $installment = $principal * ($i * pow((1 + $i), $n)) / (pow((1 + $i), $n) - 1);
                } else {
                    // Simple case: 0% interest
                    $installment = $principal / $n;
                }
                
                $totalRepayable = $installment * $n;

                // Update validated data with calculated values (rounded to 2 decimal places)
                $validated['installment_amount'] = round($installment, 2);
                $validated['total_repayable_amount'] = round($totalRepayable, 2);
            } else {
                // Fallback for invalid calculation scenarios
                return back()->withInput()->withErrors(['calculation' => 'Cannot calculate amounts with the given term/frequency.']);
            }
        }
        
        // Remove the temporary flag before updating the model
        unset($validated['calculate_amounts']); 

        $validated['updated_by'] = Auth::id();

        $loanCategory->update($validated);

        return redirect()->route('loan_categories.index')->with('success', 'Loan category updated successfully.');
    }
    
    

    /**
     * Remove the specified loan category from storage.
     */
    public function destroy(LoanCategory $loanCategory)
    {
        $loanCategory->delete();

        return redirect()->route('loan_categories.index')->with('success', 'Loan category deleted successfully.');
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(LoanCategory $loanCategory)
    {
        $loanCategory->is_active = !$loanCategory->is_active;
        $loanCategory->save();

        return redirect()->route('loan_categories.index')->with('success', 'Loan category status updated.');
    }
}
