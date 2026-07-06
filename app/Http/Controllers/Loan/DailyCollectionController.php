<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\DailyCollection;
use App\Models\GroupCenter;
use App\Models\ClientLoan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DailyCollectionController extends Controller
{
    /**
     * Display all daily collections
     */
public function index(Request $request)
{
    $query = DailyCollection::with(['loan.client'])
        ->latest();

    // 🔍 Search by client name, loan number, or payment method
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->whereHas('loan.client', function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%");
        })->orWhereHas('loan', function ($q) use ($search) {
            $q->where('loan_number', 'like', "%{$search}%");
        })->orWhere('payment_method', 'like', "%{$search}%");
    }

    // 📅 Filter by date range
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('date_of_payment', [
            $request->input('start_date'),
            $request->input('end_date')
        ]);
    }

    // 📋 Paginate results
    $collections = $query->paginate(10)->appends($request->query());

    return view('in.loans.client_loans.daily_collections.index', compact('collections'));
}


    /**
     * Show form to create a new daily collection
     */
   public function create(Request $request)
{
    $loan = null;

    if ($request->has('loan_id')) {
        $loan = ClientLoan::with('client', 'groupCenter')->findOrFail($request->loan_id);
        return view('in.loans.client_loans.daily_collections.create', compact('loan'));
    }

    // fallback (manual add)
    $loans = ClientLoan::with('client', 'groupCenter')->get();
    $groupCenters = GroupCenter::all();
    return view('in.loans.client_loans.daily_collections.create', compact('loans', 'groupCenters'));
}


    /**
     * Store a new collection record and update the loan repayment info
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_loan_id'   => 'required|exists:client_loans,id',
            'group_center_id'  => 'nullable|exists:group_centers,id',
            'date_of_payment'  => 'required|date',
            'amount_paid'      => 'required|numeric|min:0',
            'penalty_fee'      => 'nullable|numeric|min:0',
            'total_preclosure' => 'nullable|numeric|min:0',
            'payment_method'   => 'required|in:bank_transfer,cash,cheque,mobile_money,direct_debit,card,other',
        ]);

        $loan = ClientLoan::findOrFail($validated['client_loan_id']);

        // Determine if this is the first payment
        $firstPayment = $loan->amount_paid == 0;
        $validated['first_date_pay'] = $firstPayment;
        $validated['last_date_pay']  = false;
        $validated['created_by']     = Auth::id();

        // Create collection record
        $collection = DailyCollection::create($validated);

        // Update loan repayment info
        $newAmountPaid = $loan->amount_paid + $validated['amount_paid'];
        $outstandingBalance = max(0, $loan->total_payable - $newAmountPaid);

        $loan->update([
            'amount_paid'         => $newAmountPaid,
            'outstanding_balance' => $outstandingBalance,
            'penalty_fee'         => $validated['penalty_fee'] ?? 0,
            'total_preclosure'    => $validated['total_preclosure'] ?? $loan->total_preclosure,
            'start_date'          => $firstPayment ? $validated['date_of_payment'] : $loan->start_date,
            'end_date'            => $outstandingBalance == 0 ? $validated['date_of_payment'] : $loan->end_date,
            'days_left'           => $outstandingBalance == 0 ? 0 : $loan->days_left - 1,
        ]);

    if ($outstandingBalance == 0) {
        $loan->update(['status' => 'completed']);
        $collection->update(['last_date_pay' => true]);

        // 🔔 Trigger notification to client
        event(new \App\Events\LoanCompletedEvent($loan));
    }

        return redirect()
            ->route('daily_collections.index')
            ->with('success', 'Daily collection recorded and loan updated successfully.');
    }

    /**
     * Display single collection details
     */
    public function show($id)
    {
        $collection = DailyCollection::with('loan.client')->findOrFail($id);
        return view('in.loans.client_loans.daily_collections.show', compact('collection'));
    }
}
