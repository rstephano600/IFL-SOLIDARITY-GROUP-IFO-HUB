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
use App\Models\CostCentre;
use App\Services\UserDataService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;

class CostCentreController extends Controller
{
    public function costcentreinformations()
    {
        try {

            $costcentres = CostCentre::with([
                    'company',
                    'branch',
                    'department',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->where('Status', 'Active')
                ->latest()
                ->get();
            $companies = Company::where('Status', 'Active')->get();
            $branches = Company::where('Status', 'Active')->get();
            $departments = Department::where('Status', 'Active')->get();
            return view('in.costcentres.costcentreinformations', compact('costcentres','companies','branches','departments'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function costcentreinformationsreport()
    {
        try {

            $costcentres = CostCentre::with([
                    'company',
                    'branch',
                    'department',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->latest()
                ->get();

            return view('in.costcentres.report', compact('costcentres'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function storecostcentreinformations(Request $request)
    {
        try {

            $request->validate([
                'cost_centre_code' => 'required|unique:cost_centres,cost_centre_code',
                'cost_centre_name' => 'required|max:255',
                'company_id' => 'required|exists:companies,id',
                'branch_id' => 'required|exists:branchies,id',
                'department_id' => 'required|exists:departments,id',
                'reporting_segment' => 'nullable|max:255',
            ]);

            CostCentre::create([
                'cost_centre_code' => $request->cost_centre_code,
                'cost_centre_name' => $request->cost_centre_name,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'reporting_segment' => $request->reporting_segment,
                'User_id' => Auth()->id(),
                'Status' => 'Active',
                'AuditingStatus' => 'Pending',
                'ReportStatus' => 'Pending',
                'created_by' => Auth()->id(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Cost Centre registered successfully.'
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

    public function viewcostcentreinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $costcentre = CostCentre::with([
                    'company',
                    'branch',
                    'department',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->findOrFail($id);

            return view('in.costcentres.viewcostcentreinformations', compact('costcentre'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function editcostcentreinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $costcentre = CostCentre::findOrFail($id);

            $companies = Company::where('Status','Active')
                ->orderBy('company_name')
                ->get();

            $branches = Branch::where('Status','Active')
                ->orderBy('branch_name')
                ->get();

            $departments = Department::where('Status','Active')
                ->orderBy('department_name')
                ->get();

            return view('in.costcentres.editcostcentreinformations', compact(
                'costcentre',
                'companies',
                'branches',
                'departments'
            ));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function updatecostcentreinformations(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $request->validate([
                'cost_centre_code' => 'required|unique:cost_centres,cost_centre_code,' . $id,
                'cost_centre_name' => 'required|max:255',
                'company_id' => 'required|exists:companies,id',
                'branch_id' => 'required|exists:branchies,id',
                'department_id' => 'required|exists:departments,id',
                'reporting_segment' => 'nullable|max:255',
            ]);

            $costcentre = CostCentre::findOrFail($id);

            $costcentre->update([
                'cost_centre_code' => $request->cost_centre_code,
                'cost_centre_name' => $request->cost_centre_name,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'department_id' => $request->department_id,
                'reporting_segment' => $request->reporting_segment,
                'updated_by' => Auth()->id(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Cost Centre updated successfully.'
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

    public function deletedcostcentreinformations()
    {
        try {

            $costcentres = CostCentre::with([
                    'company',
                    'branch',
                    'department'
                ])
                ->where('Status','Deleted')
                ->latest()
                ->get();

            return view('in.costcentres.deletedcostcentreinformations', compact('costcentres'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function restorecostcentreinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $costcentre = CostCentre::findOrFail($id);

            $costcentre->update([
                'Status' => 'Active',
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Cost Centre restored successfully.'
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
