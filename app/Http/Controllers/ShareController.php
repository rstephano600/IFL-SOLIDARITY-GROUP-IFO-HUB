<?php

namespace App\Http\Controllers;

use App\Models\ShareType;
use App\Models\ShareOffering;
use App\Models\SharePurchaseTransaction;
use App\Models\ShareCertificate;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ShareController extends Controller
{

    public function sharepurchasetransactionshub()
    {
        try {
            return view('in.shares.sharepurchasetransactions.sharepurchasetransactionshub');
        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical for support Tel:+255657856790');
            return back();
        }
    }
    public function sharepurchasetransactions()
    {
        try {

            $transactions = SharePurchaseTransaction::with([
                    'member',
                    'shareOffering',
                    'shareType',
                    'transfer',
                    'company',
                    'branch',
                    'user'
                ])
                ->where('Status', 'Active')
                ->latest()
                ->get();
            $offerings  = ShareOffering::where('Status', 'Active')->get();
            $shareTypes = ShareType::where('Status', 'Active')->get();
            $members    = Member::where('Status', 'Active')->orderBy('member_code')->get();
            $companies  = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches   = Branch::where('Status', 'Active')->orderBy('branch_name')->get();
            return view('in.shares.sharepurchasetransactions.sharepurchasetransactions', compact('transactions', 'offerings','shareTypes','members','companies','branches'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical for support Tel:+255657856790');
            return back();
        }
    }

    public function storesharepurchasetransactions(Request $request)
    {
        $request->validate([
            'member_id'         => 'required|exists:members,id',
            'share_offering_id' => 'required|exists:share_offerings,id',
            'share_type_id'     => 'required|exists:share_types,id',
            'TransactionType'   => 'required|string|max:50',
            'SharesQuantity'    => 'required|numeric|min:0.01',
            'PricePerShare'     => 'required|numeric|min:0.01',
            'TransactionDate'   => 'required|date',
            'PaymentMethod'     => 'nullable|string|max:100',
            'PaymentReference'  => 'nullable|string|max:100',
            'Narration'         => 'nullable|string',
            'company_id'        => 'nullable|exists:companies,id',
            'branch_id'         => 'nullable|exists:branches,id',
        ]);

        try {

            $transactionRefNo = $this->generateTransactionRefNo();

            $share_offering = ShareOffering::findOrFail($request->share_offering_id);
            $branchId = $share_offering->branch_id;
            $CompanyId = $share_offering->company_id;

            SharePurchaseTransaction::create([
                'TransactionRefNo'  => $transactionRefNo,
                'member_id'         => $request->member_id,
                'share_offering_id' => $request->share_offering_id,
                'share_type_id'     => $request->share_type_id,
                'TransactionType'   => $request->TransactionType ?? 'Purchase',
                'SharesQuantity'    => $request->SharesQuantity,
                'PricePerShare'     => $share_offering->PricePerShare,
                'TransactionDate'   => $request->TransactionDate,
                'PaymentMethod'     => $request->PaymentMethod,
                'PaymentReference'  => $request->PaymentReference,
                'Narration'         => $request->Narration,
                'company_id'        => $CompanyId,
                'branch_id'         => $branchId,
                'User_id'           => auth()->id(),
                'Status'            => 'Active',
                'AuditingStatus'    => 'Pending',
                'ReportStatus'      => 'Pending',
            ]);

            Alert::success('Success ' . auth()->user()->name, 'Share purchase transaction recorded successfully.');
            return redirect()->back();

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method' => __METHOD__, 'message' => $th->getMessage(), 'file' => $th->getFile(), 'line' => $th->getLine(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    /**
     * Download Share Purchase Excel Template.
     * Pre-fills active members who have not participated in the given share offering yet.
     */
    public function downloadsharepurchasetemplate(Request $request)
    {
        $request->validate([
            'OfferingRefNo' => 'required|exists:share_offerings,OfferingRefNo',
        ]);

        try {

            $offering = ShareOffering::with('shareType')
                ->where('OfferingRefNo', $request->OfferingRefNo)
                ->where('Status', 'Active')
                ->firstOrFail();

            // Get members who already made a purchase on this offering
            $existingMemberIds = SharePurchaseTransaction::where('share_offering_id', $offering->id)
                ->where('Status', 'Active')
                ->pluck('member_id')
                ->toArray();

            // Fetch active members without purchases under this offering
            $eligibleMembers = Member::where('Status', 'Active')
                ->whereNotIn('id', $existingMemberIds)
                ->orderBy('member_code')
                ->get();

            $exportData = [];

            // Header row
            $exportData[] = [
                'OfferingRefNo',
                'TypeCode',
                'MemberCode',
                'MemberName',
                'TransactionType',
                'SharesQuantity',
                'PricePerShare',
                'TransactionDate',
                'PaymentMethod',
                'PaymentReference',
                'Narration',
            ];

            // Data rows
            foreach ($eligibleMembers as $member) {
                $exportData[] = [
                    $offering->OfferingRefNo,
                    $offering->shareType->TypeCode ?? $offering->shareType->TypeName ?? 'COMMON',
                    $member->member_code,
                    $member->member_name ?? ($member->first_name . ' ' . $member->last_name),
                    'Purchase',
                    1, // Default quantity (editable)
                    $offering->PricePerShare ?? $offering->Price ?? 0.00,
                    now()->format('Y-m-d'),
                    'Bank Transfer',
                    '',
                    'Members Share Purchases',
                ];
            }

            $fileName = 'Share_Purchase_Template_' . str_replace('/', '_', $offering->OfferingRefNo) . '_' . date('Y_m_d') . '.xlsx';

            return Excel::download(new class($exportData) implements \Maatwebsite\Excel\Concerns\FromArray {
                protected $data;
                public function __construct(array $data) { $this->data = $data; }
                public function array(): array { return $this->data; }
            }, $fileName);

        } catch (\Throwable $th) {
            \Log::error('Error generating share purchase template', [
                'user_id' => Auth::id(), 'method' => __METHOD__, 'message' => $th->getMessage(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error('Sorry! ' . auth()->user()->name, 'Failed to download template. Please try again.');
            return back();
        }
    }

    /**
     * Import Share Purchase Transactions via Excel batch upload.
     */
    public function importsharepurchasetransactions(Request $request)
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

            // Cache Offerings, Share Types, and Members for speed
            $offerings = ShareOffering::where('Status', 'Active')
                ->pluck('id', 'OfferingRefNo')
                ->toArray();

            $shareTypes = ShareType::where('Status', 'Active')
                ->get()
                ->keyBy(function ($item) {
                    return $item->TypeCode ?? $item->TypeName;
                })
                ->map(fn($item) => $item->id)
                ->toArray();

            $members = Member::where('Status', 'Active')
                ->pluck('id', 'member_code')
                ->toArray();

            foreach ($rows as $row) {

                // Excel Column Index Mapping:
                // 0: OfferingRefNo
                // 1: TypeCode
                // 2: MemberCode
                // 3: MemberName
                // 4: TransactionType
                // 5: SharesQuantity
                // 6: PricePerShare
                // 7: TransactionDate (Y-m-d)
                // 8: PaymentMethod
                // 9: PaymentReference
                // 10: Narration

                $offeringRefNo   = trim($row[0] ?? '');
                $typeCode        = trim($row[1] ?? '');
                $memberCode      = trim($row[2] ?? '');
                $transactionType = trim($row[4] ?? 'Purchase');
                $sharesQuantity  = $row[5] ?? 0;
                $pricePerShare   = $row[6] ?? 0.00;
                $transactionDate = !empty($row[7]) ? $row[7] : now()->format('Y-m-d');
                $paymentMethod   = $row[8] ?? 'Excel Import';
                $paymentReference = $row[9] ?? null;
                $narration       = $row[10] ?? 'Share Purchase Batch Import';

                // Map identifiers to internal IDs
                $offeringId  = $offerings[$offeringRefNo] ?? null;
                $shareTypeId = $shareTypes[$typeCode] ?? null;
                $memberId    = $members[$memberCode] ?? null;

                // Skip row if mandatory identifiers or quantities are invalid
                if (!$offeringId || !$shareTypeId || !$memberId || $sharesQuantity <= 0) {
                    $skippedCount++;
                    continue;
                }

                $transactionRefNo = $this->generateTransactionRefNo();

                SharePurchaseTransaction::create([
                    'TransactionRefNo'  => $transactionRefNo,
                    'share_offering_id' => $offeringId,
                    'share_type_id'     => $shareTypeId,
                    'member_id'         => $memberId,
                    'TransactionType'   => $transactionType,
                    'SharesQuantity'    => $sharesQuantity,
                    'PricePerShare'     => $pricePerShare,
                    'TransactionDate'   => $transactionDate,
                    'PaymentMethod'     => $paymentMethod,
                    'PaymentReference'  => $paymentReference,
                    'Narration'         => $narration,
                    'company_id'        => $request->company_id,
                    'branch_id'         => $request->branch_id,
                    'User_id'           => auth()->id(),
                    'Status'            => 'Active',
                    'AuditingStatus'    => 'Pending',
                    'ReportStatus'      => 'Pending',
                ]);

                $importedCount++;
            }

            DB::commit();

            $message = "Successfully imported {$importedCount} share purchase record(s).";
            if ($skippedCount > 0) {
                $message .= " ({$skippedCount} row(s) skipped due to unresolved Offering Ref, Type Code, or Member Code).";
            }

            Alert::success('Success ' . auth()->user()->name, $message);

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method' => __METHOD__, 'message' => $th->getMessage(), 'file' => $th->getFile(), 'line' => $th->getLine(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function viewsharepurchasetransactions($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $transaction = SharePurchaseTransaction::with([
                    'member',
                    'shareOffering',
                    'shareType',
                    'transfer',
                    'company',
                    'branch',
                    'user'
                ])
                ->findOrFail($id);

            return view('in.shares.sharepurchasetransactions.viewsharepurchasetransactions', compact('transaction'));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method' => __METHOD__, 'message' => $th->getMessage(), 'file' => $th->getFile(), 'line' => $th->getLine(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function editsharepurchasetransaction($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $transaction = SharePurchaseTransaction::with([
                    'member',
                    'shareOffering',
                    'shareType',
                    'transfer',
                    'company',
                    'branch',
                    'user'
                ])
                ->findOrFail($id);

            $offerings  = ShareOffering::where('Status', 'Active')->get();
            $shareTypes = ShareType::where('Status', 'Active')->get();
            $members    = Member::where('Status', 'Active')->orderBy('member_name')->get();
            $companies  = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches   = Branch::where('Status', 'Active')->orderBy('branch_name')->get();

            return view('in.shares.sharepurchasetransactions.editsharepurchasetransaction', compact(
                'transaction',
                'offerings',
                'shareTypes',
                'members',
                'companies',
                'branches'
            ));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method' => __METHOD__, 'message' => $th->getMessage(), 'file' => $th->getFile(), 'line' => $th->getLine(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            return back()->withInput();
        }
    }

    public function updatesharepurchasetransaction(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'member_id'         => 'required|exists:members,id',
            'share_offering_id' => 'required|exists:share_offerings,id',
            'share_type_id'     => 'required|exists:share_types,id',
            'TransactionType'   => 'required|string|max:50',
            'SharesQuantity'    => 'required|numeric|min:0.01',
            'PricePerShare'     => 'required|numeric|min:0.01',
            'TransactionDate'   => 'required|date',
            'PaymentMethod'     => 'nullable|string|max:100',
            'PaymentReference'  => 'nullable|string|max:100',
            'Narration'         => 'nullable|string',
            'company_id'        => 'nullable|exists:companies,id',
            'branch_id'         => 'nullable|exists:branchies,id',
        ]);

        try {

            $transaction = SharePurchaseTransaction::findOrFail($id);

            $transaction->update([
                'member_id'         => $request->member_id,
                'share_offering_id' => $request->share_offering_id,
                'share_type_id'     => $request->share_type_id,
                'TransactionType'   => $request->TransactionType ?? $transaction->TransactionType,
                'SharesQuantity'    => $request->SharesQuantity,
                'PricePerShare'     => $request->PricePerShare,
                'TransactionDate'   => $request->TransactionDate,
                'PaymentMethod'     => $request->PaymentMethod,
                'PaymentReference'  => $request->PaymentReference,
                'Narration'         => $request->Narration,
                'company_id'        => $request->company_id,
                'branch_id'         => $request->branch_id,
            ]);

            Alert::success('Success', 'Share purchase transaction updated successfully.');
            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function deletesharepurchasetransaction($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $transaction = SharePurchaseTransaction::findOrFail($id);

            $transaction->update([
                'Status' => 'Deleted',
            ]);

            Alert::success('Success', 'Share purchase transaction deleted successfully.');
            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function restoresharepurchasetransaction($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $transaction = SharePurchaseTransaction::findOrFail($id);

            $transaction->update([
                'Status' => 'Active',
            ]);

            Alert::success('Success', 'Share purchase transaction restored successfully.');
            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Helper function to generate sequential TransactionRefNo in format: IFLSG/SHR/0001
     */
    private function generateTransactionRefNo()
    {
        $prefix = 'IFLSG/SHR/';

        $latestTransaction = SharePurchaseTransaction::where('TransactionRefNo', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestTransaction) {
            return $prefix . '0001';
        }

        $lastNumber = (int) substr($latestTransaction->TransactionRefNo, strlen($prefix));
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $nextNumber;
    }


    /*
    |--------------------------------------------------------------------------
    | STAGE 1: VERIFICATION METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Display transactions pending verification.
     */
    public function unverifiedsharepurchasetransactions()
    {
        try {
            $transactions = SharePurchaseTransaction::with([
                    'member',
                    'shareOffering',
                    'shareType',
                    'company',
                    'branch',
                    'user'
                ])
                ->where('Status', 'Active')
                ->where('verification_status', 'Pending')
                ->whereIn('transaction_status', ['Draft', 'Pending Verification'])
                ->latest()
                ->get();

            return view('in.shares.sharepurchasetransactions.unverifiedsharepurchasetransactions', compact('transactions'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    public function verifysharepurchasetransaction(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'verification_status'  => 'required|in:Verified,Rejected',
            'verification_remarks' => 'nullable|string',
        ]);

        try {
            $transaction = SharePurchaseTransaction::findOrFail($id);

            if ($transaction->verification_status !== 'Pending') {
                Alert::warning('Notice', 'This transaction has already been processed for verification.');
                return redirect()->back();
            }

            $isVerified = $request->verification_status === 'Verified';

            $transaction->update([
                'verification_status'  => $request->verification_status,
                'verified_by'           => auth()->id(),
                'verified_at'           => now(),
                'verification_remarks' => $request->verification_remarks,
                'transaction_status'   => $isVerified ? 'Verified' : 'Rejected',
            ]);

            Alert::success(
                'Success', 
                $isVerified ? 'Transaction verified successfully.' : 'Transaction rejected at verification.'
            );

            return redirect()->back();

        } catch (\Throwable $th) {
            \Log::error('Error verifying share transaction', [
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
     * Display verified transactions pending managerial approval.
     */
    public function pendingapprovalsharepurchasetransactions()
    {
        try {
            $transactions = SharePurchaseTransaction::with([
                    'member',
                    'shareOffering',
                    'shareType',
                    'verifier',
                    'company',
                    'branch'
                ])
                ->where('Status', 'Active')
                ->where('verification_status', 'Verified')
                ->where('approval_status', 'Pending')
                ->where('transaction_status', 'Verified')
                ->latest()
                ->get();

            return view('in.shares.sharepurchasetransactions.pendingapprovalsharepurchasetransactions', compact('transactions'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }
    public function approvesharepurchasetransaction(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'approval_status'  => 'required|in:Approved,Rejected',
            'approval_remarks' => 'nullable|string',
        ]);

        try {
            $transaction = SharePurchaseTransaction::findOrFail($id);

            if ($transaction->verification_status !== 'Verified') {
                Alert::error('Action Denied', 'Transaction must be verified before approval.');
                return redirect()->back();
            }

            if ($transaction->approval_status !== 'Pending') {
                Alert::warning('Notice', 'This transaction has already been processed for approval.');
                return redirect()->back();
            }

            $isApproved = $request->approval_status === 'Approved';

            $transaction->update([
                'approval_status'  => $request->approval_status,
                'approved_by'       => auth()->id(),
                'approved_at'       => now(),
                'approval_remarks' => $request->approval_remarks,
                'transaction_status' => $isApproved ? 'Approved' : 'Rejected',
            ]);

            Alert::success(
                'Success', 
                $isApproved ? 'Transaction approved successfully.' : 'Transaction rejected at approval stage.'
            );

            return redirect()->back();

        } catch (\Throwable $th) {
            \Log::error('Error approving share transaction', [
                'user_id' => Auth::id(), 'method' => __METHOD__, 'message' => $th->getMessage(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STAGE 3: ACCOUNTING POSTING METHODS
    |--------------------------------------------------------------------------
    */
    public function pendingpostingsharepurchasetransactions()
    {
        try {
            $transactions = SharePurchaseTransaction::with([
                    'member',
                    'shareOffering',
                    'shareType',
                    'verifier',
                    'approver',
                    'company',
                    'branch'
                ])
                ->where('Status', 'Active')
                ->where('approval_status', 'Approved')
                ->where('posting_status', 'Pending')
                ->where('transaction_status', 'Approved')
                ->latest()
                ->get();

            return view('in.shares.sharepurchasetransactions.pendingpostingsharepurchasetransactions', compact('transactions'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    public function postsharepurchasetransaction(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'cash_account_code'               => 'required|string|max:30',
            'share_capital_account_code'     => 'required|string|max:30',
            'membership_fee_account_code'     => 'nullable|string|max:30',
            'social_contribution_account_code'=> 'nullable|string|max:30',
            'posting_reference'               => 'nullable|string|max:100',
        ]);

        try {
            $transaction = SharePurchaseTransaction::findOrFail($id);

            if ($transaction->approval_status !== 'Approved') {
                Alert::error('Action Denied', 'Transaction must be approved before accounting posting.');
                return redirect()->back();
            }

            if ($transaction->posting_status === 'Posted') {
                Alert::warning('Notice', 'This transaction is already posted to GL.');
                return redirect()->back();
            }

            DB::beginTransaction();

            $journalNo = 'JNL/' . date('Ym') . '/' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT);

            // Update transaction GL mapping and status
            $transaction->update([
                'posting_status'                   => 'Posted',
                'posted_by'                        => auth()->id(),
                'posted_at'                        => now(),
                'posting_reference'               => $request->posting_reference ?? $transaction->TransactionRefNo,
                'journal_no'                       => $journalNo,
                'cash_account_code'               => $request->cash_account_code,
                'share_capital_account_code'     => $request->share_capital_account_code,
                'membership_fee_account_code'     => $request->membership_fee_account_code,
                'social_contribution_account_code'=> $request->social_contribution_account_code,
                'transaction_status'               => 'Posted',
            ]);

            DB::commit();

            Alert::success('Success', "Transaction posted to GL with Journal Ref: {$journalNo}");

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            \Log::error('Error posting share transaction to GL', [
                'user_id' => Auth::id(), 'method' => __METHOD__, 'message' => $th->getMessage(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STAGE 4: SHARE CERTIFICATE GENERATION METHOD
    |--------------------------------------------------------------------------
    */
/**
     * Display posted transactions ready for share certificate generation.
     */
    public function pendingcertificatesharepurchasetransactions()
    {
        try {
            $transactions = SharePurchaseTransaction::with([
                    'member',
                    'shareOffering',
                    'shareType',
                    'poster',
                    'company',
                    'branch'
                ])
                ->where('Status', 'Active')
                ->where('posting_status', 'Posted')
                ->where('certificate_status', 'Not Generated')
                ->where('transaction_status', 'Posted')
                ->latest()
                ->get();

            return view('in.shares.sharepurchasetransactions.pendingcertificatesharepurchasetransactions', compact('transactions'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }
/*
    |--------------------------------------------------------------------------
    | STAGE 4: SHARE CERTIFICATE GENERATION METHOD
    |--------------------------------------------------------------------------
    */

    public function generatecertificate(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        try {
            $transaction = SharePurchaseTransaction::with(['member', 'shareType', 'shareOffering'])
                ->findOrFail($id);

            if ($transaction->posting_status !== 'Posted') {
                Alert::error('Action Denied', 'Share purchase must be posted before generating a certificate.');
                return redirect()->back();
            }

            if ($transaction->certificate_status === 'Generated') {
                Alert::warning('Notice', 'Certificate has already been generated for this transaction.');
                return redirect()->back();
            }

            DB::beginTransaction();

            // Auto-generate reference numbers
            $certificateRefNo  = $this->generateCertificateRefNo();
            $certificateNumber = 'CERT/' . date('Y') . '/' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT);

            // Create record matching ShareCertificate model fields
            $certificate = ShareCertificate::create([
                'CertificateRefNo'           => $certificateRefNo,
                'CertificateNumber'          => $certificateNumber,
                'member_id'                  => $transaction->member_id,
                'share_offering_id'          => $transaction->share_offering_id,
                'share_type_id'              => $transaction->share_type_id,
                'SharesQuantity'             => $transaction->SharesQuantity,
                'IssueDate'                  => now()->format('Y-m-d'),
                'RevocationDate'             => null,
                'RevocationReason'           => null,
                'replaced_by_certificate_id' => null,
                'CertificateStatus'          => 'Active',

                // System & Organization Audit Fields
                'company_id'                 => $transaction->company_id,
                'branch_id'                  => $transaction->branch_id,
                'User_id'                    => auth()->id(),
                'Status'                     => 'Active',
                'AuditingStatus'             => 'Pending',
                'ReportStatus'               => 'Pending',
            ]);

            // Update transaction certificate stage status
            $transaction->update([
                'certificate_status'       => 'Generated',
                'certificate_generated_at' => now(),
                'certificate_generated_by' => auth()->id(),
                'certificate_id'           => $certificate->id,
                'transaction_status'       => 'Completed',
            ]);

            DB::commit();

            Alert::success('Success', "Share Certificate #{$certificateNumber} generated successfully.");

            return redirect()->back();

        } catch (\Throwable $th) {
            DB::rollBack();

            \Log::error('Error generating share certificate', [
                'user_id' => Auth::id(), 'method' => __METHOD__, 'message' => $th->getMessage(), 'trace' => $th->getTraceAsString(),
            ]);
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }

    /**
     * Helper function to generate sequential CertificateRefNo in format: IFLSG/CRT/0001
     */
    private function generateCertificateRefNo()
    {
        $prefix = 'IFLSG/CRT/';

        $latestCertificate = ShareCertificate::where('CertificateRefNo', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestCertificate) {
            return $prefix . '0001';
        }

        $lastNumber = (int) substr($latestCertificate->CertificateRefNo, strlen($prefix));
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $nextNumber;
    }

    /**
     * Display completed transactions with generated certificates.
     */
    public function completedsharepurchasetransactions()
    {
        try {
            $transactions = SharePurchaseTransaction::with([
                    'member',
                    'shareOffering',
                    'shareType',
                    'certificate',
                    'certificateGenerator',
                    'company',
                    'branch'
                ])
                ->where('Status', 'Active')
                ->where('transaction_status', 'Completed')
                ->latest()
                ->get();

            return view('in.shares.sharepurchasetransactions.completedsharepurchasetransactions', compact('transactions'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . auth()->user()->name, 'Technical error exists, please contact Technical Support.');
            return back();
        }
    }
    
    public function sharetypes()
    {
        try {

            $shareTypes = ShareType::with([
                    'company',
                    'branch',
                    'user'
                ])
                ->where('Status', 'Active')
                ->latest()
                ->get();
            $companies = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches  = Branch::where('Status', 'Active')->orderBy('branch_name')->get();
            return view('in.shares.sharetypes.sharetypes', compact('shareTypes', 'companies', 'branches'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . ' ' . auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storesharetypes(Request $request)
    {
        $request->validate([
            'TypeCode'         => 'required|string|max:50|unique:share_types,TypeCode',
            'TypeName'         => 'required|string|max:200',
            'Description'      => 'nullable|string',
            'NominalValue'     => 'required|numeric|min:0',
            'DividendEligible' => 'nullable|boolean',
            'company_id'       => 'nullable|exists:companies,id',
            'branch_id'        => 'nullable|exists:branchies,id',
        ]);

        try {

            $typeRefNo = $this->generateTypeRefNo();

            ShareType::create([
                'TypeRefNo'        => $typeRefNo,
                'TypeCode'         => $request->TypeCode,
                'TypeName'         => $request->TypeName,
                'Description'      => $request->Description,
                'NominalValue'     => $request->NominalValue ?? 0.00,
                'DividendEligible' => $request->has('DividendEligible') ? 1 : 0,
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

    public function viewsharetypes($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $shareType = ShareType::with([
                    'company',
                    'branch',
                    'user'
                ])
                ->findOrFail($id);

            return view('in.shares.sharetypes.viewsharetypes', compact('shareType'));

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

    public function editsharetypes($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $shareType = ShareType::with([
                    'company',
                    'branch',
                    'user'
                ])
                ->findOrFail($id);

            $companies = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches  = Branch::where('Status', 'Active')->orderBy('branch_name')->get();

            return view('in.shares.sharetypes.editsharetypes', compact(
                'shareType',
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

    public function updatesharetypes(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'TypeCode'         => 'required|string|max:50|unique:share_types,TypeCode,' . $id,
            'TypeName'         => 'required|string|max:200',
            'Description'      => 'nullable|string',
            'NominalValue'     => 'required|numeric|min:0',
            'DividendEligible' => 'nullable|boolean',
            'company_id'       => 'nullable|exists:companies,id',
            'branch_id'        => 'nullable|exists:branchies,id',
        ]);

        try {

            $shareType = ShareType::findOrFail($id);

            $shareType->update([
                'TypeCode'         => $request->TypeCode,
                'TypeName'         => $request->TypeName,
                'Description'      => $request->Description,
                'NominalValue'     => $request->NominalValue ?? $shareType->NominalValue,
                'DividendEligible' => $request->has('DividendEligible') ? $request->DividendEligible : $shareType->DividendEligible,
                'company_id'       => $request->company_id,
                'branch_id'        => $request->branch_id,
            ]);

            Alert::success('Success', 'Share type updated successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back()->withInput();

        }
    }

    public function deletesharetypes($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $shareType = ShareType::findOrFail($id);

            $shareType->update([
                'Status' => 'Deleted',
            ]);

            Alert::success('Success', 'Share type deleted successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    public function restoresharetypes($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $shareType = ShareType::findOrFail($id);

            $shareType->update([
                'Status' => 'Active',
            ]);

            Alert::success('Success', 'Share type restored successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    /**
     * Helper function to generate sequential TypeRefNo in format: IFLSG/SHR/0001
     */
    private function generateTypeRefNo()
    {
        $prefix = 'IFLSG/SHRT/';

        $latestShareType = ShareType::where('TypeRefNo', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestShareType) {
            return $prefix . '0001';
        }

        $lastNumber = (int) substr($latestShareType->TypeRefNo, strlen($prefix));
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $nextNumber;
    }



    // ShareOffering

    public function shareofferings()
    {
        try {

            $shareOfferings = ShareOffering::with([
                    'shareType',
                    'company',
                    'branch',
                    'user'
                ])
                ->where('Status', 'Active')
                ->latest()
                ->get();

            $shareTypes = ShareType::where('Status', 'Active')->orderBy('TypeName')->get();
            $companies  = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches   = Branch::where('Status', 'Active')->orderBy('branch_name')->get();

            return view('in.shares.shareofferings.shareofferings', compact('shareOfferings', 'shareTypes', 'companies', 'branches'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . ' ' . auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storeshareofferings(Request $request)
    {
        $request->validate([
            'OfferingName'        => 'required|string|max:200',
            'share_type_id'       => 'required|exists:share_types,id',
            'TotalShares'         => 'required|numeric|min:0',
            'PricePerShare'       => 'required|numeric|min:0',
            'MaxPercentPerMember' => 'nullable|numeric|min:0|max:100',
            'OfferingStartDate'   => 'required|date',
            'OfferingEndDate'     => 'required|date|after_or_equal:OfferingStartDate',
            'OfferingStatus'      => 'nullable|string|max:50',
            'company_id'          => 'nullable|exists:companies,id',
            'branch_id'           => 'nullable|exists:branchies,id',
        ]);

        try {

            $offeringRefNo = $this->generateOfferingRefNo();

            ShareOffering::create([
                'OfferingRefNo'       => $offeringRefNo,
                'OfferingName'        => $request->OfferingName,
                'share_type_id'       => $request->share_type_id,
                'TotalShares'         => $request->TotalShares ?? 0.00,
                'PricePerShare'       => $request->PricePerShare ?? 0.00,
                'MaxPercentPerMember' => $request->MaxPercentPerMember ?? 0.00,
                'OfferingStartDate'   => $request->OfferingStartDate,
                'OfferingEndDate'     => $request->OfferingEndDate,
                'OfferingStatus'      => $request->OfferingStatus ?? 'Open',
                'company_id'          => $request->company_id,
                'branch_id'           => $request->branch_id,
                'User_id'             => auth()->id(),
                'Status'              => 'Active',
                'AuditingStatus'      => 'Pending',
                'ReportStatus'        => 'Pending',
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

    public function viewshareofferings($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $shareOffering = ShareOffering::with([
                    'shareType',
                    'company',
                    'branch',
                    'user'
                ])
                ->findOrFail($id);

            return view('in.shares.shareofferings.viewshareofferings', compact('shareOffering'));

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

    public function editshareofferings($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $shareOffering = ShareOffering::with([
                    'shareType',
                    'company',
                    'branch',
                    'user'
                ])
                ->findOrFail($id);

            $shareTypes = ShareType::where('Status', 'Active')->orderBy('TypeName')->get();
            $companies  = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branches   = Branch::where('Status', 'Active')->orderBy('branch_name')->get();

            return view('in.shares.shareofferings.editshareofferings', compact(
                'shareOffering',
                'shareTypes',
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

    public function updateshareofferings(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'OfferingName'        => 'required|string|max:200',
            'share_type_id'       => 'required|exists:share_types,id',
            'TotalShares'         => 'required|numeric|min:0',
            'PricePerShare'       => 'required|numeric|min:0',
            'MaxPercentPerMember' => 'nullable|numeric|min:0|max:100',
            'OfferingStartDate'   => 'required|date',
            'OfferingEndDate'     => 'required|date|after_or_equal:OfferingStartDate',
            'OfferingStatus'      => 'nullable|string|max:50',
            'company_id'          => 'nullable|exists:companies,id',
            'branch_id'           => 'nullable|exists:branchies,id',
        ]);

        try {

            $shareOffering = ShareOffering::findOrFail($id);

            $shareOffering->update([
                'OfferingName'        => $request->OfferingName,
                'share_type_id'       => $request->share_type_id,
                'TotalShares'         => $request->TotalShares ?? $shareOffering->TotalShares,
                'PricePerShare'       => $request->PricePerShare ?? $shareOffering->PricePerShare,
                'MaxPercentPerMember' => $request->MaxPercentPerMember ?? $shareOffering->MaxPercentPerMember,
                'OfferingStartDate'   => $request->OfferingStartDate,
                'OfferingEndDate'     => $request->OfferingEndDate,
                'OfferingStatus'      => $request->OfferingStatus ?? $shareOffering->OfferingStatus,
                'company_id'          => $request->company_id,
                'branch_id'           => $request->branch_id,
            ]);

            Alert::success('Success', 'Share offering updated successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back()->withInput();

        }
    }

    public function deleteshareofferings($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $shareOffering = ShareOffering::findOrFail($id);

            $shareOffering->update([
                'Status' => 'Deleted',
            ]);

            Alert::success('Success', 'Share offering deleted successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    public function restoreshareofferings($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $shareOffering = ShareOffering::findOrFail($id);

            $shareOffering->update([
                'Status' => 'Active',
            ]);

            Alert::success('Success', 'Share offering restored successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    /**
     * Helper function to generate sequential OfferingRefNo in format: IFLSG/SHO/0001
     */
    private function generateOfferingRefNo()
    {
        $prefix = 'IFLSG/SHO/';

        $latestOffering = ShareOffering::where('OfferingRefNo', 'LIKE', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$latestOffering) {
            return $prefix . '0001';
        }

        $lastNumber = (int) substr($latestOffering->OfferingRefNo, strlen($prefix));
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $nextNumber;
    }
}