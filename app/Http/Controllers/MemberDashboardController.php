<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use App\Models\User;
use App\Models\MemberNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class MemberDashboardController extends Controller
{
    public function memberdashboard()
    {
        $member = Member::with([
            'memberCategory',
            'company',
            'branch',
            'memberUser'
        ])
        ->where('member_id', Auth::id())
        ->first();

        return view('in.member.dashboard.dashboard', compact('member'));
    }

    public function profile()
    {
        $userId = session('auth_user.id');

        $user = User::findOrFail($userId);

        $member = Member::with(['memberCategory', 'company', 'branch'])
            ->where('member_id', $userId)
            ->first();

        return view('in.member.profile.profile', compact('user', 'member'));
    }

    public function updateProfile(Request $request)
    {
        $userId = session('auth_user.id');

        $validated = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($userId)],
        ]);

        try {
            $user = User::findOrFail($userId);
            $user->update($validated);

            // Keep the session copy in sync, otherwise the header/sidebar
            // will keep showing stale data until next login.
            session([
                'auth_user.email' => $user->email,
            ]);

            Alert::success('Updated', 'Your contact details have been updated successfully.');

            return back();

        } catch (\Throwable $th) {
            \Log::error('Profile update failed', [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ]);

            Alert::error('Sorry!', 'We could not update your details. Please try again.');

            return back()->withInput();
        }
    }

    public function settings()
    {
        return view('in.member.setting.setting');
    }

    public function updatePassword(Request $request)
    {
        $userId = session('auth_user.id');

        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $user = User::findOrFail($userId);

            if (! Hash::check($validated['current_password'], $user->password)) {
                Alert::error('Incorrect password', 'Your current password is incorrect.');
                return back();
            }

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            Alert::success('Password updated', 'Your password has been changed successfully.');

            return back();

        } catch (\Throwable $th) {
            \Log::error('Password update failed', [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ]);

            Alert::error('Sorry!', 'We could not update your password. Please try again.');

            return back();
        }
    }



    public function memberNotifications()
    {
        $notifications = MemberNotification::forCurrentUser()
            ->latest()
            ->paginate(20);

        return view('in.member.notification.notification', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = MemberNotification::forCurrentUser()->findOrFail($id);
        $notification->update(['read_at' => now()]);

        return back();
    }
}