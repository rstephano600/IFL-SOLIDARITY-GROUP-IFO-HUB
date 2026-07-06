<?php

namespace App\Http\Controllers\Loan;
use App\Http\Controllers\Controller;

use App\Models\Loan;
use App\Models\LoanCategory;
use App\Models\RepaymentSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanApprovalController extends Controller
{
    /**
     * Display list of pending loan requests.
     */
public function index(Request $request)
{
    $search = $request->input('search');
    $status = $request->input('status', 'pending'); // optional filter

    $loans = Loan::with([
        'client', 
        'group', 
        'groupCenter', 
        'loanCategory', 
        'collectionOfficer', 
        'createdBy', 
        'approvedBy', 
        'updatedBy'
    ])
    ->where('is_active', true)
    ->when($status, fn($q) => $q->where('status', $status))
    ->when($search, function ($query, $search) {
        $query->where(function ($q) use ($search) {
            $q->whereHas('client', function ($q) use ($search) {
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
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('approvedBy', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('updatedBy', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhere('loan_number', 'like', "%{$search}%");
        });
    })
    ->orderByDesc('created_at')
    ->paginate(30);

    return view('in.loans.requests.loan_approvals.index', compact('loans', 'search', 'status'));
}


    /**
     * Show a single pending loan for approval.
     */
    public function show($id)
    {
        $loan = Loan::with(['client', 'loanCategory'])->findOrFail($id);

        if ($loan->status !== 'pending') {
            return redirect()->route('loan-approvals.index')
                ->with('error', 'This loan has already been processed.');
        }

        return view('in.loans.requests.loan_approvals.show', compact('loan'));
    }


public function approve(Request $request, $id)
{
    $loan = Loan::findOrFail($id);
    $category = LoanCategory::findOrFail($loan->loan_category_id);

if ($loan->amount_paid < 2 * $loan->principal_due) {
    return redirect()->back()->with('error', 'Loan can’t be approved because the client has not started paying.');
}

    if ($loan->status !== 'pending') {
        return redirect()->back()->with('error', 'Loan already approved or processed.');
    }

    $membershipFee = $loan->membership_fee;

    // Step 1: Approve the loan
    $loan->update([
        'amount_disbursed'       => $category->amount_disbursed ?? $loan->amount_requested,
        'interest_rate'          => $category->interest_rate ?? 0,
        'interest_amount'        => $category->interest_amount ?? 0,
        'membership_fee_paid'    => $membershipFee ?? 0,
        'insurance_fee_paid'     => $category->insurance_fee ?? 0,
        'officer_visit_fee_paid' => $category->officer_visit_fee ?? 0,
        'status'                 => 'approved',
        'approved_by'            => Auth::id(),
        'disbursement_date'      => now(),
    ]);

    return redirect()
        ->route('loan-approvals.index')
        ->with('success', 'Loan approved and repayment schedule generated successfully.');
}

    /**
     * Reject a loan.
     */
    public function reject(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return redirect()->back()->with('error', 'Loan already processed.');
        }

        $loan->update([
            'status' => 'closed',
            'closure_reason' => $request->input('reason', 'Rejected by manager'),
            'approved_by' => Auth::id(),
            'closed_at' => now(),
        ]);

        return redirect()->route('loan-approvals.index')->with('success', 'Loan request rejected.');
    }
}
