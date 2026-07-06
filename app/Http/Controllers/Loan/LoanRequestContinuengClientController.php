<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanCategory;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\GroupCenter;
use App\Models\Group;
use App\Models\User;
use App\Models\RepaymentSchedule;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;


class LoanRequestContinuengClientController extends Controller
{

public function index(Request $request)
{
    $user = auth()->user();
    $search = $request->input('search');
    
    // Get users and clients for dropdowns
    $users = User::where('status', 'active')->orWhere('status', 'inactive')->get();
    $clients = Client::where('status', 'active')->orWhere('status', 'inactive')->get();

    $query = Loan::with(['client', 'loanCategory', 'createdBy', 'approvedBy', 'updatedBy'])
        ->whereIn('status', ['pending', 'approved', 'active'])
        ->where('is_new_client', 0)
        ->when($user->hasRole('loanofficer'), function ($query) use ($user) {
            $employee = Employee::where('user_id', $user->id)->first();
            if ($employee) {
                $query->where('collection_officer_id', $employee->id);
            }
        });

    // Search functionality
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('loan_number', 'like', "%{$search}%")
              ->orWhereHas('client', function ($q) use ($search) {
                  $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
              })
              ->orWhereHas('loanCategory', function ($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('createdBy', function ($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");

              });
        });
    }

    // Individual filters
    if ($request->filled('status')) {
        $query->where('status', $request->input('status'));
    }

    if ($request->filled('client_id')) {
        $query->where('client_id', $request->input('client_id'));
    }

    if ($request->filled('created_by')) {
        $query->where('created_by', $request->input('created_by'));
    }

    if ($request->filled('approved_by')) {
        $query->where('approved_by', $request->input('approved_by'));
    }

    if ($request->filled('updated_by')) {
        $query->where('updated_by', $request->input('updated_by'));
    }

    if ($request->filled('disbursed_date')) {
        $query->whereDate('disbursement_date', $request->input('disbursed_date'));
    }

    if ($request->filled('disbursed_date_from')) {
        $query->whereDate('disbursement_date', '>=', $request->input('disbursed_date_from'));
    }

    if ($request->filled('disbursed_date_to')) {
        $query->whereDate('disbursement_date', '<=', $request->input('disbursed_date_to'));
    }

    $loans = $query->latest()->paginate(30);

    return view('in.loans.requests.continuing_clients.index', compact('loans', 'search', 'users', 'clients'));
}

    /**
     * Show form for creating a new loan request.
     */

     public function create()
     {
            $user = auth()->user();
    // Active loan categories
         $categories = LoanCategory::where('is_active', true)->get();

    // Active clients

        $clients = Client::where('status', 'active')
        ->when($user->hasRole('loanofficer'), function ($query) use ($user) {
                // Assuming `Employee` is linked to `User` via user_id
                $employee = Employee::where('user_id', $user->id)->first();
                if ($employee) {
                    $query->where('credit_officer_id', $employee->id);
                }
            })
        ->with(['group', 'groupCenter'])
        ->get();


    // Group centers and groups for dropdowns (optional)
         $centers = GroupCenter::where('is_active', true)->get();
         $groups = Group::where('is_active', true)->get();

         return view('in.loans.requests.continuing_clients.create', compact('categories', 'clients', 'groups', 'centers'));
     }

    /**
     * Store a newly created loan request in database.
     */
    public function store(Request $request)
    {
    $validated = $request->validate([
        'client_id' => 'required|exists:clients,id',
        'loan_category_id' => 'required|exists:loan_categories,id',
    ]);

    $loanCategory = LoanCategory::findOrFail($validated['loan_category_id']);
    $client = Client::findOrFail($validated['client_id']);

    $loanNumber = 'LN-' . $client->last_name . '-' . strtoupper(Str::random(4)) . '-' . now()->format('YmdHis');

    $loan = Loan::create([
        'group_id' => $client->group_id,
        'group_center_id' => $client->group_center_id,
        'client_id' => $validated['client_id'],
        'collection_officer_id' => $client->credit_officer_id,
        'loan_category_id' => $loanCategory->id,
        'loan_number' => $loanNumber,

        'amount_requested' => $loanCategory->amount_disbursed ?? 0,
        'client_payable_frequency' => $loanCategory->principal_due ?? 0,
        'status' => 'pending',

        'membership_fee' => 0,
        'insurance_fee' => $loanCategory->insurance_fee ?? 0,
        'officer_visit_fee' => $loanCategory->officer_visit_fee ?? 0,
        'repayment_frequency' => $loanCategory->repayment_frequency ?? 'daily',
        'max_term_days' => $loanCategory->max_term_days ?? 0,
        'max_term_months' => $loanCategory->max_term_months ?? 0,
        'total_days_due' => $loanCategory->total_days_due ?? 0,
        'principal_due' => $loanCategory->principal_due ?? 0,
        'interest_due' => $loanCategory->interest_due ?? 0,
        'currency' => $loanCategory->currency ?? 'TZS',

        'created_by' => Auth::id(),
        'is_new_client' => false,
    ]);

    $totalDays = $loanCategory->total_days_due ?? 0;

    for ($i = 1; $i <= $totalDays; $i++) {
        RepaymentSchedule::create([
            'loan_id'        => $loan->id,
            'due_day_number' => $i,
            'principal_due'  => $loanCategory->principal_due ?? 0,
            'interest_due'   => $loanCategory->interest_due ?? 0,
            'days_left'      => $totalDays - $i,
            'status'         => 'pending',
            'created_by'     => Auth::id(),
        ]);
    }

        return redirect()
            ->route('loan_request_continueng_client.index')
            ->with('success', 'Loan request submitted successfully and is awaiting approval.');
    }

    /**
     * Display details of a specific loan request.
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
    return view('in.loans.requests.continuing_clients.show',  compact('loan', 'schedules', 'schedulesPaids'));
}

    /**
     * Edit loan request (optional before approval).
     */
    public function edit(Loan $loan)
    {
        $user = auth()->user();

        if ($loan->status !== 'approved') {
            return redirect()->back()->with('error', 'Only pending loan requests can be edited.');
        }

        $loanCategories = LoanCategory::where('is_active', true)->get();
        $clients = Client::where('status', 'active')
                ->when($user->hasRole('loanofficer'), function ($query) use ($user) {
                // Assuming `Employee` is linked to `User` via user_id
                $employee = Employee::where('user_id', $user->id)->first();
                if ($employee) {
                    $query->where('credit_officer_id', $employee->id);
                }
            })
        ->with(['group', 'groupCenter'])
        ->get();

        return view('in.loans.requests.continuing_clients.edit', compact('loan', 'loanCategories', 'clients'));
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
            'loan_category_id' => 'required|exists:loan_categories,id',
        ]);

        $loanCategory = LoanCategory::findOrFail($validated['loan_category_id']);

        $loan->update([
            // client request details
            'amount_requested' => $loanCategory->amount_disbursed ?? 0,
            'client_payable_frequency' =>$loanCategory->$repayment_frequency,

            // preload from category
            'amount_disbursed' => $loanCategory->amount_disbursed ?? 0, // initially same as requested
            'membership_fee' => 0,
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
            'updated_by' => Auth::id(),

            'is_new_client' => false,
        ]);

        return redirect()
            ->route('loan_request_continueng_client.index')
            ->with('success', 'Loan request updated successfully.');
    }

    /**
     * Delete a pending loan request.
     */
public function destroy(Loan $loan)
{
    // Only allow delete if loan is pending
if (strtolower(trim($loan->status)) === 'pending') {
    return redirect()->back()->with('error', 'Only pending loans can be deleted.');
}
    // $loan->delete();

    return redirect()
        ->route('loan_request_continueng_client.index')
        ->with('success', 'Loan request deleted successfully.');
}

}
