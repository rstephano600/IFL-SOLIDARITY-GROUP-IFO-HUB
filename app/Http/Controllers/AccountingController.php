<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\AccountBusiness;
use App\Models\AccountCountry;
use App\Models\AccountFifthBranch;
use App\Models\AccountFirstBranch;
use App\Models\AccountFourthBranch;
use App\Models\AccountRoot;
use App\Models\AccountSecondBranch;
use App\Models\AccountSixthBranch;
use App\Models\AccountThirdBranch;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;

class AccountingController extends Controller
{
    public function accountCountry()
    {
        try{
        $data = AccountCountry::where('Status', 'Active')->get();
        return view('in.accounting.accountCountry', compact('data'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storeaccountCountry(Request $request)
    {
        $request->validate([
            'CountryCode' => 'required|string|max:5|unique:account_countries,CountryCode',
            'CountryName' => 'required|string|max:255',
        ]);
        try {
            AccountCountry::create([
                'CountryCode' => strtoupper($request->CountryCode),
                'CountryName' => $request->CountryName,
                'User_id'     => Auth::id(),
                'Status'      => 'Active'
            ]);
            Alert::success('Success ' . ' ' . Auth()->user()->name, 'You\'ve Registered Country Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function editaccountCountry($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = AccountCountry::findOrFail($id);
            return view('in.accounting.editaccountCountry', compact('data'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function updateaccountCountry(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $request->validate([
            'CountryCode' => 'required|string|max:5|unique:account_countries,CountryCode,'.$id,
            'CountryName' => 'required|string|max:255',
        ]);
        try {
            $country = AccountCountry::findOrFail($id);
            $country->update([
                'CountryCode' => strtoupper($request->CountryCode),
                'CountryName' => $request->CountryName,
            ]);
            Alert::success('Success' . ' ' . Auth()->user()->name, 'You\'ve Updated Country details Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function destroyaccountCountry($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $country = AccountCountry::findOrFail($id);
            $country->update(['Status' => 'Deleted']);
            Alert::success('Success' . ' ' .  Auth()->user()->name, 'You\'veremoved  Country successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function deletedaccountCountry()
    {
        try{
        $data = AccountCountry::where('Status', 'Deleted')->get();
        return view('in.accounting.deletedaccountCountry', compact('data'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function accountBusiness()
    {
        try{
            $data = AccountBusiness::where('Status', 'Active')->get();
            $countries = AccountCountry::where('Status', 'Active')->get();
            return view('in.accounting.accountBusiness', compact('data', 'countries'));
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function storeaccountBusiness(Request $request)
    {
        $request->validate([
            'Country_id'   => 'required|exists:account_countries,id',
            'BusinessCode' => 'required|string|max:20|unique:account_businesses,BusinessCode',
            'BusinessName' => 'required|string|max:255',
        ]);

        try {

            AccountBusiness::create([
                'Country_id'   => $request->Country_id,
                'BusinessCode' => strtoupper($request->BusinessCode),
                'BusinessName' => $request->BusinessName,
                'User_id'      => Auth::id(),
                'Status'       => 'Active',
            ]);

            Alert::success( 'Success ' . ' ' .  Auth()->user()->name, 'You\'ve Registered Business Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function editaccountBusiness($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = AccountBusiness::findOrFail($id);
            $countries = AccountCountry::where('Status', 'Active')->get();
            return view( 'in.accounting.editaccountBusiness', compact('data', 'countries'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function updateaccountBusiness(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $request->validate([
            'Country_id'   => 'required|exists:account_countries,id',
            'BusinessCode' => 'required|string|max:20|unique:account_businesses,BusinessCode,' . $id,
            'BusinessName' => 'required|string|max:255',
        ]);

        try {
            $business = AccountBusiness::findOrFail($id);
            $business->update([
                'Country_id'   => $request->Country_id,
                'BusinessCode' => strtoupper($request->BusinessCode),
                'BusinessName' => $request->BusinessName,
            ]);
            Alert::success( 'Success ' . ' ' .  Auth()->user()->name, 'You\'ve Updated Business details Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function destroyaccountBusiness($id)
    {
        try {

            $id = Crypt::decrypt($id);
            $business = AccountBusiness::findOrFail($id);
            $business->update([
                'Status' => 'Deleted'
            ]);
            Alert::success( 'Success ' . ' ' .  Auth()->user()->name, 'You\'ve removed Business successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function accountRoot()
    {
        try{
            $data = AccountRoot::where('Status', 'Active')->get();
            $countries = AccountCountry::where('Status', 'Active')->get();
            $business = AccountBusiness::where('Status', 'Active')->get();
            return view('in.accounting.accountRoot', compact('data', 'business'));
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function storeaccountRoot(Request $request)
    {
        $request->validate([
            'AccountCode' => 'required|string|max:20|unique:account_roots,AccountCode',
            'AccountName' => 'required|string|max:255',
        ]);

        try {
            AccountRoot::create([
                'AccountCode' => strtoupper($request->AccountCode),
                'AccountName' => $request->AccountName,
                'User_id'      => Auth::id(),
                'Status'       => 'Active',
            ]);
            Alert::success( 'Success ' . ' ' .  Auth()->user()->name, 'You\'ve Registered Account Name Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function editaccountRoot($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = AccountRoot::findOrFail($id);
            $countries = AccountCountry::where('Status', 'Active')->get();
            return view( 'in.accounting.editaccountRoot', compact('data', 'countries'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function updateaccountRoot(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $request->validate([
            'AccountCode' => 'required|string|max:20|unique:account_roots,AccountCode,' . $id,
            'AccountName' => 'required|string|max:255',
        ]);

        try {
            $business = AccountRoot::findOrFail($id);
            $business->update([
                'AccountCode' => strtoupper($request->AccountCode),
                'AccountName' => $request->AccountName,
            ]);
            Alert::success( 'Success ' . ' ' .  Auth()->user()->name, 'You\'ve Updated Account Name details Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function destroyaccountRoot($id)
    {
        try {

            $id = Crypt::decrypt($id);
            $business = AccountRoot::findOrFail($id);
            $business->update([
                'Status' => 'Deleted'
            ]);
            Alert::success( 'Success ' . ' ' .  Auth()->user()->name, 'You\'ve removed Account Name successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }



    public function accountFirstBranch()
    {
        try{
            $data = AccountFirstBranch::where('Status', 'Active')->get();
            $accountroot = AccountRoot::where('Status', 'Active')->get();
            $business = AccountBusiness::where('Status', 'Active')->get();
            return view('in.accounting.accountFirstBranch', compact('data', 'accountroot'));
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function storeaccountFirstBranch(Request $request)
    {
        $request->validate([
            'AccountRoot_id' => 'required|exists:account_roots,id',
            'FirstAccountCode' => 'required|string|max:20|unique:account_first_branches,FirstAccountCode',
            'FirstAccountName' => 'required|string|max:255',
        ]);

        try {
            AccountFirstBranch::create([
                'AccountRoot_id' => $request->AccountRoot_id,
                'FirstAccountCode' => strtoupper($request->FirstAccountCode),
                'FirstAccountName' => $request->FirstAccountName,
                'User_id'      => Auth::id(),
                'Status'       => 'Active',
            ]);
            Alert::success( 'Success ' . ' ' .  Auth()->user()->name, 'You\'ve Registered Account Name Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function editaccountFirstBranch($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = AccountFirstBranch::findOrFail($id);
            $accountroot = AccountRoot::where('Status', 'Active')->get();
            $countries = AccountCountry::where('Status', 'Active')->get();
            return view( 'in.accounting.editaccountFirstBranch', compact('data', 'accountroot'));
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function updateaccountFirstBranch(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $request->validate([
            'AccountRoot_id' => 'required|exists:account_roots,id',
            'FirstAccountName' => 'required|string|max:255',
            'FirstAccountCode' => 'required|string|max:20|unique:account_first_branches,FirstAccountCode,' . $id,
        ]);

        try {
            $business = AccountFirstBranch::findOrFail($id);
            $business->update([
                'AccountRoot_id' => $request->AccountRoot_id,
                'FirstAccountCode' => strtoupper($request->FirstAccountCode),
                'FirstAccountName' => $request->FirstAccountName,
            ]);
            Alert::success( 'Success ' . ' ' .  Auth()->user()->name, 'You\'ve Updated Account Name details Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function destroyaccountFirstBranch($id)
    {
        try {

            $id = Crypt::decrypt($id);
            $business = AccountFirstBranch::findOrFail($id);
            $business->update([
                'Status' => 'Deleted'
            ]);
            Alert::success( 'Success ' . ' ' .  Auth()->user()->name, 'You\'ve removed Account Name successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' .  Auth()->user()->name, ' Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }


    public function accountSecondBranch()
    {
        try{
            $data = AccountSecondBranch::where('Status', 'Active')->get();
            $accountroot = AccountRoot::where('Status', 'Active')->get();
            $accountfirst = AccountFirstBranch::where('Status', 'Active')->get();
            $business = AccountBusiness::where('Status', 'Active')->get();
            return view('in.accounting.accountSecondBranch', compact('data', 'accountfirst'));
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' .  Auth()->user()->name, ' Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function storeaccountSecondBranch(Request $request)
    {
        $request->validate([
            'FirstRoot_id' => 'required|exists:account_first_branches,id',
            'SecondAccountCode' => 'required|string|max:20|unique:account_second_branches,SecondAccountCode',
            'SecondAccountName' => 'required|string|max:255',
        ]);

        try {
            AccountSecondBranch::create([
                'FirstRoot_id' => $request->FirstRoot_id,
                'SecondAccountCode' => strtoupper($request->SecondAccountCode),
                'SecondAccountName' => $request->SecondAccountName,
                'User_id'      => Auth::id(),
                'Status'       => 'Active',
            ]);
            Alert::success( 'Success ' . ' ' .  Auth()->user()->name, 'You\'ve Registered Account Name Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function editaccountSecondBranch($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = AccountSecondBranch::findOrFail($id);
            $accountroot = AccountRoot::where('Status', 'Active')->get();
            $accountfirst = AccountFirstBranch::where('Status', 'Active')->get();
            $countries = AccountCountry::where('Status', 'Active')->get();
            return view( 'in.accounting.editaccountSecondBranch', compact('data', 'accountfirst'));
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function updateaccountSecondBranch(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $request->validate([
            'FirstRoot_id' => 'required|exists:account_first_branches,id',
            'SecondAccountName' => 'required|string|max:255',
            'SecondAccountCode' => 'required|string|max:20|unique:account_second_branches,SecondAccountCode,' . $id,
        ]);

        try {
            $business = AccountSecondBranch::findOrFail($id);
            $business->update([
                'FirstRoot_id' => $request->FirstRoot_id,
                'SecondAccountCode' => strtoupper($request->SecondAccountCode),
                'SecondAccountName' => $request->SecondAccountName,
            ]);
            Alert::success( 'Success ' . ' ' .  Auth()->user()->name, 'You\'ve Updated Account Name details Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function destroyaccountSecondBranch($id)
    {
        try {

            $id = Crypt::decrypt($id);
            $business = AccountSecondBranch::findOrFail($id);
            $business->update([
                'Status' => 'Deleted'
            ]);
            Alert::success( 'Success ' . ' ' .  Auth()->user()->name, 'You\'ve removed Account Name successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );
            return back();
        }
    }

}
