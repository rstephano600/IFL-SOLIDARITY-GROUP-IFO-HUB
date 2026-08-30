<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use App\Models\User;
use App\Models\MemberNotification;
use App\Models\ShareType;
use App\Models\ShareOffering;
use App\Models\SharePurchaseTransaction;
use App\Models\ShareCertificate;
use App\Models\ShareTransfer;
use App\Models\ShareDividendAllocation;
use App\Models\Company;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class MemberSelfInformationsController extends Controller
{
    public function myShares()
    {
        // try {

            $user = Auth::user();

            // Get the member associated with the logged-in user
            $member = $user->member;

            if (!$member) {
                Alert::error(
                    'Sorry! ' . Auth()->user()->name,
                    'Member information was not found.'
                );

                return redirect()->back();
            }

            // Fetch completed share purchase transactions
            $sharePurchases = SharePurchaseTransaction::with([
                    'shareType',
                    'certificate'
                ])
                ->where('member_id', $member->id)
                ->where('approval_status', 'Approved')
                ->latest('TransactionDate')
                ->get();

            // Total shares purchased
            $totalSharesPurchased = $sharePurchases->sum('SharesQuantity');

            // Total amount invested
            $totalShareInvestment = $sharePurchases->sum('TotalAmount');

            // Total share purchase transactions
            $totalPurchases = $sharePurchases->count();

            // Get share transfers
            $shareTransfers = ShareTransfer::where(function ($query) use ($member) {
                    $query->where('from_member_id', $member->id)
                        ->orWhere('to_member_id', $member->id);
                })
                ->latest('TransferDate')
                ->get();

            // Shares transferred OUT
            $sharesTransferredOut = $shareTransfers
                ->where('from_member_id', $member->id)
                ->where('Status', 'Completed')
                ->sum('shares_transferred');

            // Shares transferred IN
            $sharesTransferredIn = $shareTransfers
                ->where('to_member_id', $member->id)
                ->where('Status', 'Completed')
                ->sum('shares_transferred');

            // Current shareholding
            $currentShares = $totalSharesPurchased
                + $sharesTransferredIn
                - $sharesTransferredOut;

            // =====================================================
            // DIVIDENDS
            // =====================================================

            $dividendAllocations = ShareDividendAllocation::with([
                    'declaration'
                ])
                ->where('member_id', $member->id)
                ->latest()
                ->get();

            // Total dividends allocated
            $totalDividend = $dividendAllocations->sum('dividend_amount');

            // Paid dividends
            $paidDividend = $dividendAllocations
                ->where('payment_status', 'Paid')
                ->sum('dividend_amount');

            // Unpaid dividends
            $unpaidDividend = $dividendAllocations
                ->where('payment_status', '!=', 'Paid')
                ->sum('dividend_amount');

            // =====================================================
            // SHARE CERTIFICATES
            // =====================================================

            $certificates = ShareCertificate::where('member_id', $member->id)
                ->latest('RevocationDate')
                ->get();

            // =====================================================
            // RETURN VIEW
            // =====================================================

            return view('member.shares.myShares', compact(
                'member',
                'sharePurchases',
                'shareTransfers',
                'dividendAllocations',
                'certificates',

                'totalSharesPurchased',
                'totalShareInvestment',
                'totalPurchases',

                'sharesTransferredIn',
                'sharesTransferredOut',
                'currentShares',

                'totalDividend',
                'paidDividend',
                'unpaidDividend'
            ));

        // } catch (\Throwable $th) {

        //     \Log::error('My shares error: ' . $th->getMessage());

        //     Alert::error(
        //         'Sorry! ' . Auth()->user()->name,
        //         'Technical error exists, please contact Technichal for support Tel:+255657856790'
        //     );

        //     return redirect()->back();
        // }
    }
}
