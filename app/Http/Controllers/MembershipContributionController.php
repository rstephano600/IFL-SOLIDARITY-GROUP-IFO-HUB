<?php

namespace App\Http\Controllers;

use App\Models\MembershipFeeSchedule;
use App\Models\MembershipFeePayment;
use App\Models\SocialContributionSchedule;
use App\Models\SocialContribution;
use App\Models\Member;;
use App\Models\Company;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class MembershipContributionController extends Controller
{

    public function membershipfeeschedules()
    {
        try {

            $feeSchedules = MembershipFeeSchedule::with([
                    'company',
                    'branch',
                    'user'
                ])
                ->where('Status', 'Active')
                ->latest()
                ->get();
            $companies = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches  = Branch::where('Status', 'Active')->orderBy('branch_name')->get();
            return view('in.members.membershipfeeschedules.membershipfeeschedules', compact('feeSchedules', 'companies', 'branches'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . ' ' . auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storemembershipfeeschedules(Request $request)
    {
        $request->validate([
            'FeeAmount'     => 'required|numeric|min:0',
            'EffectiveFrom' => 'required|date',
            'EffectiveTo'   => 'nullable|date|after_or_equal:EffectiveFrom',
            'Description'   => 'nullable|string',
            'company_id'    => 'nullable|exists:companies,id',
            'branch_id'     => 'nullable|exists:branchies,id',
        ]);

        try {

            $scheduleRefNo = $this->generateScheduleRefNo();

            MembershipFeeSchedule::create([
                'ScheduleRefNo'  => $scheduleRefNo,
                'FeeAmount'      => $request->FeeAmount ?? 0.00,
                'EffectiveFrom'  => $request->EffectiveFrom,
                'EffectiveTo'    => $request->EffectiveTo,
                'Description'    => $request->Description,
                'company_id'     => $request->company_id,
                'branch_id'      => $request->branch_id,
                'User_id'        => auth()->id(),
                'Status'         => 'Active',
                'AuditingStatus' => 'Pending',
                'ReportStatus'   => 'Pending',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name, 'Operation completed successfully.'
            );
            return redirect()->back();

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function viewmembershipfeeschedules($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $feeSchedule = MembershipFeeSchedule::with([
                    'payments',
                    'company',
                    'branch',
                    'user'
                ])
                ->findOrFail($id);

            return view('in.members.membershipfeeschedules.viewmembershipfeeschedules', compact('feeSchedule'));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function editmembershipfeeschedules($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $feeSchedule = MembershipFeeSchedule::with([
                    'company',
                    'branch',
                    'user'
                ])
                ->findOrFail($id);

            $companies = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches  = Branch::where('Status', 'Active')->orderBy('branch_name')->get();

            return view('in.members.membershipfeeschedules.editmembershipfeeschedules', compact(
                'feeSchedule',
                'companies',
                'branches'
            ));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function updatemembershipfeeschedules(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'FeeAmount'     => 'required|numeric|min:0',
            'EffectiveFrom' => 'required|date',
            'EffectiveTo'   => 'nullable|date|after_or_equal:EffectiveFrom',
            'Description'   => 'nullable|string',
            'company_id'    => 'nullable|exists:companies,id',
            'branch_id'     => 'nullable|exists:branchies,id',
        ]);

        try {

            $feeSchedule = MembershipFeeSchedule::findOrFail($id);

            $feeSchedule->update([
                'FeeAmount'     => $request->FeeAmount ?? $feeSchedule->FeeAmount,
                'EffectiveFrom' => $request->EffectiveFrom,
                'EffectiveTo'   => $request->EffectiveTo,
                'Description'   => $request->Description,
                'company_id'    => $request->company_id,
                'branch_id'     => $request->branch_id,
            ]);

            Alert::success('Success', 'Membership fee schedule updated successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back()->withInput();

        }
    }

    public function deletemembershipfeeschedules($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $feeSchedule = MembershipFeeSchedule::findOrFail($id);

            $feeSchedule->update([
                'Status' => 'Deleted',
            ]);

            Alert::success('Success', 'Membership fee schedule deleted successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    public function restoredmembershipfeeschedules($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $feeSchedule = MembershipFeeSchedule::findOrFail($id);

            $feeSchedule->update([
                'Status' => 'Active',
            ]);

            Alert::success('Success', 'Membership fee schedule restored successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    /**
     * Helper function to generate sequential ScheduleRefNo in format: IFLSG/MFS/0001
     */
    private function generateScheduleRefNo()
    {
        $prefix = 'IFLSG/MFS/';

        $latestSchedule = MembershipFeeSchedule::where('ScheduleRefNo', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestSchedule) {
            return $prefix . '0001';
        }

        $lastNumber = (int) substr($latestSchedule->ScheduleRefNo, strlen($prefix));
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $nextNumber;
    }



    // Membership Fee Payment;

    public function membershipfeepaymentshub()
    {
        try {
            return view('in.members.membershipfeepayments.membershipfeepaymentshub');

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . ' ' . auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function membershipfeepayments()
    {
        try {

            $feePayments = MembershipFeePayment::with([
                    'member',
                    'feeSchedule',
                    'company',
                    'branch',
                    'user'
                ])
                ->where('Status', 'Active')
                ->latest()
                ->get();
            $members       = Member::where('Status', 'Active')->orderBy('member_code')->get();
            $feeSchedules  = MembershipFeeSchedule::where('Status', 'Active')->get();
            $companies     = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches      = Branch::where('Status', 'Active')->orderBy('branch_name')->get();
            return view('in.members.membershipfeepayments.membershipfeepayments', compact('feePayments', 'members', 'feeSchedules', 'companies', 'branches'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . ' ' . auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storemembershipfeepayments(Request $request)
    {
        $request->validate([
            'member_id'        => 'required|exists:members,id',
            'fee_schedule_id'  => 'required|exists:membership_fee_schedules,id',
            'AmountPaid'       => 'required|numeric|min:0',
            'PaymentDate'      => 'required|date',
            'PaymentMethod'    => 'nullable|string|max:100',
            'PaymentReference' => 'nullable|string|max:100',
            'Narration'        => 'nullable|string',
            'company_id'       => 'nullable|exists:companies,id',
            'branch_id'        => 'nullable|exists:branchies,id',
        ]);

        try {

            $paymentRefNo = $this->generatePaymentRefNo();

            MembershipFeePayment::create([
                'PaymentRefNo'     => $paymentRefNo,
                'member_id'        => $request->member_id,
                'fee_schedule_id'  => $request->fee_schedule_id,
                'AmountPaid'       => $request->AmountPaid ?? 0.00,
                'PaymentDate'      => $request->PaymentDate,
                'PaymentMethod'    => $request->PaymentMethod,
                'PaymentReference' => $request->PaymentReference,
                'Narration'        => $request->Narration,
                'company_id'       => $request->company_id,
                'branch_id'        => $request->branch_id,
                'User_id'          => auth()->id(),
                'Status'           => 'Active',
                'AuditingStatus'   => 'Pending',
                'ReportStatus'     => 'Pending',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name, 'Operation completed successfully.'
            );
            return redirect()->back();

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function viewmembershipfeepayments($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $feePayment = MembershipFeePayment::with([
                    'member',
                    'feeSchedule',
                    'company',
                    'branch',
                    'user'
                ])
                ->findOrFail($id);

            return view('in.members.membershipfeepayments.viewmembershipfeepayments', compact('feePayment'));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function editmembershipfeepayments($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $feePayment = MembershipFeePayment::with([
                    'member',
                    'feeSchedule',
                    'company',
                    'branch',
                    'user'
                ])
                ->findOrFail($id);

            $members       = Member::where('Status', 'Active')->orderBy('member_code')->get();
            $feeSchedules  = MembershipFeeSchedule::where('Status', 'Active')->get();
            $companies     = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches      = Branch::where('Status', 'Active')->orderBy('branch_name')->get();

            return view('in.members.membershipfeepayments.editmembershipfeepayments', compact(
                'feePayment',
                'members',
                'feeSchedules',
                'companies',
                'branches'
            ));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function updatemembershipfeepayments(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'member_id'        => 'required|exists:members,id',
            'fee_schedule_id'  => 'required|exists:membership_fee_schedules,id',
            'AmountPaid'       => 'required|numeric|min:0',
            'PaymentDate'      => 'required|date',
            'PaymentMethod'    => 'nullable|string|max:100',
            'PaymentReference' => 'nullable|string|max:100',
            'Narration'        => 'nullable|string',
            'company_id'       => 'nullable|exists:companies,id',
            'branch_id'        => 'nullable|exists:branchies,id',
        ]);

        try {

            $feePayment = MembershipFeePayment::findOrFail($id);

            $feePayment->update([
                'member_id'        => $request->member_id,
                'fee_schedule_id'  => $request->fee_schedule_id,
                'AmountPaid'       => $request->AmountPaid ?? $feePayment->AmountPaid,
                'PaymentDate'      => $request->PaymentDate,
                'PaymentMethod'    => $request->PaymentMethod,
                'PaymentReference' => $request->PaymentReference,
                'Narration'        => $request->Narration,
                'company_id'       => $request->company_id,
                'branch_id'        => $request->branch_id,
            ]);

            Alert::success('Success', 'Membership fee payment updated successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back()->withInput();

        }
    }

    public function deletemembershipfeepayments($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $feePayment = MembershipFeePayment::findOrFail($id);

            $feePayment->update([
                'Status' => 'Deleted',
            ]);

            Alert::success('Success', 'Membership fee payment deleted successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    public function restoredmembershipfeepayments($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $feePayment = MembershipFeePayment::findOrFail($id);

            $feePayment->update([
                'Status' => 'Active',
            ]);

            Alert::success('Success', 'Membership fee payment restored successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    /**
     * Helper function to generate sequential PaymentRefNo in format: IFLSG/MFP/0001
     */
    private function generatePaymentRefNo()
    {
        $prefix = 'IFLSG/MFP/';

        $latestPayment = MembershipFeePayment::where('PaymentRefNo', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestPayment) {
            return $prefix . '0001';
        }

        $lastNumber = (int) substr($latestPayment->PaymentRefNo, strlen($prefix));
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $nextNumber;
    }
/*
    |--------------------------------------------------------------------------
    | STAGE 1: VERIFICATION METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Display unverified membership fee payments.
     */
    public function unverifiedmembershipfeepayments()
    {
        try {
            $payments = MembershipFeePayment::with([
                    'member', 'company', 'branch', 'user'
                ])
                ->where('Status', 'Active')
                ->where('verification_status', 'Pending')
                ->whereIn('transaction_status', ['Draft', 'Pending Verification'])
                ->latest()
                ->get();

            return view('in.members.membershipfeepayments.unverifiedmembershipfeepayments', compact('payments'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /**
     * Process verification action for membership fee payment.
     */
    public function verifymembershipfeepayment(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'verification_status'  => 'required|in:Verified,Rejected',
            'verification_remarks' => 'nullable|string',
        ]);

        try {
            $payment = MembershipFeePayment::findOrFail($id);

            if ($payment->verification_status !== 'Pending') {
                Alert::warning('Notice', 'This membership fee payment has already been verified.');
                return redirect()->back();
            }

            $isVerified = $request->verification_status === 'Verified';

            $payment->update([
                'verification_status'  => $request->verification_status,
                'verified_by'           => auth()->id(),
                'verified_at'           => now(),
                'verification_remarks' => $request->verification_remarks,
                'transaction_status'   => $isVerified ? 'Verified' : 'Rejected',
            ]);

            Alert::success(
                'Success', 
                $isVerified ? 'Membership fee payment verified successfully.' : 'Membership fee payment rejected at verification.'
            );

            return redirect()->back();

        } catch (\Throwable $th) {
            \Log::error('Error verifying membership fee payment', [
                'user_id' => Auth::id(), 'method' => __METHOD__, 'message' => $th->getMessage(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STAGE 2: APPROVAL METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Display membership fee payments pending manager approval.
     */
    public function pendingapprovalmembershipfeepayments()
    {
        try {
            $payments = MembershipFeePayment::with([
                    'member', 'verifier', 'company', 'branch'
                ])
                ->where('Status', 'Active')
                ->where('verification_status', 'Verified')
                ->where('approval_status', 'Pending')
                ->where('transaction_status', 'Verified')
                ->latest()
                ->get();

            return view('in.members.membershipfeepayments.pendingapprovalmembershipfeepayments', compact('payments'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /**
     * Process approval action for membership fee payment.
     */
    public function approvemembershipfeepayment(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'approval_status'  => 'required|in:Approved,Rejected',
            'approval_remarks' => 'nullable|string',
        ]);

        try {
            $payment = MembershipFeePayment::findOrFail($id);

            if ($payment->verification_status !== 'Verified') {
                Alert::error('Action Denied', 'Payment must be verified before approval.');
                return redirect()->back();
            }

            if ($payment->approval_status !== 'Pending') {
                Alert::warning('Notice', 'This payment has already been processed for approval.');
                return redirect()->back();
            }

            $isApproved = $request->approval_status === 'Approved';

            $payment->update([
                'approval_status'    => $request->approval_status,
                'approved_by'         => auth()->id(),
                'approved_at'         => now(),
                'approval_remarks'   => $request->approval_remarks,
                'transaction_status' => $isApproved ? 'Approved' : 'Rejected',
            ]);

            Alert::success(
                'Success', 
                $isApproved ? 'Membership fee payment approved successfully.' : 'Membership fee payment rejected at approval stage.'
            );

            return redirect()->back();

        } catch (\Throwable $th) {
            \Log::error('Error approving membership fee payment', [
                'user_id' => Auth::id(), 'method' => __METHOD__, 'message' => $th->getMessage(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STAGE 3: FINANCE & ACCOUNTING POSTING METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Display approved membership fee payments pending GL posting.
     */
    public function pendingpostingmembershipfeepayments()
    {
        try {
            $payments = MembershipFeePayment::with([
                    'member', 'verifier', 'approver', 'company', 'branch'
                ])
                ->where('Status', 'Active')
                ->where('approval_status', 'Approved')
                ->where('posting_status', 'Pending')
                ->where('transaction_status', 'Approved')
                ->latest()
                ->get();

            return view('in.members.membershipfeepayments.pendingpostingmembershipfeepayments', compact('payments'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /**
     * Post membership fee payment to GL accounts.
     */
    public function postmembershipfeepayment(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'cash_account_code'           => 'required|string|max:30',
            'fee_revenue_account_code'    => 'required|string|max:30',
            'posting_reference'           => 'nullable|string|max:100',
        ]);

        try {
            $payment = MembershipFeePayment::findOrFail($id);

            if ($payment->approval_status !== 'Approved') {
                Alert::error('Action Denied', 'Payment must be approved before GL posting.');
                return redirect()->back();
            }

            if ($payment->posting_status === 'Posted') {
                Alert::warning('Notice', 'This membership fee payment is already posted to GL.');
                return redirect()->back();
            }

            DB::beginTransaction();

            $journalNo = 'JNL/FEE/' . date('Ym') . '/' . str_pad($payment->id, 5, '0', STR_PAD_LEFT);

            $payment->update([
                'posting_status'                   => 'Posted',
                'posted_by'                        => auth()->id(),
                'posted_at'                        => now(),
                'posting_reference'               => $request->posting_reference ?? $payment->PaymentRefNo,
                'journal_no'                       => $journalNo,
                'cash_account_code'               => $request->cash_account_code,
                'social_contribution_account_code' => $request->fee_revenue_account_code, // schema field
                'transaction_status'               => 'Posted',
            ]);

            DB::commit();

            Alert::success('Success', "Membership fee payment posted to GL with Journal Ref: {$journalNo}");

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            \Log::error('Error posting membership fee payment to GL', [
                'user_id' => Auth::id(), 'method' => __METHOD__, 'message' => $th->getMessage(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /**
     * Display fully completed/posted membership fee payments.
     */
    public function completedmembershipfeepayments()
    {
        try {
            $payments = MembershipFeePayment::with([
                    'member', 'poster', 'company', 'branch'
                ])
                ->where('Status', 'Active')
                ->where('posting_status', 'Posted')
                ->where('transaction_status', 'Posted')
                ->latest()
                ->get();

            return view('in.members.membershipfeepayments.completedmembershipfeepayments', compact('payments'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    // Social Contribution Schedule

    public function socialcontributionschedules()
    {
        try {

            $schedules = SocialContributionSchedule::with([
                    'company',
                    'branch',
                    'user'
                ])
                ->where('Status', 'Active')
                ->latest()
                ->get();

            $companies = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches  = Branch::where('Status', 'Active')->orderBy('branch_name')->get();

            return view('in.members.socialcontributionschedules.socialcontributionschedules', compact('schedules', 'companies', 'branches'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . ' ' . auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storesocialcontributionschedules(Request $request)
    {
        $request->validate([
            'FeeAmount'     => 'required|numeric|min:0',
            'EffectiveFrom' => 'required|date',
            'EffectiveTo'   => 'nullable|date|after_or_equal:EffectiveFrom',
            'Description'   => 'nullable|string',
            'company_id'    => 'nullable|exists:companies,id',
            'branch_id'     => 'nullable|exists:branchies,id',
        ]);

        try {

            $scheduleRefNo = $this->generateSocialScheduleRefNo();

            SocialContributionSchedule::create([
                'ScheduleRefNo'  => $scheduleRefNo,
                'FeeAmount'      => $request->FeeAmount ?? 0.00,
                'EffectiveFrom'  => $request->EffectiveFrom,
                'EffectiveTo'    => $request->EffectiveTo,
                'Description'    => $request->Description,
                'company_id'     => $request->company_id,
                'branch_id'      => $request->branch_id,
                'User_id'        => auth()->id(),
                'Status'         => 'Active',
                'AuditingStatus' => 'Pending',
                'ReportStatus'   => 'Pending',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name, 'Operation completed successfully.'
            );
            return redirect()->back();

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function viewsocialcontributionschedules($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $schedule = SocialContributionSchedule::with([
                    'contributions',
                    'company',
                    'branch',
                    'user'
                ])
                ->findOrFail($id);

            return view('in.members.socialcontributionschedules.viewsocialcontributionschedules', compact('schedule'));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function editsocialcontributionschedules($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $schedule = SocialContributionSchedule::with([
                    'company',
                    'branch',
                    'user'
                ])
                ->findOrFail($id);

            $companies = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches  = Branch::where('Status', 'Active')->orderBy('branch_name')->get();

            return view('in.members.socialcontributionschedules.editsocialcontributionschedules', compact(
                'schedule',
                'companies',
                'branches'
            ));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function updatesocialcontributionschedules(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'FeeAmount'     => 'required|numeric|min:0',
            'EffectiveFrom' => 'required|date',
            'EffectiveTo'   => 'nullable|date|after_or_equal:EffectiveFrom',
            'Description'   => 'nullable|string',
            'company_id'    => 'nullable|exists:companies,id',
            'branch_id'     => 'nullable|exists:branchies,id',
        ]);

        try {

            $schedule = SocialContributionSchedule::findOrFail($id);

            $schedule->update([
                'FeeAmount'     => $request->FeeAmount ?? $schedule->FeeAmount,
                'EffectiveFrom' => $request->EffectiveFrom,
                'EffectiveTo'   => $request->EffectiveTo,
                'Description'   => $request->Description,
                'company_id'    => $request->company_id,
                'branch_id'     => $request->branch_id,
            ]);

            Alert::success('Success', 'Social contribution schedule updated successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back()->withInput();

        }
    }

    public function deletesocialcontributionschedules($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $schedule = SocialContributionSchedule::findOrFail($id);

            $schedule->update([
                'Status' => 'Deleted',
            ]);

            Alert::success('Success', 'Social contribution schedule deleted successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    public function restoresocialcontributionschedules($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $schedule = SocialContributionSchedule::findOrFail($id);

            $schedule->update([
                'Status' => 'Active',
            ]);

            Alert::success('Success', 'Social contribution schedule restored successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    /**
     * Helper function to generate sequential ScheduleRefNo in format: IFLSG/SCS/0001
     */
    private function generateSocialScheduleRefNo()
    {
        $prefix = 'IFLSG/SCS/';

        $latestSchedule = SocialContributionSchedule::where('ScheduleRefNo', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestSchedule) {
            return $prefix . '0001';
        }

        $lastNumber = (int) substr($latestSchedule->ScheduleRefNo, strlen($prefix));
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $nextNumber;
    }



    // Social Contribution

    public function socialcontributionshub()
    {
        try {
            return view('in.members.socialcontributions.socialcontributionshub');

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . ' ' . auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }
    public function socialcontributions()
    {
        try {

            $contributions = SocialContribution::with([
                    'schedule',
                    'member',
                    'company',
                    'branch',
                    'user'
                ])
                ->where('Status', 'Active')
                ->latest()
                ->get();
            $schedules = SocialContributionSchedule::where('Status', 'Active')->get();
            $members   = Member::where('Status', 'Active')->orderBy('member_name')->get();
            $companies = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches  = Branch::where('Status', 'Active')->orderBy('branch_name')->get();
            return view('in.members.socialcontributions.socialcontributions', compact('contributions', 'schedules', 'members', 'companies', 'branches'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . ' ' . auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storesocialcontributions(Request $request)
    {
        $request->validate([
            'social_contribution_schedule_id' => 'required|exists:social_contributions_schedules,id',
            'member_id'                        => 'required|exists:members,id',
            'ContributionMonth'               => 'required|date',
            'ExpectedAmount'                  => 'nullable|numeric|min:0',
            'AmountPaid'                      => 'required|numeric|min:0',
            'PaymentDate'                     => 'required|date',
            'PaymentMethod'                   => 'nullable|string|max:100',
            'PaymentReference'                => 'nullable|string|max:100',
            'PaymentStatus'                   => 'nullable|string|max:50',
            'Narration'                       => 'nullable|string',
            'company_id'                      => 'nullable|exists:companies,id',
            'branch_id'                       => 'nullable|exists:branchies,id',
        ]);

        try {

            $contributionRefNo = $this->generateContributionRefNo();

            $social_contribution_schedule = SocialContributionSchedule::findOrFail($request->social_contribution_schedule_id);
            $branchId = $social_contribution_schedule->branch_id;
            $CompanyId = $social_contribution_schedule->company_id;

            SocialContribution::create([
                'ContributionRefNo'               => $contributionRefNo,
                'social_contribution_schedule_id' => $request->social_contribution_schedule_id,
                'member_id'                        => $request->member_id,
                'ContributionMonth'               => $request->ContributionMonth,
                'ExpectedAmount'                  => $request->ExpectedAmount ?? 0.00,
                'AmountPaid'                      => $request->AmountPaid ?? 0.00,
                'PaymentDate'                     => $request->PaymentDate,
                'PaymentMethod'                   => $request->PaymentMethod,
                'PaymentReference'                => $request->PaymentReference,
                'PaymentStatus'                   => $request->PaymentStatus ?? 'Paid',
                'Narration'                       => $request->Narration,
                'company_id'                      => $CompanyId,
                'branch_id'                       => $branchId,
                'User_id'                         => auth()->id(),
                'Status'                          => 'Active',
                'AuditingStatus'                  => 'Pending',
                'ReportStatus'                    => 'Pending',
            ]);

            Alert::success(
                'Success ' . auth()->user()->name, 'Operation completed successfully.'
            );
            return redirect()->back();

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    /**
     * Download monthly social contribution template pre-filled with members 
     * who have NOT yet contributed for the given month.
     */
    public function downloadsocialcontributiontemplate(Request $request)
    {
        $request->validate([
            'ScheduleRefNo'     => 'required|exists:social_contributions_schedules,ScheduleRefNo',
            'ContributionMonth' => 'nullable|date_format:Y-m',
        ]);

        try {

            $schedule = SocialContributionSchedule::where('ScheduleRefNo', $request->ScheduleRefNo)
                ->where('Status', 'Active')
                ->firstOrFail();

            // Default to current month if not specified
            $month = $request->ContributionMonth ? $request->ContributionMonth . '-01' : now()->format('Y-m-01');

            // Get members who ALREADY paid for this schedule in this month
            $paidMemberIds = SocialContribution::where('social_contribution_schedule_id', $schedule->id)
                ->whereDate('ContributionMonth', $month)
                ->where('Status', 'Active')
                ->pluck('member_id')
                ->toArray();

            // Query active members who HAVE NOT paid yet
            $unpaidMembers = Member::where('Status', 'Active')
                ->whereNotIn('id', $paidMemberIds)
                ->orderBy('member_code')
                ->get();

            $exportData = [];

            // Header row
            $exportData[] = [
                'ScheduleRefNo',
                'FeeAmount',
                'MemberCode',
                'MemberName',
                'ContributionMonth',
                'ExpectedAmount',
                'AmountPaid',
                'PaymentDate',
                'PaymentMethod',
                'PaymentReference',
                'Narration'
            ];

            // Data rows for unpaid members
            foreach ($unpaidMembers as $member) {
                $exportData[] = [
                    $schedule->ScheduleRefNo,
                    $schedule->FeeAmount,
                    $member->member_code,
                    $member->member_name ?? ' ',
                    $month,
                    $schedule->FeeAmount, // Default expected amount
                    $schedule->FeeAmount, // Pre-filled amount paid (editable by user)
                    now()->format('Y-m-d'),
                    'Bank Transfer',
                    '',
                    'Monthly Social Contribution'
                ];
            }

            $fileName = 'Social_Contribution_Template_' . str_replace('/', '_', $schedule->ScheduleRefNo) . '_' . date('Y_m', strtotime($month)) . '.xlsx';

            return Excel::download(new class($exportData) implements \Maatwebsite\Excel\Concerns\FromArray {
                protected $data;
                public function __construct(array $data) { $this->data = $data; }
                public function array(): array { return $this->data; }
            }, $fileName);

        } catch (\Throwable $th) {
            \Log::error('Error generating template', [
                'user_id' => Auth::id(), 'method' => __METHOD__, 'message' => $th->getMessage(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error('Sorry! ' . auth()->user()->name, 'Failed to download template. Please try again.');
            return back();
        }
    }

    /**
     * Import monthly social contributions via Excel batch upload
     */
    public function importsocialcontributions(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240',
            'company_id' => 'nullable|exists:companies,id',
            'branch_id'  => 'nullable|exists:branchies,id',
        ]);

        try {

            $file = $request->file('excel_file');
            $rows = Excel::toArray([], $file)[0] ?? [];

            if (empty($rows) || count($rows) <= 1) {
                Alert::error('Sorry! ' . auth()->user()->name, 'The uploaded Excel file is empty or missing data rows.');
                return back();
            }

            // Remove header row
            unset($rows[0]);

            DB::beginTransaction();

            $importedCount = 0;
            $skippedCount  = 0;

            // Cache Schedules and Members to avoid repetitive N+1 DB calls inside loop
            $schedules = SocialContributionSchedule::where('Status', 'Active')
                ->pluck('id', 'ScheduleRefNo')
                ->toArray();

            $members = Member::where('Status', 'Active')
                ->pluck('id', 'member_code')
                ->toArray();

            foreach ($rows as $row) {

                // Excel Column Index Mapping:
                // 0: ScheduleRefNo
                // 1: FeeAmount
                // 2: MemberCode
                // 3: MemberName
                // 4: ContributionMonth (Y-m-d)
                // 5: ExpectedAmount
                // 6: AmountPaid
                // 7: PaymentDate (Y-m-d)
                // 8: PaymentMethod
                // 9: PaymentReference
                // 10: Narration

                $scheduleRefNo     = trim($row[0] ?? '');
                $memberCode        = trim($row[2] ?? '');
                $contributionMonth = trim($row[4] ?? '');
                $expectedAmount    = $row[5] ?? 0.00;
                $amountPaid        = $row[6] ?? 0.00;
                $paymentDate       = !empty($row[7]) ? $row[7] : now()->format('Y-m-d');
                $paymentMethod     = $row[8] ?? 'Excel Import';
                $paymentReference  = $row[9] ?? null;
                $narration         = $row[10] ?? 'Monthly Excel Batch Import';

                // Map ScheduleRefNo -> social_contribution_schedule_id
                $scheduleId = $schedules[$scheduleRefNo] ?? null;

                // Map MemberCode -> member_id
                $memberId = $members[$memberCode] ?? null;

                // Skip row if Schedule, Member, or Month is invalid/unresolved
                if (!$scheduleId || !$memberId || !$contributionMonth) {
                    $skippedCount++;
                    continue;
                }

                $contributionRefNo = $this->generateContributionRefNo();

                SocialContribution::create([
                    'ContributionRefNo'               => $contributionRefNo,
                    'social_contribution_schedule_id' => $scheduleId,
                    'member_id'                        => $memberId,
                    'ContributionMonth'               => $contributionMonth,
                    'ExpectedAmount'                  => $expectedAmount,
                    'AmountPaid'                      => $amountPaid,
                    'PaymentDate'                     => $paymentDate,
                    'PaymentMethod'                   => $paymentMethod,
                    'PaymentReference'                => $paymentReference,
                    'PaymentStatus'                   => ($amountPaid >= $expectedAmount) ? 'Paid' : 'Partial',
                    'Narration'                       => $narration,
                    'company_id'                      => $request->company_id,
                    'branch_id'                       => $request->branch_id,
                    'User_id'                         => auth()->id(),
                    'Status'                          => 'Active',
                    'AuditingStatus'                  => 'Pending',
                    'ReportStatus'                    => 'Pending',
                ]);

                $importedCount++;
            }

            DB::commit();

            $message = "Successfully imported {$importedCount} record(s).";
            if ($skippedCount > 0) {
                $message .= " ({$skippedCount} row(s) skipped due to invalid Schedule Ref or Member Code).";
            }

            Alert::success('Success ' . auth()->user()->name, $message);

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }
    public function viewsocialcontributions($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $contribution = SocialContribution::with([
                    'schedule',
                    'member',
                    'company',
                    'branch',
                    'user'
                ])
                ->findOrFail($id);

            return view('in.members.socialcontributions.viewsocialcontributions', compact('contribution'));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function editsocialcontributions($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $contribution = SocialContribution::with([
                    'schedule',
                    'member',
                    'company',
                    'branch',
                    'user'
                ])
                ->findOrFail($id);

            $schedules = SocialContributionSchedule::where('Status', 'Active')->get();
            $members   = Member::where('Status', 'Active')->orderBy('member_name')->get();
            $companies = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches  = Branch::where('Status', 'Active')->orderBy('branch_name')->get();

            return view('in.members.socialcontributions.editsocialcontributions', compact(
                'contribution',
                'schedules',
                'members',
                'companies',
                'branches'
            ));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function updatesocialcontributions(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'social_contribution_schedule_id' => 'required|exists:social_contributions_schedules,id',
            'member_id'                        => 'required|exists:members,id',
            'ContributionMonth'               => 'required|date',
            'ExpectedAmount'                  => 'nullable|numeric|min:0',
            'AmountPaid'                      => 'required|numeric|min:0',
            'PaymentDate'                     => 'required|date',
            'PaymentMethod'                   => 'nullable|string|max:100',
            'PaymentReference'                => 'nullable|string|max:100',
            'PaymentStatus'                   => 'nullable|string|max:50',
            'Narration'                       => 'nullable|string',
            'company_id'                      => 'nullable|exists:companies,id',
            'branch_id'                       => 'nullable|exists:branchies,id',
        ]);

        try {

            $contribution = SocialContribution::findOrFail($id);

            $contribution->update([
                'social_contribution_schedule_id' => $request->social_contribution_schedule_id,
                'member_id'                        => $request->member_id,
                'ContributionMonth'               => $request->ContributionMonth,
                'ExpectedAmount'                  => $request->ExpectedAmount ?? $contribution->ExpectedAmount,
                'AmountPaid'                      => $request->AmountPaid ?? $contribution->AmountPaid,
                'PaymentDate'                     => $request->PaymentDate,
                'PaymentMethod'                   => $request->PaymentMethod,
                'PaymentReference'                => $request->PaymentReference,
                'PaymentStatus'                   => $request->PaymentStatus ?? $contribution->PaymentStatus,
                'Narration'                       => $request->Narration,
                'company_id'                      => $request->company_id,
                'branch_id'                       => $request->branch_id,
            ]);

            Alert::success('Success', 'Social contribution updated successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back()->withInput();

        }
    }

    public function deletesocialcontributions($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $contribution = SocialContribution::findOrFail($id);

            $contribution->update([
                'Status' => 'Deleted',
            ]);

            Alert::success('Success', 'Social contribution deleted successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    public function restoresocialcontributions($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $contribution = SocialContribution::findOrFail($id);

            $contribution->update([
                'Status' => 'Active',
            ]);

            Alert::success('Success', 'Social contribution restored successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    /**
     * Helper function to generate sequential ContributionRefNo in format: IFLSG/SOC/0001
     */
    private function generateContributionRefNo()
    {
        $prefix = 'IFLSG/SOC/';

        $latestContribution = SocialContribution::where('ContributionRefNo', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestContribution) {
            return $prefix . '0001';
        }

        $lastNumber = (int) substr($latestContribution->ContributionRefNo, strlen($prefix));
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $nextNumber;
    }


/*
    |--------------------------------------------------------------------------
    | STAGE 1: VERIFICATION METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Display unverified social contributions.
     */
    public function unverifiedsocialcontributions()
    {
        try {
            $contributions = SocialContribution::with([
                    'schedule', 'member', 'company', 'branch', 'user'
                ])
                ->where('Status', 'Active')
                ->where('verification_status', 'Pending')
                ->whereIn('transaction_status', ['Draft', 'Pending Verification'])
                ->latest()
                ->get();

            return view('in.members.socialcontributions.unverifiedsocialcontributions', compact('contributions'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /**
     * Process verification action for social contribution.
     */
    public function verifysocialcontribution(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'verification_status'  => 'required|in:Verified,Rejected',
            'verification_remarks' => 'nullable|string',
        ]);

        try {
            $contribution = SocialContribution::findOrFail($id);

            if ($contribution->verification_status !== 'Pending') {
                Alert::warning('Notice', 'This contribution has already been processed for verification.');
                return redirect()->back();
            }

            $isVerified = $request->verification_status === 'Verified';

            $contribution->update([
                'verification_status'  => $request->verification_status,
                'verified_by'           => auth()->id(),
                'verified_at'           => now(),
                'verification_remarks' => $request->verification_remarks,
                'transaction_status'   => $isVerified ? 'Verified' : 'Rejected',
            ]);

            Alert::success(
                'Success', 
                $isVerified ? 'Social contribution verified successfully.' : 'Social contribution rejected at verification.'
            );

            return redirect()->back();

        } catch (\Throwable $th) {
            \Log::error('Error verifying social contribution', [
                'user_id' => Auth::id(), 'method' => __METHOD__, 'message' => $th->getMessage(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STAGE 2: APPROVAL METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Display contributions pending approval.
     */
    public function pendingapprovalsocialcontributions()
    {
        try {
            $contributions = SocialContribution::with([
                    'schedule', 'member', 'verifier', 'company', 'branch'
                ])
                ->where('Status', 'Active')
                ->where('verification_status', 'Verified')
                ->where('approval_status', 'Pending')
                ->where('transaction_status', 'Verified')
                ->latest()
                ->get();

            return view('in.members.socialcontributions.pendingapprovalsocialcontributions', compact('contributions'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /**
     * Process approval action for social contribution.
     */
    public function approvesocialcontribution(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'approval_status'  => 'required|in:Approved,Rejected',
            'approval_remarks' => 'nullable|string',
        ]);

        try {
            $contribution = SocialContribution::findOrFail($id);

            if ($contribution->verification_status !== 'Verified') {
                Alert::error('Action Denied', 'Contribution must be verified before approval.');
                return redirect()->back();
            }

            if ($contribution->approval_status !== 'Pending') {
                Alert::warning('Notice', 'This contribution has already been processed for approval.');
                return redirect()->back();
            }

            $isApproved = $request->approval_status === 'Approved';

            $contribution->update([
                'approval_status'    => $request->approval_status,
                'approved_by'         => auth()->id(),
                'approved_at'         => now(),
                'approval_remarks'   => $request->approval_remarks,
                'transaction_status' => $isApproved ? 'Approved' : 'Rejected',
            ]);

            Alert::success(
                'Success', 
                $isApproved ? 'Social contribution approved successfully.' : 'Social contribution rejected at approval stage.'
            );

            return redirect()->back();

        } catch (\Throwable $th) {
            \Log::error('Error approving social contribution', [
                'user_id' => Auth::id(), 'method' => __METHOD__, 'message' => $th->getMessage(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STAGE 3: FINANCE & ACCOUNTING POSTING METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Display approved contributions pending accounting posting.
     */
    public function pendingpostingsocialcontributions()
    {
        try {
            $contributions = SocialContribution::with([
                    'schedule', 'member', 'verifier', 'approver', 'company', 'branch'
                ])
                ->where('Status', 'Active')
                ->where('approval_status', 'Approved')
                ->where('posting_status', 'Pending')
                ->where('transaction_status', 'Approved')
                ->latest()
                ->get();

            return view('in.members.socialcontributions.pendingpostingsocialcontributions', compact('contributions'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /**
     * Post social contribution to GL accounts.
     */
    public function postsocialcontribution(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'cash_account_code'                => 'required|string|max:30',
            'social_contribution_account_code' => 'required|string|max:30',
            'posting_reference'                => 'nullable|string|max:100',
        ]);

        try {
            $contribution = SocialContribution::findOrFail($id);

            if ($contribution->approval_status !== 'Approved') {
                Alert::error('Action Denied', 'Contribution must be approved before accounting posting.');
                return redirect()->back();
            }

            if ($contribution->posting_status === 'Posted') {
                Alert::warning('Notice', 'This social contribution is already posted to GL.');
                return redirect()->back();
            }

            DB::beginTransaction();

            $journalNo = 'JNL/SOC/' . date('Ym') . '/' . str_pad($contribution->id, 5, '0', STR_PAD_LEFT);

            $contribution->update([
                'posting_status'                   => 'Posted',
                'posted_by'                        => auth()->id(),
                'posted_at'                        => now(),
                'posting_reference'               => $request->posting_reference ?? $contribution->ContributionRefNo,
                'journal_no'                       => $journalNo,
                'cash_account_code'               => $request->cash_account_code,
                'social_contribution_account_code' => $request->social_contribution_account_code,
                'transaction_status'               => 'Posted',
            ]);

            DB::commit();

            Alert::success('Success', "Social contribution posted to GL with Journal Ref: {$journalNo}");

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            \Log::error('Error posting social contribution to GL', [
                'user_id' => Auth::id(), 'method' => __METHOD__, 'message' => $th->getMessage(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /**
     * Display fully posted and completed social contributions.
     */
    public function completedsocialcontributions()
    {
        try {
            $contributions = SocialContribution::with([
                    'schedule', 'member', 'poster', 'company', 'branch'
                ])
                ->where('Status', 'Active')
                ->where('posting_status', 'Posted')
                ->where('transaction_status', 'Posted')
                ->latest()
                ->get();

            return view('in.members.socialcontributions.completedsocialcontributions', compact('contributions'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }
}