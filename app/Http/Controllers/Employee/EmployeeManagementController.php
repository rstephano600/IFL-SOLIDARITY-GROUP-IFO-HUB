<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\NextOfKin;
use App\Models\Referee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Helpers\LogActivity;

class EmployeeManagementController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'nextOfKin', 'referees']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('nida', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by department
        if ($request->has('department') && $request->department != '') {
            $query->where('department', $request->department);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active' ? 1 : 0);
        }

        $employees = $query->latest()->paginate(15);
        
        // Get unique departments for filter
        $departments = Employee::whereNotNull('department')
                               ->distinct()
                               ->pluck('department');

        return view('in.employees.index', compact('employees', 'departments'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $roles = User::getRoles(); // Assuming you have this method in User model
        return view('in.employees.create', compact('roles'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // User Information
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => 'nullable|string|unique:users,phone|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
            
            // Employee Information
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'nida' => 'nullable|string|unique:employees,nida|max:50',
            'tribe' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'education_level' => 'nullable|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'date_of_hire' => 'required|date',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'other_information' => 'nullable|string',
            
            // Next of Kin
            'nok_first_name' => 'nullable|string|max:255',
            'nok_last_name' => 'nullable|string|max:255',
            'nok_gender' => 'nullable|in:male,female,other',
            'nok_email' => 'nullable|email|max:255',
            'nok_phone' => 'nullable|string|max:20',
            'nok_address' => 'nullable|string|max:500',
            'nok_other_informations' => 'nullable|string',
            
            // Referee 1
            'ref1_first_name' => 'nullable|string|max:255',
            'ref1_last_name' => 'nullable|string|max:255',
            'ref1_gender' => 'nullable|in:male,female,other',
            'ref1_email' => 'nullable|email|max:255',
            'ref1_phone' => 'nullable|string|max:20',
            'ref1_address' => 'nullable|string|max:500',
            'ref1_other_informations' => 'nullable|string',
            
            // Referee 2
            'ref2_first_name' => 'nullable|string|max:255',
            'ref2_last_name' => 'nullable|string|max:255',
            'ref2_gender' => 'nullable|in:male,female,other',
            'ref2_email' => 'nullable|email|max:255',
            'ref2_phone' => 'nullable|string|max:20',
            'ref2_address' => 'nullable|string|max:500',
            'ref2_other_informations' => 'nullable|string',
        ]);

        DB::beginTransaction();

        $username = 'AB' . '-' .$validated['last_name']. '-' . str_pad(Employee::count() + 1, 6, '0', STR_PAD_LEFT);

        try {
            // Create User
            $user = User::create([
                'username' => $username,
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'status' => 'active',
                'created_by' => auth()->id(),
            ]);

            // Handle file uploads
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')
                    ->store('employees/profile_pictures', 'public');
            }

            $cvPath = null;
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')
                    ->store('employees/cvs', 'public');
            }

            // Generate Employee ID
            $employeeId = 'EMP' . str_pad(Employee::count() + 1, 6, '0', STR_PAD_LEFT);

            // Create Employee
            $employee = Employee::create([
                'employee_id' => $employeeId,
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
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
                ]);
            }

            // Create Referee 2 if provided
            if ($request->filled('ref2_first_name')) {
                Referee::create([
                    'employee_id' => $employee->id,
                    'first_name' => $validated['ref2_first_name'],
                    'last_name' => $validated['ref2_last_name'],
                    'gender' => $validated['ref2_gender'],
                    'email' => $validated['ref2_email'],
                    'phone' => $validated['ref2_phone'],
                    'address' => $validated['ref2_address'],
                    'other_informations' => $validated['ref2_other_informations'],
                ]);
            }

            DB::commit();

            return redirect()->route('employees.index')
                ->with('success', 'An employee have been added succesfully with the username' . $username );

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded files if transaction failed
            if ($profilePicturePath) {
                Storage::disk('public')->delete($profilePicturePath);
            }
            if ($cvPath) {
                Storage::disk('public')->delete($cvPath);
            }

            return back()->withInput()
                ->with('error', 'There is a problem happened please try again or contact administrator: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        $employee->load(['user', 'nextOfKin', 'referees', 'creator', 'updater']);
        return view('in.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        $employee->load(['user', 'nextOfKin', 'referees']);
        $roles = User::getRoles();
        return view('in.employees.edit', compact('employee', 'roles'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            // User Information
            'username' => 'required|string|max:255|unique:users,username,' . $employee->user_id,
            'email' => 'required|email|max:255|unique:users,email,' . $employee->user_id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $employee->user_id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string',
            
            // Employee Information
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'nida' => 'nullable|string|max:50|unique:employees,nida,' . $employee->id,
            'tribe' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'education_level' => 'nullable|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'date_of_hire' => 'required|date',
            'is_active' => 'required|boolean',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'other_information' => 'nullable|string',
            
            // Next of Kin
            'nok_first_name' => 'nullable|string|max:255',
            'nok_last_name' => 'nullable|string|max:255',
            'nok_gender' => 'nullable|in:male,female,other',
            'nok_email' => 'nullable|email|max:255',
            'nok_phone' => 'nullable|string|max:20',
            'nok_address' => 'nullable|string|max:500',
            'nok_other_informations' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Update User
            $userData = [
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'role' => $validated['role'],
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $employee->user->update($userData);

            // Handle file uploads
            if ($request->hasFile('profile_picture')) {
                // Delete old file
                if ($employee->profile_picture) {
                    Storage::disk('public')->delete($employee->profile_picture);
                }
                $validated['profile_picture'] = $request->file('profile_picture')
                    ->store('employees/profile_pictures', 'public');
            }

            if ($request->hasFile('cv')) {
                // Delete old file
                if ($employee->cv) {
                    Storage::disk('public')->delete($employee->cv);
                }
                $validated['cv'] = $request->file('cv')
                    ->store('employees/cvs', 'public');
            }

            // Update Employee
            $employee->update([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'marital_status' => $validated['marital_status'],
                'nida' => $validated['nida'],
                'tribe' => $validated['tribe'],
                'religion' => $validated['religion'],
                'address' => $validated['address'],
                'education_level' => $validated['education_level'],
                'position' => $validated['position'],
                'department' => $validated['department'],
                'date_of_hire' => $validated['date_of_hire'],
                'is_active' => $validated['is_active'],
                'profile_picture' => $validated['profile_picture'] ?? $employee->profile_picture,
                'cv' => $validated['cv'] ?? $employee->cv,
                'other_information' => $validated['other_information'],
                'updated_by' => auth()->id(),
            ]);

            // Update or Create Next of Kin
            if ($request->filled('nok_first_name')) {
                $employee->nextOfKin()->updateOrCreate(
                    ['employee_id' => $employee->id],
                    [
                        'first_name' => $validated['nok_first_name'],
                        'last_name' => $validated['nok_last_name'],
                        'gender' => $validated['nok_gender'],
                        'email' => $validated['nok_email'],
                        'phone' => $validated['nok_phone'],
                        'address' => $validated['nok_address'],
                        'other_informations' => $validated['nok_other_informations'],
                    ]
                );
            }

            DB::commit();

            return redirect()->route('employees.show', $employee)
                ->with('success', 'An employee have been Updated succesfully');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'There is a problem: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee)
    {
        DB::beginTransaction();

        try {
            // Delete uploaded files
            if ($employee->profile_picture) {
                Storage::disk('public')->delete($employee->profile_picture);
            }
            if ($employee->cv) {
                Storage::disk('public')->delete($employee->cv);
            }

            // Delete employee (will cascade delete user, next_of_kin, referees)
            $employee->delete();

            DB::commit();

            return redirect()->route('employees.index')
                ->with('success', 'An employee have been deleted succesfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'sorry their is a problem in deleting: ' . $e->getMessage());
        }
    }

    /**
     * Toggle employee active status.
     */
    public function toggleStatus(Employee $employee)
    {
        try {
            $employee->update([
                'is_active' => !$employee->is_active,
                'updated_by' => auth()->id(),
            ]);

            // Also update user status
            $employee->user->update([
                'status' => $employee->is_active ? 'active' : 'inactive',
            ]);

            $status = $employee->is_active ? 'active' : 'inactive';

            return back()->with('success', "Employee {$status} successfully!");

        } catch (\Exception $e) {
            return back()->with('error', 'Sorry an error happened: ' . $e->getMessage());
        }
    }

    /**
     * Add or update referee.
     */
    public function storeReferee(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'other_informations' => 'nullable|string',
        ]);

        try {
            Referee::create([
                'employee_id' => $employee->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'other_informations' => $validated['other_informations'],
            ]);

            return back()->with('success', 'Referee have been added successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Sorry their is an error: ' . $e->getMessage());
        }
    }

    /**
     * Delete referee.
     */
    public function deleteReferee(Referee $referee)
    {
        try {
            $referee->delete();
            return back()->with('success', 'Referee have deleted successfuly!');
        } catch (\Exception $e) {
            return back()->with('error', 'Sorry a problem happened: ' . $e->getMessage());
        }
    }

    public function searchOfficers(Request $request)
{
    $query = $request->get('q', '');
    $officers = \App\Models\Employee::query()
        ->where(function($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
              ->orWhere('last_name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%")
              ->orWhere('phone', 'like', "%{$query}%");
        })
        ->where('status', 'active')
        ->limit(10)
        ->get(['id', 'first_name', 'last_name', 'email']);
    
    return response()->json($officers);
}

}
