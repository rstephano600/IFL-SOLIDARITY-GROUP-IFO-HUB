<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use App\Models\Loan;
use App\Models\LoanCategory;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoanRequestController extends Controller
{
    /**
     * Display a listing of client loan requests (pending or active).
     */
    public function index(Request $request)
    {
        $query = Loan::with(['client', 'loanCategory'])
        ->when($user->hasRole('loanofficer'), function ($query) use ($user) {
                // Assuming `Employee` is linked to `User` via user_id
                $employee = Employee::where('user_id', $user->id)->first();
                if ($employee) {
                    $query->where('collection_officer_id', $employee->id);
                }
            });
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->input('client_id'));
        }

        $loans = $query->latest()->paginate(10);

        return view('in.loans.requests.index', compact('loans'));
    }

    /**
     * Show form for creating a new loan request.
     */
    public function create()
    {
        $loanCategories = LoanCategory::where('is_active', true)->get();
        $clients = Client::where('status', 'active')->get();

        return view('in.loans.requests.create', compact('loanCategories', 'clients'));
    }

    /**
     * Store a newly created loan request in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'group_id' => 'nullable|integer',
            'group_center_id' => 'nullable|integer',
            'loan_category_id' => 'required|exists:loan_categories,id',
            'amount_requested' => 'required|numeric|min:1000',
            'client_payable_frequency' => 'required|numeric|min:0',
        ]);

        // Fetch the chosen loan category
        $loanCategory = LoanCategory::findOrFail($validated['loan_category_id']);

        // Generate a unique loan number
        $loanNumber = 'LN-' . strtoupper(Str::random(6)) . '-' . now()->format('YmdHis');

        // Create the pending loan request
        $loan = Loan::create([
            'group_center_id' => $validated['group_center_id'] ?? null,
            'group_id' => $validated['group_id'] ?? null,
            'client_id' => $validated['client_id'],
            'loan_category_id' => $loanCategory->id,
            'loan_number' => $loanNumber,

            // client request details
            'amount_requested' => $validated['amount_requested'],
            'client_payable_frequency' => $validated['client_payable_frequency'],
            'status' => 'pending',

            // preload from category
            'amount_disbursed' => $validated['amount_requested'], // initially same as requested
            'insurance_fee' => $loanCategory->insurance_fee ?? 0,
            'officer_visit_fee' => $loanCategory->officer_visit_fee ?? 0,
            'interest_rate' => $loanCategory->interest_rate ?? 0,
            'interest_amount' => $loanCategory->interest_amount ?? 0,
            'repayment_frequency' => $loanCategory->repayment_frequency ?? 'daily',
            'max_term_days' => $loanCategory->max_term_days ?? 0,
            'max_term_months' => $loanCategory->max_term_months ?? 0,
            'total_days_due' => $loanCategory->total_days_due ?? 0,
            'principal_due' => $loanCategory->principal_due ?? 0,
            'interest_due' => $loanCategory->interest_due ?? 0,
            'currency' => $loanCategory->currency ?? 'TZS',

            // system
            'created_by' => Auth::id(),
            'is_active' => true,
        ]);

        return redirect()
            ->route('loan_requests.index')
            ->with('success', 'Loan request submitted successfully and is awaiting approval.');
    }

    /**
     * Display details of a specific loan request.
     */
    public function show(Loan $loan)
    {
        $loan->load(['client', 'loanCategory']);


        return view('in.loans.requests.show', compact('loan'));
    }

    /**
     * Edit loan request (optional before approval).
     */
    public function edit(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending loan requests can be edited.');
        }

        $loanCategories = LoanCategory::where('is_active', true)->get();
        $clients = Client::where('status', 'active')->get();

        return view('in.loans.requests.edit', compact('loan', 'loanCategories', 'clients'));
    }

    /**
     * Update a pending loan request.
     */
    public function update(Request $request, Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending loans can be updated.');
        }

        $validated = $request->validate([
            'amount_requested' => 'required|numeric|min:1000',
            'client_payable_frequency' => 'required|numeric|min:0',
            'loan_category_id' => 'required|exists:loan_categories,id',
        ]);

        $loanCategory = LoanCategory::findOrFail($validated['loan_category_id']);

        $loan->update([
            'amount_requested' => $validated['amount_requested'],
            'client_payable_frequency' => $validated['client_payable_frequency'],
            'loan_category_id' => $loanCategory->id,
            'interest_rate' => $loanCategory->interest_rate,
            'interest_amount' => $loanCategory->interest_amount,
            'repayment_frequency' => $loanCategory->repayment_frequency,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('loan_requests.index')
            ->with('success', 'Loan request updated successfully.');
    }

    /**
     * Delete a pending loan request.
     */
    public function destroy(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending loans can be deleted.');
        }

        $loan->delete();

        return redirect()
            ->route('loan_requests.index')
            ->with('success', 'Loan request deleted successfully.');
    }
}
