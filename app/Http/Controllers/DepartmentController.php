<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Department;
use App\Services\UserDataService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;

class DepartmentController extends Controller
{
    public function departmentinformations()
    {
        try {

            $departments = Department::with([
                    'company',
                    'branch',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->where('Status', 'Active')
                ->latest()
                ->get();
            $companies = Company::where('Status', 'Active')->get();
            $branches = Company::where('Status', 'Active')->get();
            return view('in.departments.departmentinformations', compact('departments', 'companies', 'branches'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function departmentinformationsreport()
    {
        try {

            $departments = Department::with([
                    'company',
                    'branch',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->latest()
                ->get();

            return view('in.departments.departmentinformationsreport', compact('departments'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function storedepartmentinformations(Request $request)
    {
        try {

            $request->validate([
                'department_code' => 'required|unique:departments,department_code',
                'department_name' => 'required|max:255',
                'company_id' => 'required|exists:companies,id',
                'branch_id' => 'required|exists:branchies,id',
                'function' => 'nullable|string',
                'description' => 'nullable|string',
            ]);

            Department::create([
                'department_code' => $request->department_code,
                'department_name' => $request->department_name,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'function' => $request->function,
                'descriptions' => $request->description,
                'User_id' => auth()->id(),
                'Status' => 'Active',
                'AuditingStatus' => 'Pending',
                'ReportStatus' => 'Pending',
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Department registered successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    public function viewdepartmentinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $department = Department::with([
                    'company',
                    'branch',
                    'user',
                    'createdBy',
                    'updatedBy',
                    'costCentres'
                ])
                ->findOrFail($id);

            return view('in.departments.viewdepartmentinformations', compact('department'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function editdepartmentinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $department = Department::findOrFail($id);

            $companies = Company::where('Status', 'Active')
                ->orderBy('company_name')
                ->get();

            $branches = Branch::where('Status', 'Active')
                ->orderBy('branch_name')
                ->get();

            return view('in.departments.editdepartmentinformations', compact(
                'department',
                'companies',
                'branches'
            ));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function updatedepartmentinformations(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $request->validate([
                'department_code' => 'required|unique:departments,department_code,' . $id,
                'department_name' => 'required|max:255',
                'company_id' => 'required|exists:companies,id',
                'branch_id' => 'required|exists:branchies,id',
                'function' => 'nullable|string',
                'description' => 'nullable|string',
            ]);

            $department = Department::findOrFail($id);

            $department->update([
                'department_code' => $request->department_code,
                'department_name' => $request->department_name,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'function' => $request->function,
                'descriptions' => $request->description,
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Department updated successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    public function deleteddepartmentinformations()
    {
        try {

            $departments = Department::with([
                    'company',
                    'branch'
                ])
                ->where('Status', 'Deleted')
                ->latest()
                ->get();

            return view('in.departments.deleteddepartmentinformations', compact('departments'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function deletedepartmentinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $department = Department::findOrFail($id);

            $department->update([
                'Status' => 'Deleted',
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Department deleted successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function restoredepartmentinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $department = Department::findOrFail($id);

            $department->update([
                'Status' => 'Active',
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Department restored successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }



}
