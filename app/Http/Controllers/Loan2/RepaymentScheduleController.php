<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use App\Models\Loan;
use App\Models\RepaymentSchedule;
use Illuminate\Http\Request;

class RepaymentScheduleController extends Controller
{
    /**
     * Display the full repayment schedule for a specific loan.
     * Accessible via a route like: /loans/{loan}/schedule
     */
    public function index(Loan $loan)
    {
        // Eager load related payments for each schedule item to show reconciliation
        $schedule = $loan->repaymentSchedules()
                         ->orderBy('installment_number')
                         ->with('payments') // Use the BelongsToMany relationship defined in the model
                         ->get();

        return view('in.loans.schedules.index', compact('loan', 'schedule'));
    }

    /**
     * Display a specific installment detail.
     * Used to show the expected payment, status, and linked actual payments.
     * Accessible via a route like: /schedules/{repaymentSchedule}
     */
    public function show(RepaymentSchedule $repaymentSchedule)
    {
        $repaymentSchedule->load('loan.client', 'payments.creator');

        return view('in.loans.schedules.show', compact('repaymentSchedule'));
    }

    // Note: Create, Store, Edit, and Update methods are typically NOT needed here, 
    // as the schedule is automatically generated (stored) by the LoanController 
    // and updated by the PaymentController.
}