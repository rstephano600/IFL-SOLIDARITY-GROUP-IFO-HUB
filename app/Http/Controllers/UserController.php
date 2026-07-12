<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\AccountBusiness;
use App\Models\AccountCountry;
use App\Models\AccountFifthGroupBranch;
use App\Models\AccountFirstBranch;
use App\Models\AccountFourthCenterBranch;
use App\Models\AccountRoot;
use App\Models\AccountSecondBranch;
use App\Models\AccountSixthMemberBranch;
use App\Models\AccountThirdBranch;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    public function systemUsers()
    {
        try{
        $data = User::where('Status', 'Active')->get();
        $roles = User::getRoles();
        return view('in.users.systemUsers', compact('data','roles'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storesystemUsers(Request $request)
    {
        try{
            $validated = $request->validate([
                'FirstName' => 'required|string|max:50',
                'MiddleName' => 'nullable|string|max:50',
                'LastName' => 'required|string|max:50',
                'email' => 'required|email|unique:users',
                'phone' => 'nullable|string|max:20',
                'Role' => ['required', Rule::in(User::getRoles())],
            ]);

            $userCount = User::count();
            $no = $userCount + 1;
            $month     = date('Y');
            $name = $validated['LastName'] . ',' . ' ' . $validated['FirstName'] . ' ' .  $validated['MiddleName'];
            $FName = strtoupper(substr($validated['FirstName'], 0,1));
            $MName = strtoupper(substr($validated['MiddleName'], 0,1));
            $LName = strtoupper(substr($validated['LastName'], 0,1));
            $Name = $FName.$MName.$LName;
            $username = 'IFLSG/'. $Name . '/' . $month. '/00' . $no;

            $validated['name'] = $name;
            $validated['username'] = $username;
            $validated['password'] = Hash::make('AiBif123456');
            $validated['User_id'] = auth()->id();

            User::create($validated);

            Alert::success('Success ' . ' ' . Auth()->user()->name, 'You\'ve Registered User ' . $name . ' created successfully.');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function editsystemUsers($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = User::findOrFail($id);
            $roles = User::getRoles();
            return view('in.users.editsystemUsers', compact('data', 'roles'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }
    public function updatesystemUsers(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $request->validate([
            'FirstName' => 'required|string|max:50',
            'MiddleName' => 'nullable|string|max:50',
            'LastName' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'Role' => ['required', Rule::in(User::getRoles())],
        ]);
        $name = $request->LastName . ',' . ' ' . $request->FirstName . ' ' .  $request->MiddleName;
        try {
            $country = User::findOrFail($id);
            $country->update([
                'name' => $name,
                'FirstName' => $request->FirstName,
                'MiddleName' => $request->MiddleName,
                'LastName' => $request->LastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'Role' => $request->Role,
            ]);
            Alert::success('Success' . ' ' . Auth()->user()->name, 'You\'ve Updated User details Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }
    public function destroysystemUsers($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $country = user::findOrFail($id);
            $country->update(['Status' => 'Deleted']);
            Alert::success('Success' . ' ' .  Auth()->user()->name, 'You\'veremoved  User successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function resetPassword(Request $request, User $user)
    {
       try {     
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password reset successfully.');
        Alert::success('Success' . ' ' .  Auth()->user()->name, 'Password reset successfully');
        return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }
    
}
