<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\RepaymentSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RepaymentScheduleController extends Controller
{
    public function show($loanId)
    {
        $loan = Loan::with('client')->findOrFail($loanId);
        $schedules = RepaymentSchedule::where('loan_id', $loanId)
            ->orderBy('installment_number', 'asc')
            ->get();

        return view('repayments.show', compact('loan', 'schedules'));
    }

    public function pay($id)
    {
    $schedule = RepaymentSchedule::findOrFail($id);
    $loan = Loan::findOrFail($schedule->loan_id);
    $loanStatus = $loan->status;

    // 🛑 Prevent paying if already paid
    if ($schedule->status === 'paid') {
        return back()->with('info', 'This installment is already paid.');
    }

    if ($loanStatus === 'refunded') {
        return back()->with('info', 'Only Active, Aproved Loans can be paid paid.');
    }

    if ($loanStatus === 'closed') {
        return back()->with('info', 'Only Active, Aproved Loans can be paid paid.');
    }

    // ✅ Ensure schedule order - get previous unpaid
    $previousSchedule = RepaymentSchedule::where('loan_id', $loan->id)
        ->where('due_day_number', '<', $schedule->due_day_number)
        ->where('status', '!=', 'paid')
        ->orderBy('due_day_number', 'desc')
        ->first();
        // 🧠 This means user skipped a previous day
    // if ($previousSchedule) {

    //     return back()->with('warning', 
    //         "You must first pay Day {$previousSchedule->due_day_number} before paying Day {$schedule->due_day_number}."
    //     );
    // }

    // ✅ Auto-mark start & end date schedules
    $firstSchedule = RepaymentSchedule::where('loan_id', $loan->id)
        ->orderBy('due_day_number', 'asc')
        ->first();

    $lastSchedule = RepaymentSchedule::where('loan_id', $loan->id)
        ->orderBy('due_day_number', 'desc')
        ->first();

    if ($schedule->id === $firstSchedule->id) {
        $schedule->is_start_date = true;
    }

    if ($schedule->id === $lastSchedule->id) {
        $schedule->is_end_date = true;
    }

    // ✅ Generate unique installment reference
    $installmentNumber = 'INST-' . 'DAY' . $schedule->due_day_number . '-' . strtoupper(Str::random(4)) . '-' . now()->format('Ymd');

    // ✅ Update repayment schedule as paid
    $schedule->update([
        'installment_number' => $installmentNumber,
        'principal_paid' => $schedule->principal_due,
        'interest_paid' => $schedule->interest_due,
        'penalty_paid' => $schedule->penalty_due,
        'paid_date' => now(),
        'status' => 'paid',
        'paid_by' => Auth::id(),
        'is_paid' => true,
        'is_start_date' => $schedule->is_start_date,
        'is_end_date' => $schedule->is_end_date,
    ]);

    // ✅ Update loan totals
    $loan->update([
        'amount_paid' => $loan->amount_paid + $schedule->principal_due,
        'penalty_fee_paid' => $loan->penalty_fee_paid + $schedule->penalty_due,
        'other_fee_paid' => $loan->other_fee_paid,
        'days_left' => $schedule->days_left,
    ]);

    // ✅ Check if all are paid
    $allPaid = $loan->repaymentSchedules()->where('status', '!=', 'paid')->count() === 0;
    if ($allPaid) {
        $loan->update(['status' => 'completed', 'closed_at' => now()]);
    }

    return back()->with('success', "Installment {$installmentNumber} paid successfully.");
}


public function addPenalty($id)
{
    $schedule = RepaymentSchedule::findOrFail($id);
    $loan = $schedule->loan;

    // check if already paid
    if ($schedule->status === 'paid') {
        return back()->with('info', 'This installment is already paid. No penalty applied.');
    }

    // constant daily penalty
    $penaltyPerDay = 2000;

    // calculate how many days late
    $dueDate = Carbon::parse($schedule->paid_date);
    $daysLate = $dueDate->isPast() ? $dueDate->diffInDays(now()) : 0;

    if ($daysLate <= 0) {
        return back()->with('info', 'This installment is not late yet.');
    }

    // compute new penalty
    // $penaltyAmount = $daysLate * $penaltyPerDay;
    $penaltyAmount = $penaltyPerDay;

    // update schedule
    $schedule->update([
        'penalty_due' => $penaltyAmount,
        'status' => 'overdue',
        'updated_by' => Auth::id(),
    ]);

    // update loan totals
    $loan->increment('penalty_fee', $penaltyAmount);
    // $loan->increment('outstanding_balance', $penaltyAmount);

    return back()->with('success', "Penalty of {$penaltyAmount} applied for {$daysLate} day(s) late.");
}
}

