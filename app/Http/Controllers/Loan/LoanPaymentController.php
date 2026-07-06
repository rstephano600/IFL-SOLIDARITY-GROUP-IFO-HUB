<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use App\Models\LoanPayment;
use App\Models\Loan;
use App\Models\Client;
use App\Models\GroupCenter;
use App\Models\RepaymentSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LogActivity;

class LoanPaymentController extends Controller
{
    /**
     * Display all loan payments
     */
    public function index()
    {
        $payments = LoanPayment::with(['loan', 'client', 'groupCentre'])
            ->latest()
            ->paginate(15);

        return view('in.loans.loan_payments.index', compact('payments'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $loans = Loan::whereIn('status', ['approved', 'active'])->get();
        $clients = Client::all();
        $centres = GroupCenter::where('is_active', true)->get();
        $schedules = RepaymentSchedule::all();

        return view('in.loans.loan_payments.create', compact('loans', 'clients', 'centres', 'schedules'));
    }

    /**
     * Store new payment
     */
     public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'client_id' => 'required|exists:clients,id',
            'group_centre_id' => 'nullable|exists:group_centres,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'principal_component' => 'nullable|numeric|min:0',
            'interest_component' => 'nullable|numeric|min:0',
            'fees_component' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:100',
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        // ✅ Validate loan approval or active
        if (!in_array($loan->status, ['approved', 'active'])) {
            return back()->withErrors(['loan_id' => 'Payments can only be made on approved or active loans.'])->withInput();
        }

        // Generate receipt number
        $receipt = 'RCPT-' . strtoupper(uniqid());

        // Record the payment
        $payment = LoanPayment::create([
            'loan_id' => $loan->id,
            'client_id' => $request->client_id,
            'repayment_schedule_id' => $request->repayment_schedule_id,
            'group_centre_id' => $request->group_centre_id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'principal_component' => $request->principal_component ?? 0,
            'interest_component' => $request->interest_component ?? 0,
            'fees_component' => $request->fees_component ?? 0,
            'payment_method' => $request->payment_method,
            'reference_number' => $request->reference_number,
            'receipt_number' => $receipt,
            'remarks' => $request->remarks,
            'status' => 'confirmed',
            'created_by' => Auth::id(),
        ]);

        // ✅ Update loan state after payment
        $this->updateLoanState($loan);

        LogActivity::add(
        'loan payment',
        'create payment',
        $payment->id,
        'Create a new payment: ' . $payment->receipt_number
         );

        return redirect()->route('loan_payments.index')
            ->with('success', 'Payment recorded and loan updated successfully.');
    }

    /**
     * Show a specific payment
     */
    public function show(LoanPayment $loanPayment)
    {
        $loanPayment->load(['loan', 'client', 'groupCentre']);
        return view('in.loans.loan_payments.show', compact('loanPayment'));
    }

    /**
     * Edit form
     */
    public function edit(LoanPayment $loanPayment)
    {
        $loans = Loan::whereIn('status', ['approved', 'active'])->get();
        $clients = Client::all();
        $centres = GroupCenter::where('is_active', true)->get();

        return view('in.loans.loan_payments.edit', compact('loanPayment', 'loans', 'clients', 'centres'));
    }

    /**
     * Update record
     */
    public function update(Request $request, LoanPayment $loanPayment)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:100',
        ]);

        $loanPayment->update($request->all() + ['updated_by' => Auth::id()]);

        // ✅ Refresh loan state after update
        $this->updateLoanState($loanPayment->loan);

        LogActivity::add(
        'loan payment',
        'Updated payment',
        $loanPayment->id,
        'updated payment: ' . $loanPayment->receipt_number
         );
        return redirect()->route('loan_payments.index')
            ->with('success', 'Payment updated and loan recalculated successfully.');

    }

    private function updateLoanState(Loan $loan)
    {
        $totalPaid = LoanPayment::where('loan_id', $loan->id)->sum('amount');
        $interestPaid = LoanPayment::where('loan_id', $loan->id)->sum('interest_component');
        $feesPaid = LoanPayment::where('loan_id', $loan->id)->sum('fees_component');

        $loan->total_paid = $totalPaid;
        $loan->interest_paid = $interestPaid;
        $loan->fees_paid = $feesPaid;

        // Recalculate outstanding balances
        $loan->total_outstanding = max(0, $loan->total_payable - $totalPaid);

        // If fully paid, mark loan as completed
        if ($loan->total_outstanding <= 0) {
            $loan->status = 'completed';
            $loan->closed_at = now();
            $loan->closure_reason = 'Fully paid';
        } elseif ($loan->status === 'approved') {
            // Move to active once first payment made
            $loan->status = 'active';
        }

        $loan->save();
    }

    /**
     * Delete a payment
     */
    public function destroy(LoanPayment $loanPayment)
    {
        $loanPayment->delete();
        return back()->with('success', 'Payment deleted successfully.');
    }
}
