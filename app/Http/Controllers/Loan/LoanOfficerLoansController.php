<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use App\Models\Loan;
use App\Models\Client;
use App\Models\Group;
use App\Models\GroupCenter;
use App\Models\LoanCategory;
use App\Models\RepaymentSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LoanOfficerLoansController extends Controller
{
public function index(Request $request)
{
    $search = $request->input('search');

    $loans = Loan::with(['client', 'group', 'groupCenter', 'loanCategory', 'collectionOfficer', 'createdBy', 'approvedBy', 'updatedBy'])
        ->where('is_active', true)
        ->when($search, function ($query, $search) {
            $query->whereHas('client', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            })
            ->orWhereHas('collectionOfficer', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            })
            ->orWhereHas('group', function ($q) use ($search) {
                $q->where('group_name', 'like', "%{$search}%");
            })
            ->orWhereHas('createdBy', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            })
            ->orWhereHas('approvedBy', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            })
            ->orWhereHas('updatedBy', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        })
        // New individual filters
        ->when($request->filled('created_by'), function ($query) use ($request) {
            $query->where('created_by', $request->input('created_by'));
        })
        ->when($request->filled('approved_by'), function ($query) use ($request) {
            $query->where('approved_by', $request->input('approved_by'));
        })
        ->when($request->filled('updated_by'), function ($query) use ($request) {
            $query->where('updated_by', $request->input('updated_by'));
        })
        ->when($request->filled('disbursed_date'), function ($query) use ($request) {
            $query->whereDate('disbursed_date', $request->input('disbursed_date'));
        })
        // Date range filters for disbursed_date
        ->when($request->filled('disbursed_date_from'), function ($query) use ($request) {
            $query->whereDate('disbursed_date', '>=', $request->input('disbursed_date_from'));
        })
        ->when($request->filled('disbursed_date_to'), function ($query) use ($request) {
            $query->whereDate('disbursed_date', '<=', $request->input('disbursed_date_to'));
        })
        ->orderByDesc('created_at')
        ->paginate(15);

    return view('in.loans.loan-officers.index', compact('loans', 'search'));
}


    /**
     * Show the form for creating a new loan.
     */
    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $categories = LoanCategory::where('is_active', true)->get();
        $groups = Group::all();
        $groupCenters = GroupCenter::all();

        return view('in.loans.loan-officers.create', compact('clients', 'categories', 'groups', 'groupCenters'));
    }

    /**
     * Store a newly created loan request (before approval).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_center_id' => 'nullable|integer|exists:group_centers,id',
            'group_id' => 'nullable|integer|exists:groups,id',
            'client_id' => 'required|integer|exists:clients,id',
            'loan_category_id' => 'required|integer|exists:loan_categories,id',
            'amount_requested' => 'required|numeric|min:0',
            'client_payable_frequency' => 'required|numeric|min:0',
        ]);

        $category = LoanCategory::findOrFail($validated['loan_category_id']);

        // Generate a unique loan number
        $validated['loan_number'] = 'LN-' . strtoupper(Str::random(6));

        // Default loan setup (pending approval)
        $validated['status'] = 'pending';
        $validated['interest_rate'] = $category->interest_rate;
        $validated['amount_disbursed'] = 0;
        $validated['created_by'] = Auth::id();
        $validated['is_active'] = true;
        $validated['is_new_client'] = true;

        Loan::create($validated);

        return redirect()
            ->route('loan-pfficers-loans.index')
            ->with('success', 'Loan request created successfully and is awaiting approval.');
    }

    /**
     * Display the specified loan details.
     */
public function show(Loan $loan)
{
    $loan->load(['client', 'group', 'groupCenter', 'loanCategory']);
    
$schedules = RepaymentSchedule::where('loan_id', $loan->id)
    ->where('is_paid', false)
    ->orderBy('due_day_number', 'asc')
    ->get();

$schedulesPaids = RepaymentSchedule::where('loan_id', $loan->id)
    ->where('is_paid', true)
    ->orderBy('due_day_number', 'asc')
    ->get();

    
    return view('in.loans.loan-officers.show', compact('loan', 'schedules', 'schedulesPaids'));
}


    /**
     * Show the form for editing the specified loan (before approval).
     */
    public function edit(Loan $loan)
    {
        $clients = Client::where('status', 'active')->get();
        $categories = LoanCategory::where('is_active', true)->get();
        $groups = Group::all();
        $groupCenters = GroupCenter::all();

        return view('in.loans.loan-officers.edit', compact('loan', 'clients', 'categories', 'groups', 'groupCenters'));
    }

    /**
     * Update loan details.
     */
    public function update(Request $request, Loan $loan)
    {
        $validated = $request->validate([
            'group_center_id' => 'nullable|integer|exists:group_centers,id',
            'group_id' => 'nullable|integer|exists:groups,id',
            'client_id' => 'required|integer|exists:clients,id',
            'loan_category_id' => 'required|integer|exists:loan_categories,id',
            'amount_requested' => 'required|numeric|min:0',
            'client_payable_frequency' => 'required|numeric|min:0',
        ]);

        $loan->update($validated + ['updated_by' => Auth::id()]);

        return redirect()
            ->route('loan-pfficers-loans.show', $loan)
            ->with('success', 'Loan request updated successfully.');
    }

}

