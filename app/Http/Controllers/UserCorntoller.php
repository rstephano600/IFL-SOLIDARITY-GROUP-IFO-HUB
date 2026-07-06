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


class UserContoller extends Controller
{
    public function systemUsers()
    {
        try{
        $data = User::where('Status', 'Active')->get();
        return view('in.admin.users.systemUsers', compact('data'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->where('Role', $role);
        }

        if ($status) {
            $query->where('Status', $status);
        }

        $users = $query->orderBy('id', 'desc')->paginate(10);

        $roles = User::getRoles();

        return view('in.admin.users.index', compact('users', 'roles', 'search', 'role', 'status'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = User::getRoles();
        return view('in.admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'nullable|email|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'role' => ['required', Rule::in(User::getRoles())],
            'password' => 'required|string|min:6|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['created_by'] = auth()->id();

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = User::getRoles();
        return view('in.admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(User::getRoles())],
            'status' => ['required', 'in:active,suspended,disabled'],
        ]);

        $validated['updated_by'] = auth()->id();

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Reset the user password.
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password reset successfully.');
    }

    /**
     * Lock or unlock the user account.
     */
    public function toggleLock(User $user)
    {
        if ($user->locked_until) {
            $user->unlockAccount();
            return back()->with('success', 'User account unlocked.');
        } else {
            $user->lockAccount();
            return back()->with('success', 'User account locked.');
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

}
