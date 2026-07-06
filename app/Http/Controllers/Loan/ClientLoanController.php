<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Models\ClientLoan;
use App\Models\Client;
use App\Models\GroupCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClientLoanController extends Controller
{
    /**
     * Display a listing of all client loans.
     */
    public function index(Request $request)
    {
        $query = ClientLoan::with(['client', 'groupCenter'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('client', function ($q2) use ($request) {
                    $q2->where('first_name', 'like', '%' . $request->search . '%')
                       ->orWhere('last_name', 'like', '%' . $request->search . '%');
                })->orWhere('loan_number', 'like', '%' . $request->search . '%');
            })
            ->latest();

        $loans = $query->paginate(15);

        return view('in.loans.client_loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new loan.
     */
    public function create()
    {
        $clients = Client::all();
        $groupCenters = GroupCenter::all();
        return view('in.loans.client_loans.create', compact('clients', 'groupCenters'));
    }

    /**
     * Store a newly created loan in storage.
     */

public function store(Request $request)
{
    $validated = $request->validate([
        'client_id' => 'required|exists:clients,id',
        'group_center_id' => 'nullable|exists:group_centers,id',
        'amount_requested' => 'required|numeric|min:0',
        'payable_frequency' => 'required|numeric|min:0',
        'repayment_frequency' => 'required|in:daily,weekly,bi_weekly,monthly,yearly,quarterly',
    ]);

    $validated['loan_number'] = 'LN-' . strtoupper(Str::random(6));

    $validated['created_by'] = Auth::id();

    ClientLoan::create($validated);

    return redirect()->route('client_loans.index')->with('success', 'Client loan created successfully.');
}


    /**
     * Display the specified loan.
     */
    public function show(ClientLoan $clientLoan)
    {
        $clientLoan->load(['client', 'groupCenter', 'dailyCollections', 'imprestCertificates']);
        return view('in.loans.client_loans.show', compact('clientLoan'));
    }

    /**
     * Show the form for editing the specified loan.
     */
    public function edit(ClientLoan $clientLoan)
    {
        $clients = Client::all();
        $groupCenters = GroupCenter::all();
        return view('in.loans.client_loans.edit', compact('clientLoan', 'clients', 'groupCenters'));
    }

    /**
     * Update the specified loan in storage.
     */
    public function update(Request $request, ClientLoan $clientLoan)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'group_center_id' => 'nullable|exists:group_centers,id',
            'loan_number' => 'required|unique:client_loans,loan_number,' . $clientLoan->id,
            'amount_requested' => 'required|numeric|min:0',
            'payable_frequency' => 'required|numeric|min:0',
            'repayment_frequency' => 'required|in:daily,weekly,bi_weekly,monthly,yearly,quarterly',
            'interest_rate' => 'nullable|numeric|min:0',
            'amount_disbursed' => 'nullable|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'penalty_fee' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,approved,disbursed,completed,defaulted',
            'remarks' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['updated_by'] = Auth::id();

        $clientLoan->update($validated);

        return redirect()->route('client_loans.index')->with('success', 'Client loan updated successfully.');
    }

    /**
     * Remove the specified loan from storage.
     */
    public function destroy(ClientLoan $clientLoan)
    {
        $clientLoan->delete();
        return redirect()->route('client_loans.index')->with('success', 'Client loan deleted successfully.');
    }

    /**
     * Mark loan as closed (optional feature).
     */
    public function closeLoan(Request $request, ClientLoan $clientLoan)
    {
        $clientLoan->update([
            'closed_at' => now(),
            'closure_reason' => $request->input('closure_reason', 'Loan closed manually'),
            'status' => 'completed',
            'updated_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Loan closed successfully.');
    }
}

