<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Employee;
use App\Models\NextOfKin;
use App\Models\Referee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers\LogActivity;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;


class EmployeeController extends Controller
{
    public function employeeinfo()
    {
        try{
        $data = Employee::where('Status', 'Active')->get();
        $roles = User::getRoles();
        return view('in.employee.employeeinfo', compact('data', 'roles'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storenewemployeeinfo(Request $request)
    {
        $validated = $request->validate([
            // User Information
            'FirstName' => 'required|string|max:50',
            'MiddleName' => 'nullable|string|max:50',
            'LastName' => 'required|string|max:50',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'Role' => ['required', Rule::in(User::getRoles())],
            'gender' => 'required|in:Male,Female',
            'Dob' => 'required|date|before:today',
            
            // Employee Information
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'nida' => 'nullable|string|unique:employees,nida|max:50',
            'tribe' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'education_level' => 'nullable|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'date_of_hire' => 'required|date',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'other_information' => 'nullable|string',
            'EmployeeID' => 'nullable|string|unique:employees,EmployeeID',
            'basic_salary' => 'nullable|string',
            
            // Next of Kin
            'nok_first_name' => 'nullable|string|max:255',
            'nok_last_name' => 'nullable|string|max:255',
            'nok_relationship' => 'nullable|string|max:255',
            'nok_gender' => 'nullable|in:Male,Female',
            'nok_email' => 'nullable|email|max:255',
            'nok_phone' => 'nullable|string|max:20',
            'nok_address' => 'nullable|string|max:500',
            'nok_other_informations' => 'nullable|string',
            
            // Referee
            'ref1_first_name' => 'nullable|string|max:255',
            'ref1_last_name' => 'nullable|string|max:255',
            'ref1_gender' => 'nullable|in:Male,Female',
            'ref1_email' => 'nullable|email|max:255',
            'ref1_phone' => 'nullable|string|max:20',
            'ref1_address' => 'nullable|string|max:500',
            'ref1_other_informations' => 'nullable|string',
            'ref1_occupation' => 'nullable|string',
            
        ]);

        // if ($validator->fails()) {
        //         dd($validator->errors()->all());
        //     }

        DB::beginTransaction();

        $userCount = User::count();
        $no = $userCount + 1;
        $month     = date('Y');
        $name = $validated['LastName'] . ',' . ' ' . $validated['FirstName'] . ' ' .  $validated['MiddleName'];
        $FName = strtoupper(substr($validated['FirstName'], 0,1));
        $MName = !empty($validated['MiddleName']) 
                ? strtoupper(substr($validated['MiddleName'], 0, 1)) 
                : '';
        $LName = strtoupper(substr($validated['LastName'], 0,1));
        $Name = $FName.$MName.$LName;
        $username = 'ArBif/'. $Name . '/' . $month. '/00' . $no;
        $profilePicturePath = null;
        $cvPath = null;
        try {
            $user = User::create([
                'username' => $username,
                'name' => $name,
                'FirstName' => $validated['FirstName'],
                'MiddleName' => $validated['MiddleName'],
                'LastName' => $validated['LastName'],
                'gender' => $validated['gender'],
                'Dob' => $validated['Dob'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make('AiBif123456'),
                'Role' => $validated['Role'],
                'User_id' => auth()->id(),
            ]);

            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')
                    ->store('employees/profile_pictures', 'public');
            }

            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')
                    ->store('employees/cvs', 'public');
            }
            // Create Employee
            $employee = Employee::create([
                'Employee_id' => $user->id,
                'EmployeeID' => $validated['EmployeeID'],
                'user_id' => auth()->id(),
                'marital_status' => $validated['marital_status'],
                'nida' => $validated['nida'],
                'tribe' => $validated['tribe'],
                'religion' => $validated['religion'],
                'address' => $validated['address'],
                'education_level' => $validated['education_level'],
                'position' => $validated['position'],
                'department' => $validated['department'],
                'date_of_hire' => $validated['date_of_hire'],
                'is_active' => true,
                'profile_picture' => $profilePicturePath,
                'cv' => $cvPath,
                'other_information' => $validated['other_information'],
                'created_by' => auth()->id(),
                'User_id' => auth()->id(),
            ]);

            // Create Next of Kin if provided
            if ($request->filled('nok_first_name')) {
                NextOfKin::create([
                    'employee_id' => $employee->id,
                    'first_name' => $validated['nok_first_name'],
                    'last_name' => $validated['nok_last_name'],
                    'gender' => $validated['nok_gender'],
                    'email' => $validated['nok_email'],
                    'phone' => $validated['nok_phone'],
                    'address' => $validated['nok_address'],
                    'other_informations' => $validated['nok_other_informations'],
                    'relationship' => $validated['nok_relationship'],
                    'User_id' => auth()->id(),
                ]);
            }

            // Create Referee 1 if provided
            if ($request->filled('ref1_first_name')) {
                Referee::create([
                    'employee_id' => $employee->id,
                    'first_name' => $validated['ref1_first_name'],
                    'last_name' => $validated['ref1_last_name'],
                    'gender' => $validated['ref1_gender'],
                    'email' => $validated['ref1_email'],
                    'phone' => $validated['ref1_phone'],
                    'address' => $validated['ref1_address'],
                    'other_informations' => $validated['ref1_other_informations'],
                    'occupation' => $validated['ref1_occupation'],
                    'User_id' => auth()->id(),
                ]);
            }
            DB::commit();
            Alert::success('Success ' . ' ' . Auth()->user()->name, 'You\'ve Registered added Employee' . $username . 'Successfully');
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();

            dd($th->getMessage());
            if ($profilePicturePath) {
                Storage::disk('public')->delete($profilePicturePath);
            }
            if ($cvPath) {
                Storage::disk('public')->delete($cvPath);
            }
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790' . $th->getMessage());
            return back();
        }
    }


    public function editemployeeinfo($id)
    {
        try {
            $employeeId = decrypt($id);
        } catch (DecryptException $e) {
            Alert::error('Sorry!', 'Invalid or expired link.');
            return back();
        }

        $employee = Employee::with(['employee', 'nextOfKin', 'referees'])->findOrFail($employeeId);
        $roles = User::getRoles();

        return view('in.employee.editemployeeinfo', compact('employee', 'roles'));
    }

public function updateemployeeinfo(Request $request, $id)
{
    try {
        $employeeId = decrypt($id);
    } catch (DecryptException $e) {
        Alert::error('Sorry!', 'Invalid or expired link.');
        return back();
    }

    $employee = Employee::findOrFail($employeeId);
    $employeeUser = $employee->employee;

    $validated = $request->validate([
        // User Information
        'FirstName' => 'required|string|max:50',
        'MiddleName' => 'nullable|string|max:50',
        'LastName' => 'required|string|max:50',
        'email' => 'required|email|unique:users,email,' . optional($employeeUser)->id,
        'phone' => 'nullable|string|max:20',
        'Role' => ['required', Rule::in(User::getRoles())],
        'gender' => 'required|in:Male,Female',
        'Dob' => 'required|date|before:today',

        // Employee Information
        'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
        'nida' => 'nullable|string|max:50|unique:employees,nida,' . $employee->id,
        'tribe' => 'nullable|string|max:100',
        'religion' => 'nullable|string|max:100',
        'address' => 'nullable|string|max:500',
        'education_level' => 'nullable|string|max:255',
        'position' => 'required|string|max:255',
        'department' => 'nullable|string|max:255',
        'date_of_hire' => 'required|date',
        'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        'cv' => 'nullable|mimes:pdf,doc,docx|max:5120',
        'other_information' => 'nullable|string',
        'EmployeeID' => 'nullable|string|max:255|unique:employees,EmployeeID,' . $employee->id,
        'basic_salary' => 'nullable|numeric',
        'weekly_allowance_amount' => 'nullable|numeric',
        'is_active' => 'nullable|boolean',

        // Next of Kin
        'nok_first_name' => 'nullable|string|max:255',
        'nok_last_name' => 'nullable|string|max:255',
        'nok_relationship' => 'nullable|string|max:255',
        'nok_gender' => 'nullable|in:Male,Female',
        'nok_email' => 'nullable|email|max:255',
        'nok_phone' => 'nullable|string|max:20',
        'nok_address' => 'nullable|string|max:500',
        'nok_other_informations' => 'nullable|string',

        // Referee
        'ref1_first_name' => 'nullable|string|max:255',
        'ref1_last_name' => 'nullable|string|max:255',
        'ref1_gender' => 'nullable|in:Male,Female',
        'ref1_email' => 'nullable|email|max:255',
        'ref1_phone' => 'nullable|string|max:20',
        'ref1_address' => 'nullable|string|max:500',
        'ref1_other_informations' => 'nullable|string',
        'ref1_occupation' => 'nullable|string',
    ]);

    DB::beginTransaction();

    $oldProfilePicture = $employee->profile_picture;
    $oldCv = $employee->cv;
    $newProfilePicturePath = null;
    $newCvPath = null;

    try {
        // Update the employee's own user account
        if ($employeeUser) {
            $employeeUser->update([
                'FirstName' => $validated['FirstName'],
                'MiddleName' => $validated['MiddleName'],
                'LastName' => $validated['LastName'],
                'name' => $validated['LastName'] . ', ' . $validated['FirstName'] . ' ' . $validated['MiddleName'],
                'gender' => $validated['gender'],
                'Dob' => $validated['Dob'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'Role' => $validated['Role'],
                'updated_by' => auth()->id(),
            ]);
        }

        if ($request->hasFile('profile_picture')) {
            $newProfilePicturePath = $request->file('profile_picture')
                ->store('employees/profile_pictures', 'public');
        }

        if ($request->hasFile('cv')) {
            $newCvPath = $request->file('cv')
                ->store('employees/cvs', 'public');
        }

        $employee->update([
            'EmployeeID' => $validated['EmployeeID'],
            'marital_status' => $validated['marital_status'],
            'nida' => $validated['nida'],
            'tribe' => $validated['tribe'],
            'religion' => $validated['religion'],
            'address' => $validated['address'],
            'education_level' => $validated['education_level'],
            'position' => $validated['position'],
            'department' => $validated['department'],
            'date_of_hire' => $validated['date_of_hire'],
            'is_active' => $request->boolean('is_active', $employee->is_active),
            'profile_picture' => $newProfilePicturePath ?? $employee->profile_picture,
            'cv' => $newCvPath ?? $employee->cv,
            'other_information' => $validated['other_information'],
            'basic_salary' => $validated['basic_salary'],
            'weekly_allowance_amount' => $validated['weekly_allowance_amount'],
            'updated_by' => auth()->id(),
        ]);

        // Next of Kin - update existing record or create new
        if ($request->filled('nok_first_name')) {
            NextOfKin::updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'first_name' => $validated['nok_first_name'],
                    'last_name' => $validated['nok_last_name'],
                    'gender' => $validated['nok_gender'],
                    'email' => $validated['nok_email'],
                    'phone' => $validated['nok_phone'],
                    'address' => $validated['nok_address'],
                    'other_informations' => $validated['nok_other_informations'],
                    'relationship' => $validated['nok_relationship'],
                    'User_id' => auth()->id(),
                ]
            );
        }

        // Referee - update existing record or create new
        if ($request->filled('ref1_first_name')) {
            Referee::updateOrCreate(
                ['employee_id' => $employee->id],
                [
                    'first_name' => $validated['ref1_first_name'],
                    'last_name' => $validated['ref1_last_name'],
                    'gender' => $validated['ref1_gender'],
                    'email' => $validated['ref1_email'],
                    'phone' => $validated['ref1_phone'],
                    'address' => $validated['ref1_address'],
                    'other_informations' => $validated['ref1_other_informations'],
                    'occupation' => $validated['ref1_occupation'],
                    'User_id' => auth()->id(),
                ]
            );
        }

        DB::commit();

        // Only remove old files once the DB transaction is safely committed
        if ($newProfilePicturePath && $oldProfilePicture) {
            Storage::disk('public')->delete($oldProfilePicture);
        }
        if ($newCvPath && $oldCv) {
            Storage::disk('public')->delete($oldCv);
        }

        Alert::success('Success ' . Auth()->user()->name, 'Employee information updated successfully');
        return back();
    } catch (\Throwable $th) {
        DB::rollBack();

        // Clean up any newly-uploaded files since the transaction failed
        if ($newProfilePicturePath) {
            Storage::disk('public')->delete($newProfilePicturePath);
        }
        if ($newCvPath) {
            Storage::disk('public')->delete($newCvPath);
        }

        Alert::error('Sorry! ' . Auth()->user()->name, 'Technical error exists, please contact Technical support Tel:+255657856790 ' . $th->getMessage());
        return back();
    }
}


    public function destroyemployeeinfo($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $country = Employee::findOrFail($id);
            $country->update(['Status' => 'Deleted']);
            Alert::success('Success' . ' ' .  Auth()->user()->name, 'You\'veremoved  Empployee successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }
}
