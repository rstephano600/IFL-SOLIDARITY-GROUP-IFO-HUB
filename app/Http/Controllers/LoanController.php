<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanCategory;
use App\Models\Loan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employee;
use App\Models\NextOfKin;
use App\Models\Referee;
use App\Models\Client;
use App\Models\Group;
use App\Models\GroupCenter;
use App\Models\LoanRepayment;
use App\Models\LoanPenaltyCategory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers\LogActivity;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use App\Imports\LoanRepaymentImport;
use App\Exports\LoanRepaymentTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Guarantor;
use App\Models\LoanGuarantor;
use App\Models\LoanPenalty;
use App\Models\LoanRepaymentFee;
use App\Models\LoanTopup;
use App\Models\LoanRefund;

use Illuminate\Contracts\Encryption\DecryptException;

class LoanController extends Controller
{

    // LOANCATEGORIES INFORMATIONS
    public function loancategories()
    {
        try{
        $data = LoanCategory::where('Status', 'Active')->get();
        return view('in.loans.loancategories', compact('data'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storeloancategory(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'amount_disbursed' => 'required|numeric|min:0',
            'principal_due' => 'required|numeric|min:0',
            'insurance_fee' => 'nullable|numeric|min:0',
            'officer_visit_fee' => 'nullable|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0|max:100',

            'repayment_frequency' => 'required|in:daily,weekly,bi_weekly,monthly,quarterly',
            'max_term_days' => 'nullable|integer|min:0',
            'max_term_months' => 'nullable|integer|min:0',

            'currency' => 'nullable|string|max:10',
            'conditions' => 'nullable|string',
            'descriptions' => 'nullable|string',
            'is_new_client' => 'boolean',

        ]);
        $amount_disbursed = (float) $request->input('amount_disbursed');
        $interest_rate = (float) $request->input('interest_rate', 20);
        $principal_due = (float) $request->input('principal_due');

        // ✅ Calculate interest and dues safely
        $interest = ($amount_disbursed * $interest_rate) / 100;
        $repayableAmount = $interest + $amount_disbursed;

        // Avoid division by zero
        if ($principal_due > 0) {
            $totalDaysDue = $repayableAmount / $principal_due;
            $interestDue = ($principal_due * $interest_rate) / 100;
        } else {
            $totalDaysDue = 0;
            $interestDue = 0;
        }

        try {

            LoanCategory::create([

                'name'                 => $request->name,
                'amount_disbursed'     => $request->amount_disbursed,
                'insurance_fee'        => $request->insurance_fee ?? 0,
                'officer_visit_fee'    => $request->officer_visit_fee ?? 0,
                'interest_rate'        => $request->interest_rate,
                'interest_amount'      => $interest,
                'repayment_frequency'  => $request->repayment_frequency,
                'total_days_due'       => $totalDaysDue,
                'max_term_days'        => $request->max_term_days,
                'max_term_months'      => $request->max_term_months,
                'principal_due'        => $request->principal_due,
                'interest_due'         => $interestDue,
                'currency'             => $request->currency,
                'conditions'           => $request->conditions,
                'descriptions'         => $request->descriptions,
                'is_active'            => true,
                'is_new_client'        => $request->is_new_client,

                'created_by'           => Auth::id(),
                'updated_by'           => Auth::id(),

                'User_id'              => Auth::id(),
                'Status'               => 'Active',
                'AuditingStatus'       => 'Pending',
                'ReportStatus'         => 'Pending'

            ]);

            Alert::success(
                'Success ' . ' ' . Auth()->user()->name, 'You\'ve Registered Loan Category Successfully' );
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function editloancategory($id)
    {
        try {

            $id = Crypt::decrypt($id);

            $data = LoanCategory::findOrFail($id);

            return view('in.loans.editloancategory', compact('data'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function updateloancategory(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([

            'name' => 'nullable|string|max:255',

            'amount_disbursed' => 'required|numeric|min:0',

            'principal_due' => 'required|numeric|min:0',

            'insurance_fee' => 'nullable|numeric|min:0',

            'officer_visit_fee' => 'nullable|numeric|min:0',

            'interest_rate' => 'nullable|numeric|min:0|max:100',

            'repayment_frequency' => 'required|in:daily,weekly,bi_weekly,monthly,quarterly',

            'max_term_days' => 'nullable|integer|min:0',

            'max_term_months' => 'nullable|integer|min:0',

            'currency' => 'nullable|string|max:10',

            'conditions' => 'nullable|string',

            'descriptions' => 'nullable|string',

            'is_new_client' => 'boolean',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Calculations
        |--------------------------------------------------------------------------
        */

        $amount_disbursed =
            (float) $request->input('amount_disbursed');

        $interest_rate =
            (float) $request->input('interest_rate', 20);

        $principal_due =
            (float) $request->input('principal_due');

        $insurance_fee =
            (float) ($request->insurance_fee ?? 0);

        $officer_visit_fee =
            (float) ($request->officer_visit_fee ?? 0);


        $interest =
            ($amount_disbursed * $interest_rate) / 100;

        $repayableAmount =
            $interest
            + $amount_disbursed
            + $insurance_fee
            + $officer_visit_fee;


        if ($principal_due > 0) {

            $totalDaysDue =
                ceil($repayableAmount / $principal_due);

            $interestDue =
                ($principal_due * $interest_rate) / 100;

        } else {

            $totalDaysDue = 0;

            $interestDue = 0;
        }


        try {

            $loan = LoanCategory::findOrFail($id);

            $loan->update([

                'name'                 => $request->name,

                'amount_disbursed'     => $amount_disbursed,

                'insurance_fee'        => $insurance_fee,

                'officer_visit_fee'    => $officer_visit_fee,

                'interest_rate'        => $interest_rate,

                'interest_amount'      => $interest,

                'repayment_frequency'  => $request->repayment_frequency,

                'total_days_due'       => $totalDaysDue,

                'max_term_days'        => $request->max_term_days,

                'max_term_months'      => $request->max_term_months,

                'principal_due'        => $principal_due,

                'interest_due'         => $interestDue,

                'currency'             => $request->currency,

                'conditions'           => $request->conditions,

                'descriptions'         => $request->descriptions,

                'is_new_client'        => $request->is_new_client,

                'updated_by'           => Auth::id(),

            ]);


            Alert::success(
                'Success ' . ' ' . Auth()->user()->name,
                'You\'ve Updated Loan Category Successfully'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function destroyloancategory($id)
    {
        try {

            $id = Crypt::decrypt($id);

            $loan = LoanCategory::findOrFail($id);

            $loan->update([
                'Status' => 'Deleted'
            ]);

            Alert::success(
                'Success ' . ' ' . Auth()->user()->name,
                'You\'ve removed Loan Category successfully'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    } 

    public function viewloancategory($id)
    {
        try {

            $id = Crypt::decrypt($id);

            $data = LoanCategory::findOrFail($id);

            return view(
                'in.loans.viewloancategory',
                compact('data')
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }


    // LOAN INFORMATIONS
    public function loansinformations()
    {
        try {

            $data = Loan::with([
                'client',
                'loanCategory',
                'group',
                'groupCenter'
            ])
            ->where('Status', 'Active')
            ->latest()
            ->get();
            $clients = Client::where('Status', 'Active')->get();
            $loanCategories = LoanCategory::where('Status', 'Active')->get();
            $groups = Group::where('Status', 'Active')->get();
            $groupCenters = GroupCenter::where('Status', 'Active')->get();
            return view( 'in.loans.loansinformations', compact('data', 'clients', 'loanCategories', 'groups', 'groupCenters'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function viewloaninformation($id)
    {
        try {

            $id = Crypt::decrypt($id);

            $data = Loan::with([
                'client',
                'loanCategory',
                'group',
                'groupCenter'
            ])->findOrFail($id);
            return view(
                'in.loans.viewloaninformation',
                compact('data')
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function registerloaninformation(Request $request)
    {
        $validated = $request->validate([

            // 'group_id' => 'nullable|integer|exists:groups,id',
            'client_id' => 'required|integer|exists:clients,id',
            'loan_category_id' => 'required|integer|exists:loan_categories,id',
            'membership_fee' => 'required|numeric|min:0',
            // 'client_payable_frequency' => 'required|numeric|min:0',
            'application_date' => 'required|date',
        ]);

        try {
            $loanCategory = LoanCategory::findOrFail( $request->loan_category_id );
            // $group = Group::findOrFail( $request->group_id );
            // $groupCenter = $group->group_center_id;

            // $amountRequested = (float) $request->amount_requested;

            $latestLoan = Loan::latest()->first();
            $client = Client::findOrFail($validated['client_id']);
            $loanNumber = 'ArB-LN-' . $client->client->LastName . '-'. date('Ymd') . '-' . str_pad(($latestLoan ? $latestLoan->id + 1 : 1), 4, '0', STR_PAD_LEFT );

            Loan::create([
                'loan_number' => $loanNumber,
                'group_center_id' =>$client->group_center_id,
                'group_id' =>$client->group_id,
                'collection_officer_id' =>$client->group->credit_officer_id,
                'client_id' =>$request->client_id,
                'membership_fee' =>$request->membership_fee,
                'loan_category_id' =>$request->loan_category_id,
                'amount_requested' => $loanCategory->amount_disbursed,
                'client_payable_frequency' => $loanCategory->principal_due,
                'application_date' => $request->application_date,
                'is_active' => true,
                'ApprovalStatus' => 'Pending',
                'currency' => $loanCategory->currency,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'User_id' => Auth::id(),
            ]);

            Alert::success( 'Success ' . ' ' . Auth()->user()->name, 'Loan Information Registered Successfully');
            return back();
        } catch (\Throwable $th) {
            // Alert::error('Execution Error', $th->getMessage());
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function editloaninformation($id)
    {
        try {

            $id = Crypt::decrypt($id);

            $data = Loan::findOrFail($id);

            $clients = Client::where('Status', 'Active')->get();

            $groups = Group::where('Status', 'Active')->get();

            $loanCategories = LoanCategory::where('Status', 'Active')->get();

            return view(
                'in.loans.editloaninformation',
                compact(
                    'data',
                    'clients',
                    'groups',
                    'loanCategories'
                )
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function updateloaninformation(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $validated = $request->validate([
            'group_id' => 'nullable|integer|exists:groups,id',
            'client_id' => 'required|integer|exists:clients,id',
            'loan_category_id' => 'required|integer|exists:loan_categories,id',
            'amount_requested' => 'required|numeric|min:0',
            'membership_fee' => 'required|numeric|min:0',
            'client_payable_frequency' => 'required|numeric|min:0',
        ]);

        try {

            $loan = Loan::findOrFail($id);
            $loanCategory = LoanCategory::findOrFail(
                $request->loan_category_id
            );

            $groupCenter = null;
            if ($request->group_id) {
                $group = Group::findOrFail(
                    $request->group_id
                );
                $groupCenter = $group->group_center_id;
            }

            $loan->update([

                'group_center_id' =>$groupCenter,
                'group_id' =>$request->group_id,
                'client_id' =>$request->client_id,
                'loan_category_id' =>$request->loan_category_id,
                'membership_fee' =>$request->membership_fee,
                'amount_requested' =>$loanCategory->amount_disbursed,
                'client_payable_frequency' =>$loanCategory->principal_due,
                'currency' =>$loanCategory->currency,
                'updated_by' =>Auth::id()

            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Loan Information Updated Successfully'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

// LOANPENALITIES INFORMATIONS
public function loanpenaltycategories()
{
    try {

        $data = LoanPenaltyCategory::where('Status', 'Active')->get();

        return view(
            'in.loans.loanpenaltycategories',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}
public function storeloanpenaltycategory(Request $request)
{
    $request->validate([

        'name' => 'required|string|max:255',

        'conditions' => 'nullable|string',

        'descriptions' => 'nullable|string',

    ]);

    try {

        LoanPenaltyCategory::create([

            'name' => $request->name,

            'conditions' => $request->conditions,

            'descriptions' => $request->descriptions,

            'User_id' => Auth::id(),

            'Status' => 'Active',

            'AuditingStatus' => 'Pending',

            'ReportStatus' => 'Pending'

        ]);

        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'You\'ve Registered Loan Penalty Category Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function editloanpenaltycategory($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanPenaltyCategory::findOrFail($id);

        return view(
            'in.loans.editloanpenaltycategory',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}



public function updateloanpenaltycategory(Request $request, $id)
{
    $id = Crypt::decrypt($id);

    $request->validate([

        'name' => 'required|string|max:255',

        'conditions' => 'nullable|string',

        'descriptions' => 'nullable|string',

    ]);

    try {

        $category = LoanPenaltyCategory::findOrFail($id);

        $category->update([

            'name' => $request->name,

            'conditions' => $request->conditions,

            'descriptions' => $request->descriptions,

        ]);

        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'You\'ve Updated Loan Penalty Category Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}


public function viewloanpenaltycategory($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanPenaltyCategory::findOrFail($id);

        return view(
            'in.loans.viewloanpenaltycategory',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}


public function destroyloanpenaltycategory($id)
{
    try {

        $id = Crypt::decrypt($id);
        $category = LoanPenaltyCategory::findOrFail($id);
        $category->update([
            'Status' => 'Deleted'
        ]);
        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'You\'ve Removed Loan Penalty Category Successfully'
        );
        return back();
    } catch (\Throwable $th) {
        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );
        return back();
    }
}


    // LOAN PAYMENTS
    public function loansrepaymentsfees()
    {
        try {

            $data = LoanRepaymentFee::with([
                'loan',
                'client',
                'receiver'
            ])
            ->where('Status', 'Active')
            ->where('RepaymentStatus', 'ONGOING')
            ->latest()
            ->get();

            $loans = Loan::where('Status', 'Active')
                ->whereIn('status', ['Approved', 'Active'])
                ->get();

            return view(
                'in.loans.fees.loansrepaymentsfees', compact( 'data', 'loans')
            );
        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function storeloanrepaymentfee(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'payment_date' => 'required|date',
            'membership_fee_paid' => 'nullable|numeric|min:0',
            'officer_visit_fee_paid' => 'nullable|numeric|min:0',
            'insurance_fee_paid' => 'nullable|numeric|min:0',
            'preclosure_fee_paid' => 'nullable|numeric|min:0',
            'penalty_fee_paid' => 'nullable|numeric|min:0',
            'other_fee_paid' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);
        try {
            $loan = Loan::findOrFail( $request->loan_id );

            $membership_fee_paid = (float) $request->membership_fee_paid;
            $officer_visit_fee_paid = (float) $request->officer_visit_fee_paid;
            $insurance_fee_paid = (float) $request->insurance_fee_paid;
            $preclosure_fee_paid = (float) $request->preclosure_fee_paid;
            $penalty_fee_paid = (float) $request->penalty_fee_paid;
            $other_fee_paid = (float) $request->other_fee_paid;

            LoanRepaymentFee::create([
                'loan_id' =>$loan->id,
                'client_id' =>$loan->client_id,
                'payment_date' =>$request->payment_date,
                'membership_fee_paid' =>$request->membership_fee_paid,
                'officer_visit_fee_paid' =>$request->officer_visit_fee_paid,
                'insurance_fee_paid' =>$request->insurance_fee_paid,
                'preclosure_fee_paid' =>$request->preclosure_fee_paid,
                'penalty_fee_paid' =>$request->penalty_fee_paid,
                'other_fee_paid' =>$request->other_fee_paid,
                'payment_method' =>$request->payment_method,
                'reference_number' =>$request->reference_number,
                'remarks' =>$request->remarks,
                'received_by' =>Auth::id(),
                'User_id' =>Auth::id(),
            ]);

            $loan->increment( 'membership_fee_paid', $membership_fee_paid );
            $loan->increment( 'officer_visit_fee_paid', $officer_visit_fee_paid );
            $loan->increment( 'insurance_fee_paid', $insurance_fee_paid );
            $loan->increment( 'preclosure_fee_paid', $preclosure_fee_paid );
            $loan->increment( 'penalty_fee_paid', $penalty_fee_paid );
            $loan->increment( 'other_fee_paid', $other_fee_paid );
            $loan->refresh();

            Alert::success( 'Success ' . ' ' . Auth()->user()->name, 'Loan Fee Repayment Registered Successfully' );
            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

public function editloanrepaymentfee($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanRepaymentFee::findOrFail($id);

        return view(
            'in.loans.editloanrepayment',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}
public function updateloanrepaymentfee(Request $request, $id)
{
    $id = Crypt::decrypt($id);
    $request->validate([
        'payment_date' => 'required|date',
        'amount_paid' => 'required|numeric|min:1',
        'payment_method' => 'nullable|string|max:50',
        'reference_number' => 'nullable|string|max:100',
        'remarks' => 'nullable|string',
    ]);

    try {

        $repayment = LoanRepaymentFee::findOrFail($id);

        $loan = Loan::findOrFail(
            $repayment->loan_id
        );

        /*
        |--------------------------------------------------------------------------
        | Reverse Old Repayment
        |--------------------------------------------------------------------------
        */

        $loan->decrement(
            'amount_paid',
            $repayment->amount_paid
        );


        /*
        |--------------------------------------------------------------------------
        | New Allocation
        |--------------------------------------------------------------------------
        */

        $amountPaid =
            (float) $request->amount_paid;

        $principalPaid =
            min(
                $loan->principal_due,
                $amountPaid
            );

        $interestPaid =
            min(
                $loan->interest_due,
                max(
                    0,
                    $amountPaid - $principalPaid
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Update Repayment
        |--------------------------------------------------------------------------
        */

        $repayment->update([

            'payment_date' =>
                $request->payment_date,

            'amount_paid' =>
                $amountPaid,

            'principal_paid' =>
                $principalPaid,

            'interest_paid' =>
                $interestPaid,

            'payment_method' =>
                $request->payment_method,

            'reference_number' =>
                $request->reference_number,

            'remarks' =>
                $request->remarks,

            'received_by' =>
                Auth::id(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Apply New Repayment
        |--------------------------------------------------------------------------
        */

        $loan->increment(
            'amount_paid',
            $amountPaid
        );

        $loan->refresh();


        /*
        |--------------------------------------------------------------------------
        | Loan Status Check
        |--------------------------------------------------------------------------
        */

        if (
            $loan->amount_paid >=
            $loan->repayable_amount
        ) {

            $loan->update([

                'status' => 'Completed',

                'closed_at' => now()

            ]);

        } else {

            $loan->update([

                'status' => 'Active',

                'closed_at' => null

            ]);
        }


        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'You\'ve Updated Loan Repayment Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}
public function viewloanrepaymentfee($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanRepaymentFee::with([
            'loan',
            'client',
            'receiver'
        ])->findOrFail($id);

        return view(
            'in.loans.viewloanrepayment',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}
public function destroyloanrepaymentfee($id)
{
    try {

        $id = Crypt::decrypt($id);

        $repayment =
            LoanRepaymentFee::findOrFail($id);

        $loan =
            Loan::findOrFail(
                $repayment->loan_id
            );

        $loan->decrement(
            'amount_paid',
            $repayment->amount_paid
        );

        $repayment->update([

            'Status' => 'Deleted'

        ]);

        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'Loan Repayment Removed Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function importloanrepaymentsfee(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    try {
        $import = new LoanRepaymentImportFee;

        Excel::import($import, $request->file('file'));

        // ✅ Show row-level errors if any rows failed
        if (!empty($import->errors)) {
            $errorList = implode('<br>', $import->errors);
            Alert::warning(
                'Imported with warnings - ' . Auth()->user()->name,
                $errorList
            );
            return back();
        }

        Alert::success(
            'Success ' . ' ' . Auth()->user()->name,
            'Loan Repayments Imported Successfully'
        );

        return back();

    } catch (\Throwable $th) {
        Alert::error(
            'Sorry! ' . ' ' . Auth()->user()->name,
            $th->getMessage()  // ✅ Now actually surfaces real errors
        );

        return back();
    }
}


public function downloadloanrepaymenttemplatefee()
{
    try {

        return Excel::download(
            new LoanRepaymentTemplateExportFee,
            'loan_repayment_template_fee_' . date('Ymd') . '.xlsx'
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Template download failed: ' . $th->getMessage()
        );

        return back();
    }
}

    // LOAN PAYMENTS
    public function loansrepayments()
    {
        try {

            $data = LoanRepayment::with([
                'loan',
                'client',
                'receiver'
            ])
            ->where('Status', 'Active')
            ->latest()
            ->get();

            $loans = Loan::where('Status', 'Active')
                ->whereIn('status', ['Approved', 'Active'])
                ->get();

            return view(
                'in.loans.loansrepayments',
                compact(
                    'data',
                    'loans'
                )
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function storeloanrepayment(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'payment_date' => 'required|date',
            'amount_paid' => 'required|numeric|min:1',
            'payment_method' => 'nullable|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);
        try {
            $loan = Loan::findOrFail( $request->loan_id );

            /*
            |--------------------------------------------------------------------------
            | Calculate Allocation
            |--------------------------------------------------------------------------
            */

            $amountPaid = (float) $request->amount_paid;
            $principalPaid = min( $loan->principal_due, $amountPaid );
            $interestPaid = min( $loan->interest_due, max( 0, $amountPaid - $principalPaid ) );

            LoanRepayment::create([
                'loan_id' =>$loan->id,
                'client_id' =>$loan->client_id,
                'payment_date' =>$request->payment_date,
                'amount_paid' =>$amountPaid,
                'principal_paid' =>$principalPaid,
                'interest_paid' =>$interestPaid,
                'penalty_paid' =>0,
                'payment_method' =>$request->payment_method,
                'reference_number' =>$request->reference_number,
                'remarks' =>$request->remarks,
                'received_by' =>Auth::id(),
                'User_id' =>Auth::id(),
                'Status' =>'Active',
                'AuditingStatus' =>'Pending',
                'ReportStatus' =>'Pending'
            ]);
            $loan->increment( 'amount_paid', $amountPaid );
            $loan->refresh();

            if (
                $loan->total_amount_paid >= $loan->repayable_amount || $loan->outstanding_balance <= 0
            ) {
                $loan->update([
                    'RepaymentStatus' => 'COMPLETE',
                    'closed_at' => now()
                ]);
            }


            Alert::success( 'Success ' . ' ' . Auth()->user()->name, 'Loan Repayment Registered Successfully' );
            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function editloanrepayment($id)
    {
        try {

            $id = Crypt::decrypt($id);

            $data = LoanRepayment::findOrFail($id);

            return view(
                'in.loans.editloanrepayment',
                compact('data')
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function updateloanrepayment(Request $request, $id)
    {
        $request->validate([
            'payment_date'     => 'required|date',
            'amount_paid'      => 'required|numeric|min:1',
            'payment_method'   => 'nullable|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'remarks'          => 'nullable|string',
        ]);

        try {

            $id = Crypt::decrypt($id); // ✅ Moved inside try/catch

            $repayment = LoanRepayment::findOrFail($id);
            $loan      = Loan::findOrFail($repayment->loan_id);

            /*
            |--------------------------------------------------------------------------
            | Reverse Old Repayment
            |--------------------------------------------------------------------------
            */

            $loan->decrement('amount_paid', $repayment->amount_paid);

            /*
            |--------------------------------------------------------------------------
            | New Allocation
            |--------------------------------------------------------------------------
            */

            $amountPaid    = (float) $request->amount_paid;
            $principalPaid = min($loan->principal_due, $amountPaid);
            $interestPaid  = min($loan->interest_due, max(0, $amountPaid - $principalPaid));

            /*
            |--------------------------------------------------------------------------
            | Update Repayment Record
            |--------------------------------------------------------------------------
            */

            $repayment->update([
                'payment_date'     => $request->payment_date,
                'amount_paid'      => $amountPaid,
                'principal_paid'   => $principalPaid,
                'interest_paid'    => $interestPaid,
                'payment_method'   => $request->payment_method,
                'reference_number' => $request->reference_number,
                'remarks'          => $request->remarks,
                'received_by'      => Auth::id(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Apply New Repayment to Loan
            |--------------------------------------------------------------------------
            */

            $loan->increment('amount_paid', $amountPaid);
            $loan->refresh();

            /*
            |--------------------------------------------------------------------------
            | Loan Status Check — mirrors storeloanrepayment logic exactly
            |--------------------------------------------------------------------------
            */

            if (
                $loan->total_amount_paid >= $loan->repayable_amount || // ✅ uses total_amount_paid
                $loan->outstanding_balance <= 0                         // ✅ added second condition
            ) {
                $loan->update([
                    'RepaymentStatus' => 'COMPLETE', // ✅ correct field name
                    'closed_at'       => now(),
                ]);
            } else {
                $loan->update([
                    'RepaymentStatus' => 'ONGOING',   // ✅ correct field name
                    'closed_at'       => null,
                ]);
            }

            Alert::success(
                'Success ' . ' ' . Auth()->user()->name,
                'You\'ve Updated Loan Repayment Successfully'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function viewloanrepayment($id)
    {
        try {

            $id = Crypt::decrypt($id);

            $data = LoanRepayment::with([
                'loan',
                'client',
                'receiver'
            ])->findOrFail($id);

            return view(
                'in.loans.viewloanrepayment',
                compact('data')
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function destroyloanrepayment($id)
    {
        try {

            $id = Crypt::decrypt($id);

            $repayment =
                LoanRepayment::findOrFail($id);

            $loan =
                Loan::findOrFail(
                    $repayment->loan_id
                );

            $loan->decrement(
                'amount_paid',
                $repayment->amount_paid
            );

            $repayment->update([

                'Status' => 'Deleted'

            ]);

            Alert::success(
                'Success ' . ' ' . Auth()->user()->name,
                'Loan Repayment Removed Successfully'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function importloanrepayments(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $import = new LoanRepaymentImport;

            Excel::import($import, $request->file('file'));

            // ✅ Show row-level errors if any rows failed
            if (!empty($import->errors)) {
                $errorList = implode('<br>', $import->errors);
                Alert::warning(
                    'Imported with warnings - ' . Auth()->user()->name,
                    $errorList
                );
                return back();
            }

            Alert::success(
                'Success ' . ' ' . Auth()->user()->name,
                'Loan Repayments Imported Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                $th->getMessage()  // ✅ Now actually surfaces real errors
            );

            return back();
        }
    }


public function downloadloanrepaymenttemplate()
{
    try {

        return Excel::download(
            new LoanRepaymentTemplateExport,
            'loan_repayment_template_' . date('Ymd') . '.xlsx'
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Template download failed: ' . $th->getMessage()
        );

        return back();
    }
}


// GUARANTOR
public function guarantors()
{
    try {

        $data = Guarantor::where(
            'Status',
            'Active'
        )->get();

        return view(
            'in.loans.guarantors',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function storeguarantor(Request $request)
{
    $request->validate([

        'first_name' => 'required|string|max:100',

        'middle_name' => 'nullable|string|max:100',

        'last_name' => 'nullable|string|max:100',

        'gender' => 'nullable|string|max:20',

        'phone_number' => 'required|string|max:50',

        'alternative_phone' => 'nullable|string|max:50',

        'nida_number' => 'nullable|string|max:100',

        'email' => 'nullable|email|max:255',

        'occupation' => 'nullable|string|max:255',

        'physical_address' => 'nullable|string',

        'relationship_with_client' => 'nullable|string|max:100',

        'remarks' => 'nullable|string',

    ]);

    try {

        $latest = Guarantor::latest()->first();

        $guarantorNumber =
            'GUA-' .
            date('Ymd') .
            '-' .
            str_pad(
                ($latest ? $latest->id + 1 : 1),
                4,
                '0',
                STR_PAD_LEFT
            );

        Guarantor::create([

            'guarantor_number' =>
                $guarantorNumber,

            'first_name' =>
                $request->first_name,

            'middle_name' =>
                $request->middle_name,

            'last_name' =>
                $request->last_name,

            'gender' =>
                $request->gender,

            'phone_number' =>
                $request->phone_number,

            'alternative_phone' =>
                $request->alternative_phone,

            'nida_number' =>
                $request->nida_number,

            'email' =>
                $request->email,

            'occupation' =>
                $request->occupation,

            'physical_address' =>
                $request->physical_address,

            'relationship_with_client' =>
                $request->relationship_with_client,

            'remarks' =>
                $request->remarks,

            'User_id' =>
                Auth::id(),

            'Status' =>
                'Active',

            'AuditingStatus' =>
                'Pending',

            'ReportStatus' =>
                'Pending'

        ]);

        Alert::success(
            'Success '.Auth()->user()->name,
            'Guarantor Registered Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function editguarantor($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = Guarantor::findOrFail($id);

        return view(
            'in.loans.editguarantor',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function updateguarantor(Request $request, $id)
{
    $id = Crypt::decrypt($id);

    $request->validate([

        'first_name' => 'required|string|max:100',

        'middle_name' => 'nullable|string|max:100',

        'last_name' => 'nullable|string|max:100',

        'gender' => 'nullable|string|max:20',

        'phone_number' => 'required|string|max:50',

        'alternative_phone' => 'nullable|string|max:50',

        'nida_number' => 'nullable|string|max:100',

        'email' => 'nullable|email|max:255',

        'occupation' => 'nullable|string|max:255',

        'physical_address' => 'nullable|string',

        'relationship_with_client' => 'nullable|string|max:100',

        'remarks' => 'nullable|string',

    ]);

    try {

        $guarantor = Guarantor::findOrFail($id);

        $guarantor->update([

            'first_name' =>
                $request->first_name,

            'middle_name' =>
                $request->middle_name,

            'last_name' =>
                $request->last_name,

            'gender' =>
                $request->gender,

            'phone_number' =>
                $request->phone_number,

            'alternative_phone' =>
                $request->alternative_phone,

            'nida_number' =>
                $request->nida_number,

            'email' =>
                $request->email,

            'occupation' =>
                $request->occupation,

            'physical_address' =>
                $request->physical_address,

            'relationship_with_client' =>
                $request->relationship_with_client,

            'remarks' =>
                $request->remarks,

        ]);

        Alert::success(
            'Success '.Auth()->user()->name,
            'Guarantor Updated Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function viewguarantor($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = Guarantor::findOrFail($id);

        return view(
            'in.loans.viewguarantor',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function destroyguarantor($id)
{
    try {

        $id = Crypt::decrypt($id);

        $guarantor = Guarantor::findOrFail($id);

        $guarantor->update([

            'Status' => 'Deleted'

        ]);

        Alert::success(
            'Success '.Auth()->user()->name,
            'Guarantor Removed Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}


public function loanguarantors()
{
    try {

        $data = LoanGuarantor::with([
            'loan',
            'client',
            'guarantor'
        ])
        ->where('Status', 'Active')
        ->get();

        $loans = Loan::where('Status', 'Active')->get();

        $guarantors = Guarantor::where(
            'Status',
            'Active'
        )->get();

        return view(
            'in.loans.loanguarantors',
            compact(
                'data',
                'loans',
                'guarantors'
            )
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function storeloanguarantor(Request $request)
{
    $request->validate([

        'loan_id' => 'required|exists:loans,id',

        'guarantor_id' => 'required|exists:guarantors,id',

        'guarantee_amount' => 'required|numeric|min:0',

        'relationship_type' => 'nullable|string|max:100',

        'remarks' => 'nullable|string',

    ]);

    try {

        $loan = Loan::findOrFail(
            $request->loan_id
        );
        // $exists = LoanGuarantor::where('loan_id', $request->loan_id)
        //     ->where('guarantor_id', $request->guarantor_id)
        //     ->where('Status', 'Active')
        //     ->exists();

        // if ($exists) {

        //     Alert::warning(
        //         'Warning '.Auth()->user()->name,
        //         'This guarantor is already assigned to the selected loan.'
        //     );

        //     return back();
        // }
        LoanGuarantor::create([

            'loan_id' =>
                $loan->id,

            'client_id' =>
                $loan->client_id,

            'guarantor_id' =>
                $request->guarantor_id,

            'guarantee_amount' =>
                $request->guarantee_amount,

            'relationship_type' =>
                $request->relationship_type,

            'remarks' =>
                $request->remarks,

            'User_id' =>
                Auth::id(),

            'Status' =>
                'Active',

            'AuditingStatus' =>
                'Pending',

            'ReportStatus' =>
                'Pending'

        ]);

        Alert::success(
            'Success '.Auth()->user()->name,
            'Loan Guarantor Registered Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function editloanguarantor($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanGuarantor::findOrFail($id);

        $loans = Loan::where(
            'Status',
            'Active'
        )->get();

        $guarantors = Guarantor::where(
            'Status',
            'Active'
        )->get();

        return view(
            'in.loans.editloanguarantor',
            compact(
                'data',
                'loans',
                'guarantors'
            )
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function updateloanguarantor(Request $request, $id)
{
    $id = Crypt::decrypt($id);

    $request->validate([

        'loan_id' => 'required|exists:loans,id',

        'guarantor_id' => 'required|exists:guarantors,id',

        'guarantee_amount' => 'required|numeric|min:0',

        'relationship_type' => 'nullable|string|max:100',

        'remarks' => 'nullable|string',

    ]);

    try {

        $loanGuarantor = LoanGuarantor::findOrFail($id);

        $loan = Loan::findOrFail(
            $request->loan_id
        );

        $loanGuarantor->update([

            'loan_id' =>
                $loan->id,

            'client_id' =>
                $loan->client_id,

            'guarantor_id' =>
                $request->guarantor_id,

            'guarantee_amount' =>
                $request->guarantee_amount,

            'relationship_type' =>
                $request->relationship_type,

            'remarks' =>
                $request->remarks

        ]);

        Alert::success(
            'Success '.Auth()->user()->name,
            'Loan Guarantor Updated Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function viewloanguarantor($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanGuarantor::with([
            'loan',
            'client',
            'guarantor'
        ])->findOrFail($id);

        return view(
            'in.loans.viewloanguarantor',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}

public function destroyloanguarantor($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanGuarantor::findOrFail($id);

        $data->update([

            'Status' => 'Deleted'

        ]);

        Alert::success(
            'Success '.Auth()->user()->name,
            'Loan Guarantor Removed Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! '.Auth()->user()->name,
            'Technical error exists, please contact Technichal for support Tel:+255657856790'
        );

        return back();
    }
}


public function loanpenalties()
{
    try {

        $data = LoanPenalty::with([
            'loan',
            'client',
            'penaltyCategory'
        ])
        ->where('Status', 'Active')
        ->latest()
        ->get();

        $loans = Loan::where(
            'Status',
            'Active'
        )->get();

        $penalties = LoanPenaltyCategory::where(
            'Status',
            'Active'
        )->get();

        return view(
            'in.loans.loanpenalties',
            compact(
                'data',
                'loans',
                'penalties'
            )
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}

public function unpaidloanpenalties()
{
    try {

        $data = LoanPenalty::with([
            'loan',
            'client',
            'penaltyCategory'
        ])
        ->where('Status', 'Active')
        ->where('payment_status', 'NOT PAID')
        ->latest()
        ->get();

        $loans = Loan::where(
            'Status',
            'Active'
        )->get();

        $penalties = LoanPenaltyCategory::where(
            'Status',
            'Active'
        )->get();

        return view(
            'in.loans.unpaidloanpenalties',
            compact(
                'data',
                'loans',
                'penalties'
            )
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}

public function storeloanpenalty(Request $request)
{
    $request->validate([

        'loan_id' => 'required|exists:loans,id',

        'penalty_id' => 'required|exists:loan_penalties_categories,id',

        'penalty_date' => 'required|date',

        'overdue_days' => 'required|integer|min:0',

        'penalty_rate' => 'required|numeric|min:0',

        'remarks' => 'nullable|string',

    ]);

    try {

            $loan = Loan::findOrFail(
                $request->loan_id
            );

            LoanPenalty::create([

                'loan_id' =>
                    $loan->id,

                'client_id' =>
                    $loan->client_id,

                'penalty_id' =>
                    $request->penalty_id,

                'penalty_date' =>
                    $request->penalty_date,

                'overdue_days' =>
                    $request->overdue_days,

                'penalty_rate' =>
                    $request->penalty_rate,

                'penalty_amount' =>
                    $request->penalty_rate,

                'payment_status' =>
                    'NOT PAID',

                'remarks' =>
                    $request->remarks,

                'User_id' =>
                    Auth::id(),

                'Status' =>
                    'Active',

                'AuditingStatus' =>
                    'Pending',

                'ReportStatus' =>
                    'Pending'

            ]);
            if (
                $loan->total_amount_paid >= $loan->repayable_amount || $loan->outstanding_balance <= 0
            ) {
                $loan->update([
                    'RepaymentStatus' => 'COMPLETE',
                    'closed_at' => now()
                ]);
            }
        Alert::success(
            'Success ' . Auth()->user()->name,
            'Loan Penalty Registered Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}

public function editloanpenalty($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanPenalty::findOrFail($id);

        $loans = Loan::where(
            'Status',
            'Active'
        )->get();

        $penalties = LoanPenaltyCategory::where(
            'Status',
            'Active'
        )->get();

        return view(
            'in.loans.editloanpenalty',
            compact(
                'data',
                'loans',
                'penalties'
            )
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}

public function updateloanpenalty(Request $request, $id)
{
    $id = Crypt::decrypt($id);

    $request->validate([

        'loan_id' => 'required|exists:loans,id',

        'penalty_id' => 'required|exists:loan_penalties_categories,id',

        'penalty_date' => 'required|date',

        'overdue_days' => 'required|integer|min:0',

        'penalty_rate' => 'required|numeric|min:0',

        'remarks' => 'nullable|string',

    ]);

    try {

        $penalty = LoanPenalty::findOrFail($id);

        $loan = Loan::findOrFail(
            $request->loan_id
        );

        $penaltyAmount =
            ($loan->client_payable_frequency *
            $request->penalty_rate / 100)
            *
            $request->overdue_days;

        $penalty->update([

            'loan_id' =>
                $loan->id,

            'client_id' =>
                $loan->client_id,

            'penalty_id' =>
                $request->penalty_id,

            'penalty_date' =>
                $request->penalty_date,

            'overdue_days' =>
                $request->overdue_days,

            'penalty_rate' =>
                $request->penalty_rate,

            'penalty_amount' =>
                $penaltyAmount,

            'remarks' =>
                $request->remarks

        ]);

        Alert::success(
            'Success ' . Auth()->user()->name,
            'Loan Penalty Updated Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}

public function viewloanpenalty($id)
{
    try {

        $id = Crypt::decrypt($id);

        $data = LoanPenalty::with([
            'loan',
            'client',
            'penaltyCategory'
        ])->findOrFail($id);

        return view(
            'in.loans.viewloanpenalty',
            compact('data')
        );

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}

public function destroyloanpenalty($id)
{
    try {

        $id = Crypt::decrypt($id);

        $penalty = LoanPenalty::findOrFail($id);

        $penalty->update([

            'Status' => 'Deleted'

        ]);

        Alert::success(
            'Success ' . Auth()->user()->name,
            'Loan Penalty Removed Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}
public function payloanpenalty($id)
{
    try {

        $id = Crypt::decrypt($id);

        $penalty = LoanPenalty::findOrFail($id);

        if ($penalty->payment_status == 'PAID') {

            Alert::warning(
                'Warning ' . Auth()->user()->name,
                'Penalty already marked as paid'
            );

            return back();
        }

        $amountPaid = $penalty->penalty_amount;

        $loan = Loan::findOrFail(
            $penalty->loan_id
        );

        $loan->increment(
            'penalty_fee_paid',
            $amountPaid
        );

        $penalty->update([

            'payment_status' => 'PAID',

            'paid_at' => now()

        ]);

        Alert::success(
            'Success ' . Auth()->user()->name,
            'Penalty Marked As Paid Successfully'
        );

        return back();

    } catch (\Throwable $th) {

        Alert::error(
            'Sorry! ' . Auth()->user()->name,
            'Technical error exists, please contact Technical for support Tel:+255657856790'
        );

        return back();
    }
}

    // LOAN APPROVAL
    public function approveloansinformations()
    {
        try {

            $data = Loan::with([
                'client',
                'loanCategory',
                'group',
                'groupCenter'
            ])
            ->where('Status', 'Active')
            ->where('ApprovalStatus', 'Pending')
            ->latest()
            ->get();
            $clients = Client::where('Status', 'Active')->get();
            $loanCategories = LoanCategory::where('Status', 'Active')->get();
            $groups = Group::where('Status', 'Active')->get();
            $groupCenters = GroupCenter::where('Status', 'Active')->get();
            return view( 'in.loans.approve.approveloansinformations', compact('data', 'clients', 'loanCategories', 'groups', 'groupCenters'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function viewapproveloansinformations($id)
    {
        try {

            $id = Crypt::decrypt($id);

            $data = Loan::with([
                        'client',
                        'loanCategory',
                        'group',
                        'groupCenter'
                    ])->where('ApprovalStatus', 'Pending')->findOrFail($id);
            return view(
                'in.loans.approve.viewapproveloansinformations',
                compact('data')
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }
    public function rejectedloansinformations()
    {
        try {

            $data = Loan::with([
                'client',
                'loanCategory',
                'group',
                'groupCenter'
            ])
            ->where('Status', 'Active')
            ->where('ApprovalStatus', 'Rejected')
            ->latest()
            ->get();
            $clients = Client::where('Status', 'Active')->get();
            $loanCategories = LoanCategory::where('Status', 'Active')->get();
            $groups = Group::where('Status', 'Active')->get();
            $groupCenters = GroupCenter::where('Status', 'Active')->get();
            return view( 'in.loans.approve.rejectedloansinformations', compact('data', 'clients', 'loanCategories', 'groups', 'groupCenters'));
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function approveloansinfo(Request $request, $encryptedId)
    {
        try {
            // 1. Safely decrypt the incoming loan ID string
            $loanId = Crypt::decrypt($encryptedId);
        } catch (DecryptException $e) {
            Alert::error('Error!', 'Invalid or corrupted loan signature key detected.');
            return back();
        }

        // 2. Base Validation common to both actions
        $request->validate([
            'action_type' => 'required|in:approve,reject',
            'remarks'     => 'nullable|string|max:255',
        ]);

        $loan = Loan::findOrFail($loanId);
        $category = LoanCategory::findOrFail($loan->loan_category_id);
        $loanNumber = $loan->loan_number;

        // 3. Early state guard check: Ensure the loan isn't already processed
        if ($loan->ApprovalStatus !== 'Pending') {
            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Loan ' . $loanNumber . ' can’t be processed because it is either Refunded, Closed, or Already Approved/Rejected.'
            );
            return back();
        }

        try {
            // ==========================================
            // BRANCH PATHWAY A: REJECTION
            // ==========================================
            if ($request->input('action_type') === 'reject') {
                $request->validate([
                    'RejectReasons' => 'required|string|max:500',
                ]);

                $loan->update([
                    'ApprovalStatus'  => 'Rejected',
                    'ApprovalRemarks' => $request->remarks, // Main notes box
                    'RejectReasons'   => $request->RejectReasons, // Explicit field requirement
                    'approved_by'     => Auth::id(), // Records who logged the review action
                    'closed_at'       => now(),
                ]);

                Alert::success(
                    'Done ' . Auth()->user()->name,
                    'Loan ' . $loanNumber . ' has been officially rejected.'
                );
                return redirect()->route('approveloansinformations');
            }

            // ==========================================
            // BRANCH PATHWAY B: APPROVAL
            // ==========================================
            $request->validate([
                'amount_disbursed' => 'required|numeric|min:0',
            ]);

            // Specific rule constraint for initial collection verification
            if ($loan->amount_paid < 3 * $category->principal_due) {
                Alert::error(
                    'Sorry! ' . Auth()->user()->name,
                    'Loan ' . $loanNumber . ' can’t be Approved because the client has not started paying.'
                );
                return back();
            }

            $loan->update([
                'amount_disbursed'  => $request->amount_disbursed,
                'insurance_fee'     => $category->insurance_fee,
                'officer_visit_fee' => $category->officer_visit_fee,
                'interest_rate'     => $category->interest_rate,
                'interest_amount'   => $category->interest_amount,
                'principal_due'     => $category->principal_due,
                'interest_due'      => $category->interest_due,
                'total_days_due'    => $category->total_days_due,
                'max_term_days'     => $category->max_term_days,
                'max_term_months'   => $category->max_term_months,
                'ApprovalRemarks'   => $request->remarks,
                'ApprovalStatus'    => 'Approved',
                'approved_by'       => Auth::id(),
                'disbursement_date' => now(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Loan ' . $loanNumber . ' approved and repayment schedule generated successfully.'
            );
            return redirect()->route('approveloansinformations');

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function refundloansinfo(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $loan = Loan::findOrFail($id);
        $loanNumber = $loan->loan_number;
        try {

            if ($loan->RefundStatus !== 'Not Refunded') {
                Alert::error(
                    'Sorry! ' . Auth()->user()->name,
                    'Loan ' . $loanNumber . 'can’t be Refunded because the Loan is either Refunded or Closed.'
                );
                return back();
            }

            $loan->update([
                'RefundStatus' => 'Refunded',
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Loan ' . $loanNumber  . 'have been Refunded  successfully.'
            );
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function unrefundloansinfo(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $loan = Loan::findOrFail($id);
        $loanNumber = $loan->loan_number;
        try {

            if ($loan->RefundStatus !== 'Refunded') {
                Alert::error(
                    'Sorry! ' . Auth()->user()->name,
                    'Loan ' . $loanNumber . 'can’t be Resset to Refund because the Loan is either Refunded or Closed.'
                );
                return back();
            }

            $loan->update([
                'RefundStatus' => 'Not Refunded',
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Loan ' . $loanNumber  . 'have been Reseted for the Refund successfully.'
            );
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    // LOAN REFUNDING
    public function refundedloansinformations()
    {
        try {

            $data = LoanRefund::with([
                    'loan',
                    'client',
                    'group',
                    'groupCenter',
                    'approver',
                    'user'
                ])
                ->where('Status', 'Active')
                ->where('ApprovalStatus', 'Approved')
                ->latest()
                ->get();

            return view(
                'in.loans.refund.refundedloansinformations',
                compact('data')
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! '.Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function refundthisloansinformations($id)
    {
        try {

            $loan = Loan::findOrFail(decrypt($id));

            // Check whether a refund request already exists
            $exists = LoanRefund::where('loan_id', $loan->id)
                        ->where('Status', 'Active')
                        ->exists();

            if ($exists) {

                Alert::warning(
                    'Warning',
                    'A refund request already exists for this loan.'
                );

                return back();
            }

            LoanRefund::create([

                'loan_id' => $loan->id,
                'client_id' => $loan->client_id,
                'group_center_id' => $loan->group_center_id,
                'group_id' => $loan->group_id,

                'refund_number' => 'ARB-RFD-'.date('YmdHis'),

                'refund_date' => now(),

                // Request only
                'requested_refund' => 0,
                'approved_refund' => 0,
                'refunded_amount' => 0,

                'membership_fee_refund' => 0,
                'insurance_fee_refund' => 0,
                'officer_visit_fee_refund' => 0,
                'other_fee_refund' => 0,
                'penalty_fee_refund' => 0,
                'preclosure_fee_refund' => 0,

                'total_refund' => 0,

                'refund_reason' => 'Loan cancelled before disbursement.',

                'ApprovalStatus' => 'Pending',

                'created_by' => auth()->id(),
                'User_id' => auth()->id(),

                'Status' => 'Active',
                'AuditingStatus' => 'Pending',
                'ReportStatus' => 'Pending',

            ]);

            Alert::success(
                'Success',
                'Loan refund request submitted successfully.'
            );

            return redirect()->route('pendingloanrefund');

        } catch (\Throwable $th) {

            Alert::error(
                'Error',
                $th->getMessage()
            );

            return back();
        }
    }

    public function pendingloanrefund()
    {
        try {

            $data = LoanRefund::with([
                    'loan',
                    'client',
                    'group',
                    'groupCenter'
                ])
                ->where('Status', 'Active')
                ->where('ApprovalStatus', 'Pending')
                ->latest()
                ->get();

            return view(
                'in.loans.refund.pendingloanrefund',
                compact('data')
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry!',
                'Technical error exists.'
            );

            return back();
        }
    }

    public function viewloanrefund($id)
    {
        try {

            $data = LoanRefund::with([
                    'loan',
                    'client',
                    'group',
                    'groupCenter'
                ])
                ->findOrFail(decrypt($id));

            return view(
                'in.loans.refund.viewloanrefund',
                compact('data')
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry!',
                'Technical error exists.'
            );

            return back();
        }
    }

    public function approveloanrefund($id)
    {
        try {

            DB::beginTransaction();

            $refund = LoanRefund::findOrFail(decrypt($id));

            if ($refund->ApprovalStatus == 'Approved') {

                Alert::warning(
                    'Warning',
                    'This refund has already been approved.'
                );

                return back();
            }

            $loan = Loan::findOrFail($refund->loan_id);

            // Copy paid amounts
            $refund->refunded_amount = $loan->amount_paid;

            $refund->membership_fee_refund = $loan->membership_fee_paid;

            $refund->insurance_fee_refund = $loan->insurance_fee_paid;

            $refund->officer_visit_fee_refund = $loan->officer_visit_fee_paid;

            $refund->other_fee_refund = $loan->other_fee_paid;

            $refund->penalty_fee_refund = $loan->penalty_fee_paid;

            $refund->preclosure_fee_refund = $loan->preclosure_fee_paid;

            $refund->total_refund =
                $refund->refunded_amount
                + $refund->membership_fee_refund
                + $refund->insurance_fee_refund
                + $refund->officer_visit_fee_refund
                + $refund->other_fee_refund
                + $refund->penalty_fee_refund
                + $refund->preclosure_fee_refund;

            $refund->approved_refund = $refund->total_refund;

            $refund->ApprovalStatus = 'Approved';

            $refund->approved_by = auth()->id();

            $refund->save();

            // Update loan
            $loan->RefundStatus = 'Refunded';

            $loan->LoanStatus = 'Refunded';

            $loan->CloseStatus = 'Closed';

            $loan->amount_with_refund = $refund->total_refund;

            $loan->refunded_at = now();

            $loan->refunded_by = auth()->id();

            $loan->save();

            DB::commit();

            Alert::success(
                'Success',
                'Loan refund approved successfully.'
            );

            return redirect()->route('pendingloanrefund');

        } catch (\Throwable $th) {

            DB::rollBack();

            Alert::error(
                'Error',
                $th->getMessage()
            );

            return back();
        }
    }

    public function rejectloanrefund($id)
    {
        try {

            $refund = LoanRefund::findOrFail(decrypt($id));

            $refund->ApprovalStatus = 'Rejected';

            $refund->approved_by = auth()->id();

            $refund->save();

            Alert::success(
                'Success',
                'Loan refund rejected successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Error',
                $th->getMessage()
            );

            return back();
        }
    }


    // LOAN DOUBLING
    public function doubledloansinformations()
    {
        try {
            $data = LoanTopup::where('Status', 'Active')->get();
            $loan = Loan::with([
                    'client',
                    'loanCategory',
                    'group',
                    'groupCenter'
                ])
                ->where('Status', 'Active')
                ->where('ApprovalStatus', 'Approved')
                ->where('CloseStatus', 'Not Closed')
                ->where('RefundStatus', 'Not Refunded')
                ->where('RepaymentStatus', 'ONGOING')
                ->latest()
                ->get()
                ->filter(function ($loan) {

                    $remaining = $loan->total_repayable - $loan->total_amount_paid;

                    return $remaining <= ($loan->principal_due * 5);
                });

                $clients = Client::where('Status', 'Active')->get();
                $loanCategories = LoanCategory::where('Status', 'Active')->get();
                $groups = Group::where('Status', 'Active')->get();
                $groupCenters = GroupCenter::where('Status', 'Active')->get();
                return view(
                    'in.loans.double.doubledloansinformations',
                    compact(
                        'data',
                        'loan',
                        'clients',
                        'loanCategories',
                        'groups',
                        'groupCenters'
                    )
                );
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function storeloandoublinginformations(Request $request)
    {
        $validated = $request->validate([
            'old_loan_id'      => 'required|exists:loans,id',
            'loan_category_id' => 'required|integer|exists:loan_categories,id',
            'membership_fee'   => 'nullable|numeric|min:0',
            'remarks'          => 'nullable|string|max:1000',
            'topup_reason'     => 'required|string|max:1000',
            'topup_date'       => 'required|date',
            'application_date' => 'nullable|date',
        ]);

        try {

            DB::beginTransaction();

            $loan = Loan::with([
                'client',
                'repaymentSchedules',
                'penalties'
            ])->findOrFail($request->old_loan_id);

            $approved_amount = $loan->amount_disbursed;

            /*
            |--------------------------------------------------------------------------
            | Loan Must Be Active
            |--------------------------------------------------------------------------
            */

            if ($loan->Status != 'Active') {
                Alert::warning('Warning', 'Only active loans can be topped up.');
                return back();
            }

            /*
            |--------------------------------------------------------------------------
            | Outstanding Calculations
            |--------------------------------------------------------------------------
            */

            $principalOutstanding = max(
                0,
                $loan->principal_due - ($loan->principal_paid ?? 0) // ✅ principal_paid not amount_paid
            );

            $penaltyOutstanding = max(
                0,
                $loan->penalties->sum(function ($item) {
                    return ($item->amount ?? 0) - ($item->amount_paid ?? 0);
                })
            );

            $otherOutstanding = max(
                0,
                ($loan->membership_fee        - $loan->membership_fee_paid)
                + ($loan->insurance_fee       - $loan->insurance_fee_paid)
                + ($loan->officer_visit_fee   - $loan->officer_visit_fee_paid)
                + ($loan->other_fee           - $loan->other_fee_paid)
            );

            $totalOutstanding =
                $principalOutstanding
                + $penaltyOutstanding
                + $otherOutstanding;

            $topupFee       = 5000;
            $totalDeduction = $totalOutstanding + $topupFee;
            /*
            |--------------------------------------------------------------------------
            | Create Loan Topup Record
            |--------------------------------------------------------------------------
            */

            $loanTopup = LoanTopup::create([
                'old_loan_id'            => $loan->id,
                'client_id'              => $loan->client_id,
                'group_id'               => $loan->group_id,
                'group_center_id'        => $loan->group_center_id,
                'requested_amount'       => $loan->amount_requested,
                'approved_amount'        => $approved_amount,
                'amount_disbursed'       => 0,
                'outstanding_principal'  => $principalOutstanding,
                'outstanding_interest'   => 0,   // ✅ now defined
                'outstanding_penalty'    => $penaltyOutstanding,
                'outstanding_other_fee'  => $otherOutstanding,
                'total_outstanding'      => $totalOutstanding,
                'topup_fee'              => $topupFee,
                'total_deductions'       => $totalDeduction,
                'net_disbursed'          => $approved_amount,
                'remaining_installments' => $loan->outstanding_balance, // ✅ actual count, not money
                'topup_reason'           => $request->topup_reason,
                'remarks'                => $request->remarks,
                'topup_date'             => $request->topup_date,
            ]);



            /*
            |--------------------------------------------------------------------------
            | Create New Loan (if category provided)
            |--------------------------------------------------------------------------
            */

            if ($request->filled('loan_category_id')) { // ✅ filled() not has()

                $loanCategory = LoanCategory::findOrFail($request->loan_category_id);

                // ✅ client_id comes directly from loan, not from $validated array
                $client = Client::findOrFail($loan->client_id);

                $latestLoan = Loan::latest()->first();
                $loanNumber = 'ArB-LN-'
                    . $client->client->LastName . '-'
                    . date('Ymd') . '-'
                    . str_pad(($latestLoan ? $latestLoan->id + 1 : 1), 4, '0', STR_PAD_LEFT);

                $newLoan = Loan::create([
                    'loan_number'              => $loanNumber,
                    'group_center_id'          => $client->group_center_id,
                    'group_id'                 => $client->group_id,
                    'collection_officer_id'    => $loan->credit_officer_id,
                    'client_id'                => $loan->client_id,
                    'membership_fee'           => $request->membership_fee,
                    'loan_category_id'         => $request->loan_category_id,
                    'amount_requested'         => $loanCategory->amount_disbursed,
                    'client_payable_frequency' => $loanCategory->principal_due,
                    'application_date'         => $request->application_date,
                    'is_active'                => true,
                    'ApprovalStatus'           => 'Pending',
                    'currency'                 => $loanCategory->currency,
                    'created_by'               => Auth::id(),
                    'updated_by'               => Auth::id(),
                    'User_id'                  => Auth::id(),
                ]);
    
            /*
            |--------------------------------------------------------------------------
            | Close Old Loan
            |--------------------------------------------------------------------------
            */
                $loan->update([
                    'amount_with_preclosure' => $totalOutstanding,
                    'closure_reason'         => $request->topup_reason,
                    'closed_at'              => now(),
                    'RepaymentStatus'        => 'COMPLETE', // ✅ removed duplicate key
                    'CloseStatus'        => 'Closed', // ✅ removed duplicate key
                ]);

            /*
            |--------------------------------------------------------------------------
            | Apply New Loan id to loanTopup
            |--------------------------------------------------------------------------
            */
                $loanTopup->update([
                    'new_loan_id' => $newLoan->id, // ✅ ->id not the model object
                ]);
            }

            DB::commit();

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Loan Top-up Request Submitted Successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            DB::rollBack();

            Alert::error(
                'Sorry ' . Auth()->user()->name,
                'Technical error: ' . $th->getMessage()
            );
            return back();
        }
    }

    public function viewloandoublinginformations(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $loan = LoanTopup::findOrFail($id);
        $loanNumber = $loan->loan_number;
        try {
            return view( 'in.loans.double.viewloandoublinginformations', compact('loan', 'loanNumber'));
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function doublethisloansinformations($id)
    {
        // try {

        $id = Crypt::decrypt($id);
        $data = Loan::findOrFail($id);
            return view(
                'in.loans.double.doublethisloansinformations',
                compact('data')
            );

        // } catch (\Throwable $th) {

        //     Alert::error(
        //         'Sorry! ' . ' ' . Auth()->user()->name,
        //         'Technical error exists, please contact Technichal for support Tel:+255657856790'
        //     );

        //     return back();
        // }
    }
    public function viewapprovedoubledloansinformations($id)
    {
        try {

            $id = Crypt::decrypt($id);

            $data = Loan::with([
                        'client',
                        'loanCategory',
                        'group',
                        'groupCenter'
                    ])->where('ApprovalStatus', 'Pending')->findOrFail($id);
            return view(
                'in.loans.double.viewapprovedoubledloansinformations',
                compact('data')
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();
        }
    }

    public function rejectedloansinformationsold()
    {
        try {

            $data = Loan::with([
                'client',
                'loanCategory',
                'group',
                'groupCenter'
            ])
            ->where('Status', 'Active')
            ->where('ApprovalStatus', 'Rejected')
            ->latest()
            ->get();
            $clients = Client::where('Status', 'Active')->get();
            $loanCategories = LoanCategory::where('Status', 'Active')->get();
            $groups = Group::where('Status', 'Active')->get();
            $groupCenters = GroupCenter::where('Status', 'Active')->get();
            return view( 'in.loans.approve.rejectedloansinformations', compact('data', 'clients', 'loanCategories', 'groups', 'groupCenters'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function approvedoubledloansinformations(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $loan = Loan::findOrFail($id);
        $category = LoanCategory::findOrFail($loan->loan_category_id);
        $loanNumber = $loan->loan_number;
        try {
            if ($loan->amount_paid < 2 * $category->principal_due) {
                Alert::error(
                    'Sorry! ' . Auth()->user()->name,
                    'Loan ' . $loanNumber . 'can’t be Approved because the client has not started paying.'
                );
                return back();
            }

            if ($loan->ApprovalStatus !== 'Pending') {
                Alert::error(
                    'Sorry! ' . Auth()->user()->name,
                    'Loan ' . $loanNumber . 'can’t be Approved because the Loan is either Refunded or Closed or Already Approved.'
                );
                return back();
            }

            $membershipFee = $loan->membership_fee;

            // Step 1: Approve the loan
            $loan->update([
                'amount_disbursed'       => $category->amount_disbursed ?? $loan->amount_requested,
                'insurance_fee' => $category->insurance_fee,
                'officer_visit_fee' => $category->officer_visit_fee,
                'interest_rate' => $category->interest_rate,
                'interest_amount' => $category->interest_amount,
                'principal_due' => $category->principal_due,
                'interest_due' => $category->interest_due,
                'total_days_due' => $category->total_days_due,
                'max_term_days' => $category->max_term_days,
                'max_term_months' => $category->max_term_months,
                'ApprovalStatus'         => 'Approved',
                'approved_by'            => Auth::id(),
                'disbursement_date'      => now(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Loan ' . $loanNumber  . 'approved and repayment schedule generated successfully.'
            );
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function closeloansinfo(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $loan = Loan::findOrFail($id);
        $loanNumber = $loan->loan_number;
        try {

            if ($loan->RefundStatus !== 'Not Refunded') {
                Alert::error(
                    'Sorry! ' . Auth()->user()->name,
                    'Loan ' . $loanNumber . 'can’t be Refunded because the Loan is either Refunded or Closed.'
                );
                return back();
            }

            $loan->update([
                'RefundStatus' => 'Refunded',
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Loan ' . $loanNumber  . 'have been Refunded  successfully.'
            );
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }
    public function closedloansinformations()
    {
        try {

            $data = Loan::with([
                'client',
                'loanCategory',
                'group',
                'groupCenter'
            ])
            ->where('Status', 'Active')
            ->where('CloseStatus', 'Closed')
            ->latest()
            ->get();
            $clients = Client::where('Status', 'Active')->get();
            $loanCategories = LoanCategory::where('Status', 'Active')->get();
            $groups = Group::where('Status', 'Active')->get();
            $groupCenters = GroupCenter::where('Status', 'Active')->get();
            return view( 'in.loans.closedloansinformations', compact('data', 'clients', 'loanCategories', 'groups', 'groupCenters'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }
    public function printloaninformationformtemplate()
    {
        try {

            $data = Loan::with([
                'client',
                'loanCategory',
                'group',
                'groupCenter'
            ])
            ->where('Status', 'Active')
            ->where('CloseStatus', 'Closed')
            ->latest()
            ->get();
            $clients = Client::where('Status', 'Active')->get();
            $loanCategories = LoanCategory::where('Status', 'Active')->get();
            $groups = Group::where('Status', 'Active')->get();
            $groupCenters = GroupCenter::where('Status', 'Active')->get();
            return view( 'in.loans.printloaninformationformtemplate', compact('data', 'clients', 'loanCategories', 'groups', 'groupCenters'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . ' ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }


}