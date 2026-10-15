<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Employee;
use App\Models\NextOfKin;
use App\Models\Referee;
use App\Models\Company;
use App\Models\Branch;
use App\Models\SalaryCalcl;
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
        try {
            $data  = Employee::with('user')->where('Status', 'Active')->get();
            $companies = Company::where('Status', 'Active')->get();
            $branchies = Branch::where('Status', 'Active')->get();
            $roles = array_filter(
                User::getRoles(),
                fn ($role) => $role !== User::ROLE_SUPER_ADMIN
            );
            return view('in.employee.employeeinfo', compact('data', 'roles', 'companies', 'branchies'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . ' ' . Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storenewemployeeinfo(Request $request)
    {
        // 1. Validate incoming data
        $validated = $request->validate([
            // User Information
            'branch_id'      => 'required|integer',
            'FirstName'      => 'required|string|max:50',
            'MiddleName'     => 'nullable|string|max:50',
            'LastName'       => 'required|string|max:50',
            'email'          => 'required|email|unique:users',
            'phone'          => 'nullable|string|max:20',
            'Role'           => ['required', Rule::in(User::getRoles())],
            'gender'         => 'required|in:Male,Female',
            'Dob'            => 'required|date|before:today',

            // Employee Information
            'marital_status'    => 'nullable|in:Single,Married,Divorced,Widowed',
            'nida'              => 'nullable|string|unique:employees,nida|max:50',
            'tribe'             => 'nullable|string|max:100',
            'religion'          => 'nullable|string|max:100',
            'address'           => 'nullable|string|max:500',
            'education_level'   => 'nullable|string|max:255',
            'position'          => 'required|string|max:255',
            'department'        => 'nullable|string|max:255',
            'date_of_hire'      => 'required|date',
            'profile_picture'   => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'cv_attachment'     => 'nullable|mimes:pdf,doc,docx|max:5120',
            'other_information' => 'nullable|string',
            'EmployeeID'        => 'nullable|string|unique:employees,EmployeeID',
            'basic_salary'      => 'required|string',

            // Payment / Salary processing
            'bank_name'              => 'nullable|string|max:150',
            'bank_account_number'    => 'nullable|string|max:50',
            'bank_account_name'      => 'nullable|string|max:150',
            'bank_branch'            => 'nullable|string|max:150',
            'mobile_money_provider'  => 'nullable|in:mpesa,tigopesa,airtelmoney,halopesa,other',
            'mobile_money_number'    => 'nullable|string|max:20',
            'payment_method'         => 'nullable|in:bank,mobile_money,cash',
            'housing_allowance'      => 'nullable|numeric|min:0',
            'transport_allowance'    => 'nullable|numeric|min:0',
            'medical_allowance'      => 'nullable|numeric|min:0',
            'overtime_rate'          => 'nullable|numeric|min:0',

            // Tanzanian statutory / compliance
            'tin_number'          => 'nullable|string|max:30',
            'nssf_number'         => 'nullable|string|max:30',
            'pssss_number'        => 'nullable|string|max:30',
            'nhif_number'         => 'nullable|string|max:30',
            'wcf_number'          => 'nullable|string|max:30',
            'employment_type'     => 'nullable|in:permanent,contract,probation,casual',
            'contract_start_date' => 'nullable|date',
            'contract_end_date'   => 'nullable|date|after_or_equal:contract_start_date',

            // Employment structure
            'supervisor_id'      => 'nullable|integer|exists:employees,id',
            'termination_date'   => 'nullable|date',
            'termination_reason' => 'nullable|string|max:255',
            'leave_balance'      => 'nullable|numeric|min:0',
        ]);

        $branch = Branch::findOrFail($request->branch_id);
        $companyId = $branch->company_id;

        DB::beginTransaction();

        $userCount = User::count();
        $no = $userCount + 1;
        $month = date('Y');

        $name = $validated['LastName'] . ', ' . $validated['FirstName'] . ' ' . ($validated['MiddleName'] ?? '');
        $FName = strtoupper(substr($validated['FirstName'], 0, 1));
        $MName = !empty($validated['MiddleName']) ? strtoupper(substr($validated['MiddleName'], 0, 1)) : '';
        $LName = strtoupper(substr($validated['LastName'], 0, 1));
        $username = 'IFLSG/' . $FName . $MName . $LName . '/' . $month . '/00' . $no;

        $profilePicturePath = null;
        $cvPath = null;

        try {
            // Create User
            $user = User::create([
                'username'    => $username,
                'name'        => $name,
                'company_id'  => $companyId,
                'branch_id'   => $validated['branch_id'],
                'FirstName'   => $validated['FirstName'],
                'MiddleName'  => $validated['MiddleName'],
                'LastName'    => $validated['LastName'],
                'gender'      => $validated['gender'],
                'Dob'         => $validated['Dob'],
                'email'       => $validated['email'],
                'phone'       => $validated['phone'],
                'password'    => Hash::make('IFLSG@123456'),
                'Role'        => $validated['Role'],
                'User_id'     => auth()->id(),
            ]);

            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('employees/profile_pictures', 'public');
            }

            if ($request->hasFile('cv_attachment')) {
                $cvPath = $request->file('cv_attachment')->store('employees/cvs', 'public');
            }

            // Create Employee — Employee_id links to the User this employee record belongs to
            $employee = Employee::create([
                'Employee_id'       => $user->id,
                'EmployeeID'        => $validated['EmployeeID'],
                'User_id'           => auth()->id(),
                'marital_status'    => $validated['marital_status'],
                'nida'              => $validated['nida'],
                'tribe'             => $validated['tribe'],
                'religion'          => $validated['religion'],
                'address'           => $validated['address'],
                'education_level'   => $validated['education_level'],
                'position'          => $validated['position'],
                'department'        => $validated['department'],
                'date_of_hire'      => $validated['date_of_hire'],
                'basic_salary'      => $validated['basic_salary'],
                'is_active'         => true,
                'profile_picture'   => $profilePicturePath,
                'cv'                => $cvPath,
                'other_information' => $validated['other_information'],
                'created_by'        => auth()->id(),

                // Payment / Salary processing
                'bank_name'             => $validated['bank_name'] ?? null,
                'bank_account_number'   => $validated['bank_account_number'] ?? null,
                'bank_account_name'     => $validated['bank_account_name'] ?? null,
                'bank_branch'           => $validated['bank_branch'] ?? null,
                'mobile_money_provider' => $validated['mobile_money_provider'] ?? null,
                'mobile_money_number'   => $validated['mobile_money_number'] ?? null,
                'payment_method'        => $validated['payment_method'] ?? null,
                'housing_allowance'     => $validated['housing_allowance'] ?? null,
                'transport_allowance'   => $validated['transport_allowance'] ?? null,
                'medical_allowance'     => $validated['medical_allowance'] ?? null,
                'overtime_rate'         => $validated['overtime_rate'] ?? null,

                // Tanzanian statutory / compliance
                'tin_number'          => $validated['tin_number'] ?? null,
                'nssf_number'         => $validated['nssf_number'] ?? null,
                'pssss_number'        => $validated['pssss_number'] ?? null,
                'nhif_number'         => $validated['nhif_number'] ?? null,
                'wcf_number'          => $validated['wcf_number'] ?? null,
                'employment_type'     => $validated['employment_type'] ?? null,
                'contract_start_date' => $validated['contract_start_date'] ?? null,
                'contract_end_date'   => $validated['contract_end_date'] ?? null,

                // Employment structure
                'supervisor_id'      => $validated['supervisor_id'] ?? null,
                'termination_date'   => $validated['termination_date'] ?? null,
                'termination_reason' => $validated['termination_reason'] ?? null,
                'leave_balance'      => $validated['leave_balance'] ?? null,
            ]);

            if (
                    !SalaryCalcl::where('Paid_id', $employee->id)
                        ->where('Status', 'Active')
                        ->exists()
                ) {
                    SalaryCalcl::create([
                        'Paid_id'   => $employee->id,
                        'GrsAmount' => $employee->basic_salary,
                        'Status'    => 'Active',
                    ]);
                }
            DB::commit();
            Alert::success('Success', "You've registered Employee {$username} successfully");
            return back();

        } catch (\Throwable $th) {
            DB::rollBack();
            if ($profilePicturePath) Storage::disk('public')->delete($profilePicturePath);
            if ($cvPath) Storage::disk('public')->delete($cvPath);

            // dd($th->getMessage(), $th->getLine(), $th->getFile());
            Alert::error('Sorry! ' . ' ' . Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }


    // EmployeeController@editemployeeinfo — add this alongside the others
    public function editemployeeinfo($id)
    {
        try {
            $employee    = Employee::with('user')->findOrFail(decrypt($id));
            $branchies = Branch::where('Status', 'Active')->get();
            $roles       = User::getRoles();
            $supervisors = Employee::where('Status', 'Active')
                                ->where('id', '!=', $employee->id)
                                ->with('user')
                                ->get();

            return view('in.employee.editemployeeinfo', compact('employee', 'branchies', 'roles', 'supervisors'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . ' ' . Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    /**
     * Update employee + linked user info.
     * Employee_id on the employees table is the FK to users.id.
     */
    public function updateemployeeinfo(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $user     = User::findOrFail($employee->Employee_id);

        $validated = $request->validate([
            // User Information
            'branch_id'  => 'required|integer',
            'FirstName'  => 'required|string|max:50',
            'MiddleName' => 'nullable|string|max:50',
            'LastName'   => 'required|string|max:50',
            'email'      => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'      => 'nullable|string|max:20',
            'Role'       => ['required', Rule::in(User::getRoles())],
            'gender'     => 'required|in:Male,Female',
            'Dob'        => 'required|date|before:today',

            // Employee Information
            'marital_status'    => 'nullable|in:Single,Married,Divorced,Widowed',
            'nida'              => ['nullable', 'string', 'max:50', Rule::unique('employees', 'nida')->ignore($employee->id)],
            'tribe'             => 'nullable|string|max:100',
            'religion'          => 'nullable|string|max:100',
            'address'           => 'nullable|string|max:500',
            'education_level'   => 'nullable|string|max:255',
            'position'          => 'required|string|max:255',
            'department'        => 'nullable|string|max:255',
            'date_of_hire'      => 'required|date',
            'profile_picture'   => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'cv_attachment'     => 'nullable|mimes:pdf,doc,docx|max:5120',
            'other_information' => 'nullable|string',
            'EmployeeID'        => ['nullable', 'string', Rule::unique('employees', 'EmployeeID')->ignore($employee->id)],
            'basic_salary'      => 'required|numeric|min:0',

            // Payment / Salary processing
            'bank_name'              => 'nullable|string|max:150',
            'bank_account_number'    => 'nullable|string|max:50',
            'bank_account_name'      => 'nullable|string|max:150',
            'bank_branch'            => 'nullable|string|max:150',
            'mobile_money_provider'  => 'nullable|in:mpesa,tigopesa,airtelmoney,halopesa,other',
            'mobile_money_number'    => 'nullable|string|max:20',
            'payment_method'         => 'nullable|in:bank,mobile_money,cash',
            'housing_allowance'      => 'nullable|numeric|min:0',
            'transport_allowance'    => 'nullable|numeric|min:0',
            'medical_allowance'      => 'nullable|numeric|min:0',
            'overtime_rate'          => 'nullable|numeric|min:0',

            // Tanzanian statutory / compliance
            'tin_number'          => 'nullable|string|max:30',
            'nssf_number'         => 'nullable|string|max:30',
            'pssss_number'        => 'nullable|string|max:30',
            'nhif_number'         => 'nullable|string|max:30',
            'wcf_number'          => 'nullable|string|max:30',
            'employment_type'     => 'nullable|in:permanent,contract,probation,casual',
            'contract_start_date' => 'nullable|date',
            'contract_end_date'   => 'nullable|date|after_or_equal:contract_start_date',

            // Employment structure
            'supervisor_id'      => 'nullable|integer|exists:employees,id',
            'termination_date'   => 'nullable|date',
            'termination_reason' => 'nullable|string|max:255',
            'leave_balance'      => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        $profilePicturePath = $employee->profile_picture;
        $cvPath              = $employee->cv;

        try {
            if ($request->hasFile('profile_picture')) {
                if ($profilePicturePath) Storage::disk('public')->delete($profilePicturePath);
                $profilePicturePath = $request->file('profile_picture')->store('employees/profile_pictures', 'public');
            }

            if ($request->hasFile('cv_attachment')) {
                if ($cvPath) Storage::disk('public')->delete($cvPath);
                $cvPath = $request->file('cv_attachment')->store('employees/cvs', 'public');
            }

            $name = $validated['LastName'] . ', ' . $validated['FirstName'] . ' ' . ($validated['MiddleName'] ?? '');

            $user->update([
                'name'       => $name,
                'branch_id'  => $validated['branch_id'],
                'FirstName'  => $validated['FirstName'],
                'MiddleName' => $validated['MiddleName'],
                'LastName'   => $validated['LastName'],
                'gender'     => $validated['gender'],
                'Dob'        => $validated['Dob'],
                'email'      => $validated['email'],
                'phone'      => $validated['phone'],
                'Role'       => $validated['Role'],
                'updated_by' => auth()->id(),
            ]);

            $employee->update([
                'EmployeeID'        => $validated['EmployeeID'],
                'marital_status'    => $validated['marital_status'],
                'nida'              => $validated['nida'],
                'tribe'             => $validated['tribe'],
                'religion'          => $validated['religion'],
                'address'           => $validated['address'],
                'education_level'   => $validated['education_level'],
                'position'          => $validated['position'],
                'department'        => $validated['department'],
                'date_of_hire'      => $validated['date_of_hire'],
                'basic_salary'      => $validated['basic_salary'],
                'profile_picture'   => $profilePicturePath,
                'cv'                => $cvPath,
                'other_information' => $validated['other_information'],
                'updated_by'        => auth()->id(),

                // Payment / Salary processing
                'bank_name'             => $validated['bank_name'] ?? null,
                'bank_account_number'   => $validated['bank_account_number'] ?? null,
                'bank_account_name'     => $validated['bank_account_name'] ?? null,
                'bank_branch'           => $validated['bank_branch'] ?? null,
                'mobile_money_provider' => $validated['mobile_money_provider'] ?? null,
                'mobile_money_number'   => $validated['mobile_money_number'] ?? null,
                'payment_method'        => $validated['payment_method'] ?? null,
                'housing_allowance'     => $validated['housing_allowance'] ?? null,
                'transport_allowance'   => $validated['transport_allowance'] ?? null,
                'medical_allowance'     => $validated['medical_allowance'] ?? null,
                'overtime_rate'         => $validated['overtime_rate'] ?? null,

                // Tanzanian statutory / compliance
                'tin_number'          => $validated['tin_number'] ?? null,
                'nssf_number'         => $validated['nssf_number'] ?? null,
                'pssss_number'        => $validated['pssss_number'] ?? null,
                'nhif_number'         => $validated['nhif_number'] ?? null,
                'wcf_number'          => $validated['wcf_number'] ?? null,
                'employment_type'     => $validated['employment_type'] ?? null,
                'contract_start_date' => $validated['contract_start_date'] ?? null,
                'contract_end_date'   => $validated['contract_end_date'] ?? null,

                // Employment structure
                'supervisor_id'      => $validated['supervisor_id'] ?? null,
                'termination_date'   => $validated['termination_date'] ?? null,
                'termination_reason' => $validated['termination_reason'] ?? null,
                'leave_balance'      => $validated['leave_balance'] ?? null,
            ]);
            if (
                    !SalaryCalcl::where('Paid_id', $employee->id)
                        ->where('Status', 'Active')
                        ->exists()
                ) {
                    SalaryCalcl::create([
                        'Paid_id'   => $employee->id,
                        'GrsAmount' => $employee->basic_salary,
                        'Status'    => 'Active',
                    ]);
                }
            DB::commit();
            Alert::success('Success', "Employee {$user->username} updated successfully");
            return back();

        } catch (\Throwable $th) {
            DB::rollBack();
            // dd($th->getMessage(), $th->getLine(), $th->getFile());
            Alert::error('Sorry! ' . ' ' . Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }
    /**
     * Show a single employee (edit form / profile view).
     */
    public function showemployeeinfo($id)
    {
        // try {
            $employee = Employee::with('user')->findOrFail($id);
            $roles    = User::getRoles();
            return view('in.employee.employeeshow', compact('employee', 'roles'));
        // } catch (\Throwable $th) {
        //     Alert::error('Sorry! ' . ' ' . Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
        //     return back();
        // }
    }

    /**
     * Soft-delete an employee (flip Status on both employee and linked user).
     */
    public function deleteemployeeinfo($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($id);
            $user     = User::findOrFail($employee->Employee_id);

            $employee->update([
                'Status'     => 'Inactive',
                'is_active'  => false,
                'updated_by' => auth()->id(),
            ]);

            $user->update([
                'Status'     => 'Inactive',
                'updated_by' => auth()->id(),
            ]);

            DB::commit();
            Alert::success('Success', "Employee {$user->username} has been deactivated");
            return back();

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Sorry! ' . ' ' . Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    /**
     * List inactive/soft-deleted employees, for the restore screen.
     */
    public function trashedemployeeinfo()
    {
        try {
            $data  = Employee::with('user')->where('Status', 'Inactive')->get();
            $roles = User::getRoles();
            return view('in.employee.trashedemployees', compact('data', 'roles'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! ' . ' ' . Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    /**
     * Restore a soft-deleted employee.
     */
    public function restoreemployeeinfo($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($id);
            $user     = User::findOrFail($employee->Employee_id);

            $employee->update([
                'Status'     => 'Active',
                'is_active'  => true,
                'updated_by' => auth()->id(),
            ]);

            $user->update([
                'Status'     => 'Active',
                'updated_by' => auth()->id(),
            ]);

            DB::commit();
            Alert::success('Success', "Employee {$user->username} has been restored");
            return back();

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Sorry! ' . ' ' . Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    /**
     * Lock an employee's login account.
     */
    public function lockemployeeaccount($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($id);
            $user     = User::findOrFail($employee->Employee_id);

            $user->update([
                'locked_until' => now()->addYears(100), // effectively indefinite, until explicitly unlocked
                'updated_by'   => auth()->id(),
            ]);

            DB::commit();
            Alert::success('Success', "Account for {$user->username} has been locked");
            return back();

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Sorry! ' . ' ' . Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    /**
     * Unlock an employee's login account.
     */
    public function unlockemployeeaccount($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($id);
            $user     = User::findOrFail($employee->Employee_id);

            $user->update([
                'locked_until'           => null,
                'failed_login_attempts'  => 0,
                'updated_by'             => auth()->id(),
            ]);

            DB::commit();
            Alert::success('Success', "Account for {$user->username} has been unlocked");
            return back();

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error('Sorry! ' . ' ' . Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
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
