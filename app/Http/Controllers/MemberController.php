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
use App\Models\MemberCategory;
use App\Models\Member;
use App\Services\UserDataService;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function membercategoryinformations()
    {
        try {

            $membercategories = MemberCategory::with([
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
            return view('in.members.membercategories.membercategoryinformations', compact('membercategories', 'companies', 'branches'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function membercategoryinformationsreport()
    {
        try {

            $membercategories = MemberCategory::with([
                    'company',
                    'branch',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->latest()
                ->get();

            return view('in.members.membercategories.membercategoryinformationsreport', compact('membercategories'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function storemembercategoryinformations(Request $request)
    {
        try {

            $request->validate([
                'member_category_code' => 'required|unique:member_categories,member_category_code',
                'member_category_name' => 'required|max:255',
                'company_id' => 'required|exists:companies,id',
                'branch_id' => 'required|exists:branchies,id',
                'description' => 'nullable|string',
                'voting_right' => 'required',
                'loan_eligibility' => 'required',
            ]);

            MemberCategory::create([
                'member_category_code' => $request->member_category_code,
                'member_category_name' => $request->member_category_name,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'description' => $request->description,
                'voting_right' => $request->voting_right,
                'loan_eligibility' => $request->loan_eligibility,
                'User_id' => Auth()->id(),
                'Status' => 'Active',
                'AuditingStatus' => 'Pending',
                'ReportStatus' => 'Pending',
                'created_by' => Auth()->id(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Member Category registered successfully.'
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

    public function viewmembercategoryinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $membercategory = MemberCategory::with([
                    'company',
                    'branch',
                    'user',
                    'createdBy',
                    'updatedBy',
                    'members'
                ])
                ->findOrFail($id);

            return view('in.members.membercategories.viewmembercategoryinformations', compact('membercategory'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function editmembercategoryinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $membercategory = MemberCategory::findOrFail($id);

            $companies = Company::where('Status', 'Active')
                ->orderBy('company_name')
                ->get();

            $branches = Branch::where('Status', 'Active')
                ->orderBy('branch_name')
                ->get();

            return view('in.members.membercategories.editmembercategoryinformations', compact(
                'membercategory',
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

    public function updatemembercategoryinformations(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $request->validate([
                'member_category_code' => 'required|unique:member_categories,member_category_code,' . $id,
                'member_category_name' => 'required|max:255',
                'company_id' => 'required|exists:companies,id',
                'branch_id' => 'required|exists:branchies,id',
                'description' => 'nullable|string',
                'voting_right' => 'required',
                'loan_eligibility' => 'required',
            ]);

            $membercategory = MemberCategory::findOrFail($id);

            $membercategory->update([
                'member_category_code' => $request->member_category_code,
                'member_category_name' => $request->member_category_name,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'description' => $request->description,
                'voting_right' => $request->voting_right,
                'loan_eligibility' => $request->loan_eligibility,
                'updated_by' => Auth()->id(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Member Category updated successfully.'
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

    public function deletedmembercategoryinformations()
    {
        try {

            $membercategories = MemberCategory::with([
                    'company',
                    'branch'
                ])
                ->where('Status', 'Deleted')
                ->latest()
                ->get();

            return view('in.members.membercategories.deletedmembercategoryinformations', compact('membercategories'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function deletemembercategoryinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $membercategory = MemberCategory::findOrFail($id);

            $membercategory->update([
                'Status' => 'Deleted',
                'updated_by' => Auth()->id(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Member Category deleted successfully.'
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

    public function restoremembercategoryinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $membercategory = MemberCategory::findOrFail($id);

            $membercategory->update([
                'Status' => 'Active',
                'updated_by' => Auth()->id(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Member Category restored successfully.'
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


    // MEMBERS INFORMATIONS
    public function memberinformations()
    {
        try {

            $lastMember = Member::latest('id')->first();
            if ($lastMember) {
                $lastNumber = (int) substr($lastMember->member_code, -4);
                $nextNumber = $lastNumber + 1;

            } else {
                $nextNumber = 1;
            }

            $memberCode = 'MEM-' . date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $members = Member::with([
                    'memberCategory',
                    'company',
                    'branch',
                    'memberUser',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->where('Status', 'Active')
                ->latest()
                ->get();

            $users = User::where('Status', 'Active')
                ->whereDoesntHave('member')
                ->orderBy('name')
                ->get();

            $companies = Company::where('Status', 'Active')
                ->orderBy('company_name')
                ->get();

            $branches = Branch::where('Status', 'Active')
                ->orderBy('branch_name')
                ->get();

            $membercategories = MemberCategory::where('Status', 'Active')
                ->orderBy('member_category_name')
                ->get();

            return view(
                'in.members.members.memberinformations',
                compact(
                    'members',
                    'users',
                    'companies',
                    'branches',
                    'membercategories',
                    'memberCode'
                )
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();

        }
    }
    public function memberinformationsreport()
    {
        try {

            $members = Member::with([
                    'memberCategory',
                    'company',
                    'branch',
                    'memberUser',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->latest()
                ->get();

            return view('in.members.members.memberinformationsreport', compact('members'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function storememberinformations(Request $request)
    {
        DB::beginTransaction();

        // try {

            $request->validate([
                'member_code' => 'required|unique:members,member_code',
                'member_category_id' => 'required|exists:member_categories,id',
                'branch_id' => 'required|exists:branchies,id',
                'Role'           => ['required', Rule::in(User::getRoles())],
                'email' => 'required|email|unique:users,email',

                'FirstName' => 'required|max:100',
                'MiddleName' => 'nullable|max:100',
                'LastName' => 'required|max:100',

                'phone' => 'required|max:30',
                'Dob' => 'nullable|date',
                'gender' => 'required',

                'nida' => 'nullable|max:100',
                'tin' => 'nullable|max:100',
                'work_permit' => 'nullable|max:100',
                'profile_picture' => 'nullable|image|max:2048',
                'admission_date' => 'nullable|date',
            ]);

            $branch = Branch::findOrFail($request->branch_id);
            $companyId = $branch->company_id;

            $userCount = User::count();
            $no = $userCount + 1;
            $month = date('Y');
            
            $name = $request['LastName'] . ', ' . $request['FirstName'] . ' ' . ($request['MiddleName'] ?? '');
            $FName = strtoupper(substr($request['FirstName'], 0, 1));
            $MName = !empty($request['MiddleName']) ? strtoupper(substr($request['MiddleName'], 0, 1)) : '';
            $LName = strtoupper(substr($request['LastName'], 0, 1));
            $username = 'iflsg/' . $FName . $MName . $LName . '/' . $month . '/00' . $no;

            $profilePicturePath = null;
            $userprofilePicturePath = null;

            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')->store('members/profile_pictures', 'public');
            }
            if ($request->hasFile('profile_picture')) {
                $userprofilePicturePath = $request->file('profile_picture')->store('users/profile_pictures', 'public');
            }

            $user = User::create([
                'username' => $username,
                'name' => $name,
                'FirstName' => $request->FirstName,
                'MiddleName' => $request->MiddleName,
                'LastName' => $request->LastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make('123456IFLSG'),
                'Role'        => $request['Role'],
                'Status' => 'Active',
                'User_id' => Auth()->id(),
                'Dob' => $request->Dob,
                'gender' => $request->gender,
                'profile_picture' => $userprofilePicturePath,
            ]);

            Member::create([

                'member_code' => $request->member_code,
                'member_name' => $name,
                'member_category_id' => $request->member_category_id,
                'member_id' => $user->id,

                'company_id' => $companyId,
                'branch_id' => $request->branch_id,

                'nida' => $request->nida,
                'tin' => $request->tin,
                'work_permit' => $request->work_permit,
                'admission_date' => $request->admission_date,
                'profile_picture' => $profilePicturePath,

                'User_id' => Auth()->id(),
                'Status' => 'Active',
                'AuditingStatus' => 'Pending',
                'ReportStatus' => 'Pending',
                'created_by' => Auth()->id(),

            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Member registered successfully.'
            );

            return back();

        // } catch (\Throwable $th) {

        //     DB::rollBack();

        //     Alert::error(
        //         'Sorry! ' . Auth()->user()->name,
        //         'Technical error exists, please contact Technical Support Tel:+255657856790'
        //     );

        //     return back()->withInput();
        // }
    }
    public function storememberinformationsexist(Request $request)
    {
        $validated = $request->validate([

            'member_id' => [
                'required',
                'exists:users,id',
                'unique:members,member_id',
            ],
            'member_code' => [
                'required',
                'unique:members,member_code',
            ],

            'member_category_id' => [
                'required',
                'exists:member_categories,id',
            ],

            'branch_id' => [
                'required',
                'exists:branchies,id',
            ],

            'nida' => [
                'nullable',
                'digits:20',
                'unique:members,nida',
            ],

            'tin' => [
                'nullable',
                'digits_between:9,20',
                'unique:members,tin',
            ],

            'work_permit' => [
                'nullable',
                'string',
                'max:100',
                'unique:members,work_permit',
            ],

            'admission_date' => [
                'required',
                'date',
            ],

            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

        ]);
        try {
            $branch = Branch::findOrFail($request->branch_id);
            $companyId = $branch->company_id;

            $user = User::findOrFail($validated['member_id']);


            Member::create([

                'member_code'        => $validated['member_code'],
                'member_name'        => $user->name,
                'member_category_id' => $validated['member_category_id'],
                'member_id'          => $user->id,
                'company_id'         => $companyId,
                'branch_id'          => $validated['branch_id'],
                'nida'               => $validated['nida'],
                'tin'                => $validated['tin'],
                'work_permit'        => $validated['work_permit'],
                'admission_date'     => $validated['admission_date'],
                'User_id'            => Auth()->id(),
                'created_by'         => Auth()->id(),
                'updated_by'         => Auth()->id(),
            ]);

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Member registered successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technichal for support Tel:+255657856790'
            );

            return back();

        }
    }
    public function viewmemberinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $member = Member::with([
                    'memberCategory',
                    'company',
                    'branch',
                    'memberUser',
                    'user',
                    'createdBy',
                    'updatedBy'
                ])
                ->findOrFail($id);

            return view('in.members.members.viewmemberinformations', compact('member'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function editmemberinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $member = Member::with([
                    'memberCategory',
                    'company',
                    'branch',
                    'memberUser'
                ])
                ->findOrFail($id);

            $roles = array_filter(
                User::getRoles(),
                fn ($role) => $role !== User::ROLE_SUPERADMIN
            );

            $companies = Company::where('Status', 'Active')
                ->orderBy('company_name')
                ->get();

            $branches = Branch::where('Status', 'Active')
                ->orderBy('branch_name')
                ->get();

            $membercategories = MemberCategory::where('Status', 'Active')
                ->orderBy('member_category_name')
                ->get();

            return view('in.members.members.editmemberinformations', compact(
                'member',
                'companies',
                'branches',
                'membercategories',
                'roles'
            ));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function updatememberinformations(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $id = Crypt::decrypt($id);
            $member = Member::findOrFail($id);

            $request->validate([
                'member_code' => 'required|unique:members,member_code,' . $id,
                'member_name' => 'required|max:255',
                'member_category_id' => 'required|exists:member_categories,id',
                'company_id' => 'required|exists:companies,id',
                'branch_id' => 'required|exists:branchies,id',

                'email' => 'required|email|unique:users,email,' . $member->member_id,

                'FirstName' => 'required|max:100',
                'MiddleName' => 'nullable|max:100',
                'LastName' => 'required|max:100',

                'phone' => 'required|max:30',
                'Dob' => 'nullable|date',
                'gender' => 'required',

                'nida' => 'nullable|max:100',
                'tin' => 'nullable|max:100',
                'work_permit' => 'nullable|max:100',
                'admission_date' => 'nullable|date',
            ]);

            $user = User::findOrFail($member->member_id);

            $user->update([
                'username' => $request->member_code,
                'name' => $request->member_name,
                'FirstName' => $request->FirstName,
                'MiddleName' => $request->MiddleName,
                'LastName' => $request->LastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'Dob' => $request->Dob,
                'gender' => $request->gender,
                'updated_by' => Auth()->id(),
            ]);

            $member->update([
                'member_code' => $request->member_code,
                'member_name' => $request->member_name,
                'member_category_id' => $request->member_category_id,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'nida' => $request->nida,
                'tin' => $request->tin,
                'work_permit' => $request->work_permit,
                'admission_date' => $request->admission_date,
                'updated_by' => Auth()->id(),
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Member information updated successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    public function deletedmemberinformations()
    {
        try {

            $members = Member::with([
                    'memberCategory',
                    'company',
                    'branch',
                    'memberUser'
                ])
                ->where('Status', 'Deleted')
                ->latest()
                ->get();

            return view('in.members.members.deletedmemberinformations', compact('members'));

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    public function deletememberinformations($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $member = Member::findOrFail($id);

            $member->update([
                'Status' => 'Deleted',
                'updated_by' => Auth()->id(),
            ]);

            if ($member->memberUser) {

                $member->memberUser->update([
                    'Status' => 'Inactive',
                    'updated_by' => Auth()->id(),
                ]);

            }

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Member deleted successfully.'
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

    public function restorememberinformations($id)
    {
        try {

            $member = Member::findOrFail($id);

            $member->update([
                'Status' => 'Active',
                'updated_by' => Auth()->id(),
            ]);

            if ($member->memberUser) {

                $member->memberUser->update([
                    'Status' => 'Active',
                    'updated_by' => Auth()->id(),
                ]);

            }

            Alert::success(
                'Success ' . Auth()->user()->name,
                'Member restored successfully.'
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
