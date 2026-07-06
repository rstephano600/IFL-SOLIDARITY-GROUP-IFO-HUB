<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\RepaymentSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Show the form for recording a new payment for a specific loan.
     * Accessible via a route like: /loans/{loan}/payments/create
     */
    public function create(Loan $loan)
    {
        // 1. Load data for the form
        // Get the overdue/pending schedule items to help the user know how much is due.
        $pendingSchedules = $loan->repaymentSchedules()
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->orderBy('due_date')
            ->get();
        
        $totalDue = $pendingSchedules->sum('total_due') - $pendingSchedules->sum('amount_paid');
        
        return view('in.loans.payments.create', compact('loan', 'totalDue'));
    }

    /**
     * Store a newly recorded payment and allocate it to the schedule.
     */
    public function store(Request $request, Loan $loan)
    {
        // 1. Validation
        $validated = $request->validate([
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_method' => ['required', Rule::in(['bank_transfer', 'cash', 'cheque', 'mobile_money', 'direct_debit', 'card', 'other'])],
            'transaction_reference' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            // Note: group_center_id is assumed to be derived from the loan or the user's current center
        ]);

        // 2. Begin Transaction (Crucial for financial operations)
        return DB::transaction(function () use ($request, $loan, $validated) {

            $amountReceived = (float) $validated['payment_amount'];
            $remainingAmount = $amountReceived;
            $paymentAllocations = [];
            $paymentBreakdown = [
                'principal_total' => 0,
                'interest_total' => 0,
                'fees_total' => 0,
                'penalty_total' => 0,
            ];

            // 3. Find Overdue/Pending Schedule Items
            $scheduleItems = $loan->repaymentSchedules()
                ->whereIn('status', ['pending', 'partial', 'overdue'])
                ->orderBy('due_date')
                ->get();

            // 4. Allocation Logic (Pay oldest debts first: Penalties -> Fees -> Interest -> Principal)
            foreach ($scheduleItems as $item) {
                if ($remainingAmount <= 0) break;

                $allocationResult = $this->allocatePaymentToScheduleItem($item, $remainingAmount);
                
                $remainingAmount = $allocationResult['remaining_amount'];
                
                // Track breakdown for the main Payment record
                $paymentBreakdown['principal_total'] += $allocationResult['principal_applied'];
                $paymentBreakdown['interest_total'] += $allocationResult['interest_applied'];
                $paymentBreakdown['fees_total'] += $allocationResult['fees_applied'];
                $paymentBreakdown['penalty_total'] += $allocationResult['penalty_applied'];
                
                // Track data for the pivot table
                if ($allocationResult['amount_applied'] > 0) {
                    $paymentAllocations[] = [
                        'repayment_schedule_id' => $item->id,
                        'amount_applied' => $allocationResult['amount_applied'],
                        'principal_applied' => $allocationResult['principal_applied'],
                        'interest_applied' => $allocationResult['interest_applied'],
                        'fees_applied' => $allocationResult['fees_applied'],
                        'penalty_applied' => $allocationResult['penalty_applied'],
                    ];
                }
            }

            // 5. Create the Master Payment Record
            $payment = Payment::create(array_merge($validated, [
                'loan_id' => $loan->id,
                'group_center_id' => $loan->group_center_id,
                'payment_number' => 'P-' . strtoupper(Str::random(8)) . '-' . date('Y'),
                'principal_amount' => $paymentBreakdown['principal_total'],
                'interest_amount' => $paymentBreakdown['interest_total'],
                'fees_amount' => $paymentBreakdown['fees_total'],
                'penalty_amount' => $paymentBreakdown['penalty_total'],
                'created_by' => Auth::id(),
            ]));

            // 6. Save Allocations (Populate the pivot table)
            foreach ($paymentAllocations as &$alloc) {
                $alloc['payment_id'] = $payment->id;
                // Note: The pivot table usually doesn't need created_at/updated_at unless explicitly defined.
            }
            if (!empty($paymentAllocations)) {
                DB::table('payment_schedule_allocations')->insert($paymentAllocations);
            }

            // 7. Update Loan Totals
            $loan->increment('total_paid', $amountReceived);
            $loan->decrement('outstanding_principal', $paymentBreakdown['principal_total']);
            $loan->decrement('total_outstanding', $paymentBreakdown['principal_total'] + $paymentBreakdown['interest_total'] + $paymentBreakdown['fees_total'] + $paymentBreakdown['penalty_total']);
            
            // 8. Handle Over/Under Payment (Simplified)
            if ($remainingAmount > 0) {
                // Handle overpayment (e.g., credit the client's account, update loan pre-payment status)
                // For now, we'll just log it in the remarks.
                $loan->update(['remarks' => $loan->remarks . "\n" . "Overpayment of {$remainingAmount} recorded on {$validated['payment_date']}."]);
            }
            
            // 9. Check if loan is completed
            if ($loan->outstanding_principal <= 0.01) {
                $loan->update(['status' => 'completed', 'closed_at' => now()]);
            }

            return redirect()->route('loans.show', $loan)->with('success', 'Payment recorded and allocated successfully.');
        });
    }

    // ----------------------------------------------------------------------
    // PRIVATE ALLOCATION HELPER METHOD
    // ----------------------------------------------------------------------

    /**
     * Core logic to apply a portion of the payment to a single schedule item.
     * Prioritizes penalties, then fees, then interest, then principal.
     */
    private function allocatePaymentToScheduleItem(RepaymentSchedule $item, float $paymentRemaining): array
    {
        $result = [
            'remaining_amount' => $paymentRemaining,
            'amount_applied' => 0,
            'principal_applied' => 0,
            'interest_applied' => 0,
            'fees_applied' => 0,
            'penalty_applied' => 0,
        ];

        // Debt Components (Due - Paid)
        // NOTE: Penalty logic is complex and often stored in a separate table, 
        // but we'll include a placeholder for it here. Assuming $item->penalty_due exists.
        $penaltyDue = 0.00; // Placeholder for actual penalty calculation
        $feesRemaining = $item->fees_due - $item->fees_paid;
        $interestRemaining = $item->interest_due - $item->interest_paid;
        $principalRemaining = $item->principal_due - $item->principal_paid;
        
        // 1. Apply to Penalty (if any)
        $applyPenalty = min($penaltyDue, $result['remaining_amount']);
        $result['remaining_amount'] -= $applyPenalty;
        $result['penalty_applied'] += $applyPenalty;
        
        // 2. Apply to Fees
        $applyFees = min($feesRemaining, $result['remaining_amount']);
        $result['remaining_amount'] -= $applyFees;
        $result['fees_applied'] += $applyFees;
        
        // 3. Apply to Interest
        $applyInterest = min($interestRemaining, $result['remaining_amount']);
        $result['remaining_amount'] -= $applyInterest;
        $result['interest_applied'] += $applyInterest;

        // 4. Apply to Principal
        $applyPrincipal = min($principalRemaining, $result['remaining_amount']);
        $result['remaining_amount'] -= $applyPrincipal;
        $result['principal_applied'] += $applyPrincipal;

        $result['amount_applied'] = $applyPenalty + $applyFees + $applyInterest + $applyPrincipal;
        
        // 5. Update Schedule Item (CRITICAL)
        if ($result['amount_applied'] > 0) {
            $item->increment('amount_paid', $result['amount_applied']);
            $item->increment('principal_paid', $result['principal_applied']);
            $item->increment('interest_paid', $result['interest_applied']);
            $item->increment('fees_paid', $result['fees_applied']);
            
            // Recalculate status
            if ($item->total_due - $item->amount_paid <= 0.01) {
                $item->status = 'paid';
                $item->paid_date = $result['remaining_amount'] == $paymentRemaining ? now() : $item->paid_date; // Set paid date if completely settled by THIS payment
            } elseif ($item->amount_paid > 0) {
                $item->status = 'partial';
            }
            $item->save();
        }

        return $result;
    }
}