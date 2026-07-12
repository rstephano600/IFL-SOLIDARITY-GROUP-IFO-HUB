<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use App\Models\Branch;
use App\Models\CompanyBusinessCode;
use App\Services\UserDataService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;

class CompanyController extends Controller
{
    public function companiesinformations()
    {
        try {

            $companies = Company::with([
                    'parentCompany',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->where('Status', 'Active')
                ->latest()
                ->get();

            return view('in.companies.companiesinformations', compact('companies'));

        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storecompaniesinformations(Request $request)
    {
        $request->validate([
            'company_code' => 'required|string|max:30|unique:companies,company_code',
            'company_name' => 'required|string|max:200',
            'company_type' => 'required|string|max:200',
            'parent_company_id' => 'nullable|exists:companies,id',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'established_date' => 'nullable|date',
        ]);

        try {

            Company::create([
                'company_code' => $request->company_code,
                'company_name' => $request->company_name,
                'company_type' => $request->company_type,
                'parent_company_id' => $request->parent_company_id,
                'description' => $request->description,
                'address' => $request->address,
                'region' => $request->region,
                'district' => $request->district,
                'ward' => $request->ward,
                'village' => $request->village,
                'phone' => $request->phone,
                'email' => $request->email,
                'established_date' => $request->established_date,
                'User_id' => auth()->id(),
                'Status' => 'Active',
                'AuditingStatus' => 'Pending',
                'ReportStatus' => 'Pending',
                'created_by' => auth()->id(),
            ]);

            Alert::success(
                'Success ' . auth()->user()->name, 'Operation completed successfully.'
            );
            return redirect()->back();
        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            // Alert::error(
            //     'Sorry! ' . auth()->user()->name,
            //     'Technical error exists, please contact Technical Support. Error: ' . $th->getMessage()
            // );
            return back()->withInput();
        }
    }

    public function viewcompaniesinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);


            $company = Company::with([
                    'parentCompany',
                    'childCompanies',
                    'branches',
                    'departments',
                    'costCentres',
                    'businessCodes',
                    'memberCategories',
                    'members',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->findOrFail($id);

            return view('in.companies.viewcompaniesinformations', compact('company'));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            // Alert::error(
            //     'Sorry! ' . auth()->user()->name,
            //     'Technical error exists, please contact Technical Support. Error: ' . $th->getMessage()
            // );
            return back()->withInput();
        }
    }
    public function editcompaniesinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $company = Company::with([
                    'parentCompany',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->findOrFail($id);

            // Companies available to be selected as parent
            $parentCompanies = Company::where('Status', 'Active')
                ->where('id', '!=', $id)
                ->orderBy('company_name')
                ->get();

            return view('in.companies.editcompaniesinformations', compact(
                'company',
                'parentCompanies'
            ));

        } catch (\Throwable $th) {
            \Log::error('Error', [
                'user_id'   => Auth::id(), 'user_name' => auth()->user()->name, 'method'    => __METHOD__, 'message'   => $th->getMessage(), 'file'      => $th->getFile(), 'line'      => $th->getLine(), 'trace'     => $th->getTraceAsString(),
            ]);
            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support. Error: Tel: +255657856790.'
            );
            // Alert::error(
            //     'Sorry! ' . auth()->user()->name,
            //     'Technical error exists, please contact Technical Support. Error: ' . $th->getMessage()
            // );
            return back()->withInput();
        }
    }
    public function updatecompaniesinformations(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $request->validate([
            'company_code' => 'required|string|max:30|unique:companies,company_code,' . $id,
            'company_name' => 'required|string|max:200',
            'company_type' => 'required|string|max:200',
            'parent_company_id' => 'nullable|exists:companies,id',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'village' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'established_date' => 'nullable|date',
        ]);

        try {

            $company = Company::findOrFail($id);

            $company->update([
                'company_code' => $request->company_code,
                'company_name' => $request->company_name,
                'company_type' => $request->company_type,
                'parent_company_id' => $request->parent_company_id,
                'description' => $request->description,
                'address' => $request->address,
                'region' => $request->region,
                'district' => $request->district,
                'ward' => $request->ward,
                'village' => $request->village,
                'phone' => $request->phone,
                'email' => $request->email,
                'established_date' => $request->established_date,
                'updated_by' => auth()->id(),
            ]);

            Alert::success('Success', 'Company updated successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back()->withInput();

        }
    }
    public function deletecompaniesinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $company = Company::findOrFail($id);

            $company->update([
                'Status' => 'Deleted',
                'updated_by' => auth()->id(),
            ]);

            Alert::success('Success', 'Company deleted successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    public function restorecompaniesinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $company = Company::findOrFail($id);

            $company->update([
                'Status' => 'Active',
                'updated_by' => auth()->id(),
            ]);

            Alert::success('Success', 'Company restored successfully.');

            return redirect()->back();

        } catch (\Exception $e) {

            Alert::error('Error', $e->getMessage());

            return redirect()->back();

        }
    }

    // COMPANY BRANCHIES INFORMATIONS
    public function branchiesinformations()
    {
        try {

            $branches = Branch::with([
                    'company',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->where('Status', 'Active')
                ->latest()
                ->get();
            $companies = Company::where('Status', 'Active')->get();
            return view('in.branchies.branchiesinformations', compact('branches', 'companies'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function storebranchiesinformations(Request $request)
    {
        try {

            $request->validate([
                'branch_code' => 'required|unique:branchies,branch_code',
                'branch_name' => 'required|max:255',
                'company_id' => 'required|exists:companies,id',
                'description' => 'nullable',
                'address' => 'nullable|max:255',
                'region' => 'nullable|max:100',
                'district' => 'nullable|max:100',
                'ward' => 'nullable|max:100',
                'village' => 'nullable|max:100',
                'phone' => 'nullable|max:30',
                'email' => 'nullable|email|max:150',
                'established_date' => 'nullable|date',
            ]);

            Branch::create([
                'branch_code' => $request->branch_code,
                'branch_name' => $request->branch_name,
                'company_id' => $request->company_id,
                'description' => $request->description,
                'address' => $request->address,
                'region' => $request->region,
                'district' => $request->district,
                'ward' => $request->ward,
                'village' => $request->village,
                'phone' => $request->phone,
                'email' => $request->email,
                'established_date' => $request->established_date,
                'User_id' => auth()->id(),
                'Status' => 'Active',
                'AuditingStatus' => 'Pending',
                'ReportStatus' => 'Pending',
                'created_by' => auth()->id(),
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Branch registered successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }


    public function viewbranchiesinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $branch = Branch::with([
                    'company',
                    'user',
                    'createdBy',
                    'updatedBy',
                    'departments',
                    'costCentres',
                    'businessCodes',
                    'memberCategories',
                    'members',
                ])
                ->findOrFail($id);

            return view('in.branchies.viewbranchiesinformations', compact('branch'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }
    public function editbranchiesinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $branch = Branch::with([
                    'company',
                    'user',
                    'createdBy',
                    'updatedBy',
                    'departments',
                    'costCentres',
                    'businessCodes',
                    'memberCategories',
                    'members',
                ])
                ->findOrFail($id);
            $companies = Company::where('Status', 'Active')->get();
            return view('in.branchies.editbranchiesinformations', compact('branch', 'companies'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function updatebranchiesinformations(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $request->validate([
                'branch_code' => 'required|unique:branchies,branch_code,' . $id,
                'branch_name' => 'required|max:255',
                'company_id' => 'required|exists:companies,id',
                'description' => 'nullable',
                'address' => 'nullable|max:255',
                'region' => 'nullable|max:100',
                'district' => 'nullable|max:100',
                'ward' => 'nullable|max:100',
                'village' => 'nullable|max:100',
                'phone' => 'nullable|max:30',
                'email' => 'nullable|email|max:150',
                'established_date' => 'nullable|date',
            ]);

            $branch = Branch::findOrFail($id);

            $branch->update([
                'branch_code' => $request->branch_code,
                'branch_name' => $request->branch_name,
                'company_id' => $request->company_id,
                'description' => $request->description,
                'address' => $request->address,
                'region' => $request->region,
                'district' => $request->district,
                'ward' => $request->ward,
                'village' => $request->village,
                'phone' => $request->phone,
                'email' => $request->email,
                'established_date' => $request->established_date,
                'updated_by' => auth()->id(),
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Branch updated successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    public function deletebranchiesinformations($id)
    {
        try {

            $branch = Branch::findOrFail($id);

            $branch->update([
                'Status' => 'Deleted',
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Branch deleted successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function restorebranchiesinformations($id)
    {
        try {

            $branch = Branch::findOrFail($id);

            $branch->update([
                'Status' => 'Active',
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . auth()->user()->name,
                'Branch restored successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }


    // COMPANY BUSINESS CODES INFORMATIONS
    public function companybusinesscodeinformations()
    {
        try {

            $businesscodes = CompanyBusinessCode::with([
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
            return view('in.companies.companybusinesscodes.companybusinesscodeinformations', compact('businesscodes','companies','branches'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function companybusinesscodeinformationsreport()
    {
        try {

            $businesscodes = CompanyBusinessCode::with([
                    'company',
                    'branch',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->latest()
                ->get();

            return view('in.companies.companybusinesscodes.companybusinesscodeinformationsreport', compact('businesscodes'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }


    public function storecompanybusinesscodeinformations(Request $request)
    {
        try {

            $request->validate([
                'business_code' => 'required|unique:company_businesses_codes,business_code',
                'business_name' => 'required|max:255',
                'company_id' => 'required|exists:companies,id',
                'branch_id' => 'required|exists:branchies,id',
                'business_activity' => 'required|max:255',
                'segment' => 'nullable|max:255',
                'description' => 'nullable',
            ]);

            CompanyBusinessCode::create([
                'business_code' => $request->business_code,
                'business_name' => $request->business_name,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'business_activity' => $request->business_activity,
                'segment' => $request->segment,
                'description' => $request->description,
                'User_id' => Auth()->id(),
                'Status' => 'Active',
                'AuditingStatus' => 'Pending',
                'ReportStatus' => 'Pending',
                'created_by' => Auth()->id(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Company Business Code registered successfully.'
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

    public function viewcompanybusinesscodeinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $businesscode = CompanyBusinessCode::with([
                    'company',
                    'branch',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->findOrFail($id);

            return view('in.companies.companybusinesscodes.viewcompanybusinesscodeinformations', compact('businesscode'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function editcompanybusinesscodeinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $businesscode = CompanyBusinessCode::findOrFail($id);

            $companies = Company::where('Status', 'Active')
                ->orderBy('company_name')
                ->get();

            $branches = Branch::where('Status', 'Active')
                ->orderBy('branch_name')
                ->get();

            return view('in.companies.companybusinesscodes.editcompanybusinesscodeinformations', compact(
                'businesscode',
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

    public function updatecompanybusinesscodeinformations(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $request->validate([
                'business_code' => 'required|unique:company_businesses_codes,business_code,' . $id,
                'business_name' => 'required|max:255',
                'company_id' => 'required|exists:companies,id',
                'branch_id' => 'required|exists:branchies,id',
                'business_activity' => 'required|max:255',
                'segment' => 'nullable|max:255',
                'description' => 'nullable',
            ]);

            $businesscode = CompanyBusinessCode::findOrFail($id);

            $businesscode->update([
                'business_code' => $request->business_code,
                'business_name' => $request->business_name,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'business_activity' => $request->business_activity,
                'segment' => $request->segment,
                'description' => $request->description,
                'updated_by' => Auth()->id(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Company Business Code updated successfully.'
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

    public function deletedcompanybusinesscodeinformations()
    {
        try {

            $businesscodes = CompanyBusinessCode::with([
                    'company',
                    'branch'
                ])
                ->where('Status', 'Deleted')
                ->latest()
                ->get();

            return view('in.companies.companybusinesscodes.deletedcompanybusinesscodeinformations', compact('businesscodes'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function deletecompanybusinesscodeinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $businesscode = CompanyBusinessCode::findOrFail($id);

            $businesscode->update([
                'Status' => 'Deleted',
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Company Business Code deleted successfully.'
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

    public function restorecompanybusinesscodeinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $businesscode = CompanyBusinessCode::findOrFail($id);

            $businesscode->update([
                'Status' => 'Active',
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Company Business Code restored successfully.'
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
