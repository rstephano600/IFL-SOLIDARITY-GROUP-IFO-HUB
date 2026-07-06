<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Log; // Use the Log facade for robust error handling

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile information.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // Eager load the employee relationship for the view
        $user = Auth::user()->load('employee');
        
        // This assumes you have a profile view file named 'profile.show'
        return view('in.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the authenticated user's profile information.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        // Eager load the employee relationship for the view
        $user = Auth::user()->load('employee');
        
        // This assumes you have a profile view file named 'profile.edit'
        return view('in.profile.edit', compact('user'));
    }

    /**
     * Update the authenticated user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Define Validation Rules
        $request->validate([
            // User Table fields (Validation ensures uniqueness, ignoring current user)
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            
            // Employee Table fields (Personal details)
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
            'date_of_birth' => ['nullable', 'date'],
            'marital_status' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'nida' => ['nullable', 'string', 'max:50'],
        ]);

        // 2. Update User (Authentication) Details
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->save();

        // 3. Update Employee (Personal) Details
        // This assumes the User model has a defined 'employee' relationship (e.g., hasOne)
        if ($user->employee) {
            $user->employee->first_name = $request->input('first_name');
            $user->employee->last_name = $request->input('last_name');
            $user->employee->gender = $request->input('gender');
            $user->employee->date_of_birth = $request->input('date_of_birth');
            $user->employee->marital_status = $request->input('marital_status');
            $user->employee->address = $request->input('address');
            $user->employee->nida = $request->input('nida');

            // Set the updater ID from the currently authenticated user
            $user->employee->updated_by = $user->id; 

            $user->employee->save();
        } else {
            // Log an error if the authenticated user does not have a linked employee record.
            Log::error('Authenticated User ID ' . $user->id . ' attempted to update profile but has no linked employee record.');
            return redirect()->route('profile.show')->with('warning', 'Profile updated, but personal details could not be saved (missing employee record).');
        }

        // Redirect back to the profile page with a success message
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    public function editPassword()
    {
        return view('in.profile.password.edit');
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check current password validity
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update to the new password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile.show')->with('success', 'Password updated successfully!');
    }
    

}

