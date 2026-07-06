<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use App\Models\PermissionUser;
use Illuminate\Http\Request;
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

class PermissionUserController extends Controller
{
    public function usersRole()
    {
        try{
        $users = User::where('Status', 'Active')->get();
        return view('in.permissions.usersRole', compact('users'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }
    public function assignRole($id)
    {
        try{
            $user = User::findOrFail($id);
            $permissions = Permission::where('Status', 'Active')->whereNot('slug', 'is-company-admin')->get();
            $assigned = PermissionUser::where('User_id', $id)->pluck('permission_id')->toArray();
            // Sort: Unassigned (0) first, Assigned (1) last
            $permissions = $permissions->sortBy(function ($permission) use ($assigned) {
                return in_array($permission->id, $assigned) ? 1 : 0;
            });
            return view('in.permissions.assignRole', compact('user', 'permissions', 'assigned'));
            } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }
    public function permissionsstore(Request $request)
    {
        try{
            $request->validate([
                'User_id' => 'required|exists:users,id',
                'permissions' => 'required|array',
                'permissions.*' => 'exists:permissions,id'
            ]);
            $userId = $request->User_id;
            $creatorId = auth()->id();

            foreach ($request->permissions as $permissionId) {
                PermissionUser::firstOrCreate([
                    'User_id' => $userId,
                    'permission_id' => $permissionId
                ], [
                    'Creater_id' => $creatorId,
                    'Status' => 'Active',
                    'duration' => 'Permanent'
                ]);
            }
            $user = User::findOrFail($userId);
            $username = $user->name;
            Alert::success('Success' . ' ' . Auth()->user()->name, 'You\'ve Assigned Permissions Access for ' . $username . ' Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function permissionsremove(Request $request)
    {
        try{
        $request->validate([
            'User_id' => 'required|exists:users,id',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        PermissionUser::where('User_id', $request->User_id)
            ->whereIn('permission_id', $request->permissions)
            ->delete();
            $user = User::findOrFail($userId);
            $username = $user->name;
            Alert::success('Success' . ' ' . Auth()->user()->name, 'You\'ve Removed Permissions Access for ' . $username . ' Successfully');
        return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }
}