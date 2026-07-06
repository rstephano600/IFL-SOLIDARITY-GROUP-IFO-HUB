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
use App\Models\GroupCenter;
use App\Models\GroupMember;
use App\Models\Group;
use App\Models\Employee;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class GroupController extends Controller
{

    public function groupCenter()
    {
        try{
        $data = GroupCenter::where('Status', 'Active')->get();
        $employees = Employee::where('Status', 'Active')->get();
        return view('in.groups.centers.groupCenter', compact('data', 'employees'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storegroupCenter(Request $request)
    {
        $validated = $request->validate([
            'center_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'collection_officer_id' => 'nullable|exists:employees,id',
            'established_date' => 'nullable|date',
        ]);

        // Generate a unique center code based on group and date
        $center_name = $validated['center_name'];
        $validated['center_code'] = strtoupper('CTR-' . Str::slug($center_name, '-') . '-' . now()->format('Ymd') . '-' . rand(100, 999));

        $validated['created_by'] = auth()->id();
        $validated['User_id'] = auth()->id();
        $validated['is_active'] = true;
        try{
            GroupCenter::create($validated);
            Alert::success('Success ' . ' ' . Auth()->user()->name, 'You\'ve  Created Group Collection Center Successfully');
            return back();
            } catch (\Throwable $th) {
            dd($th->getMessage());
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790' . $th->getMessage());
            return back();
        }
    }

    public function editgroupCenter($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = GroupCenter::where('Status', 'Active')->findOrFail($id);
            $employees = Employee::where('Status', 'Active')->get();
            return view('in.groups.centers.editgroupCenter', compact('data', 'employees'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function updategroupCenter(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'center_name'            => 'required|string|max:255',
            'location'               => 'nullable|string|max:255',
            'area'                   => 'nullable|string|max:255',
            'description'            => 'nullable|string',
            'collection_officer_id'  => 'nullable|exists:employees,id',
            'established_date'       => 'nullable|date',
        ]);

        try {

            $groupCenter = GroupCenter::findOrFail($id);
            $groupCenter->update([
                'center_name'           => $request->center_name,
                'location'              => $request->location,
                'area'                  => $request->area,
                'description'           => $request->description,
                'collection_officer_id' => $request->collection_officer_id,
                'established_date'      => $request->established_date,
                'updated_by'            => auth()->id() ?? 1,

            ]);
            Alert::success(
                'Success ' . Auth()->user()->name,
                'You\'ve Updated Group Collection Center Successfully'
            );
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function destroygroupCenter($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $country = GroupCenter::findOrFail($id);
            $country->update(['Status' => 'Deleted']);
            Alert::success('Success' . ' ' .  Auth()->user()->name, 'You\'veremoved  Group Center Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function innactivegroupCenter()
    {
        try{
        $data = GroupCenter::where('Status', 'Deleted')->get();
        $employees = Employee::where('Status', 'Active')->get();
        return view('in.groups.centers.innactivegroupCenter', compact('data', 'employees'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function activategroupCenter($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $country = GroupCenter::findOrFail($id);
            $country->update(['Status' => 'Active']);
            Alert::success('Success' . ' ' .  Auth()->user()->name, 'You\'ve activated  Group Center Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function centerGroups()
    {
        try{
        $data = Group::where('Status', 'Active')->get();
        $groupcenters = GroupCenter::where('Status', 'Active')->get();
        $employees = Employee::where('Status', 'Active')->get();
        return view('in.groups.groups.centerGroups', compact('data', 'groupcenters', 'employees'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storecenterGroups(Request $request)
    {
        $validated = $request->validate([
            'group_center_id' => 'required|exists:group_centers,id',
            'group_name' => 'required|string|max:255',
            'group_type' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'credit_officer_id' => 'nullable|exists:employees,id',
            'registration_date' => 'nullable|date',
        ]);

        $namePart = strtoupper(substr(preg_replace('/\s+/', '', $request->group_name), 0, 3));
        $datePart = now()->format('Ymd'); // current date (e.g. 20251006)
        $randomPart = strtoupper(Str::random(3));

        $groupCode = "{$namePart}-{$datePart}-{$randomPart}";

        // Ensure code is unique (retry if not)
        while (\App\Models\Group::where('group_code', $groupCode)->exists()) {
            $randomPart = strtoupper(Str::random(3));
            $groupCode = "{$namePart}-{$datePart}-{$randomPart}";
        }

        $validated['group_code'] = $groupCode;
        $validated['created_by'] = auth()->id() ?? 1;
        $validated['User_id'] = auth()->id() ?? 1;

        try{
            Group::create($validated);
            Alert::success('Success ' . ' ' . Auth()->user()->name, 'You\'ve  Created Group' . $validated['group_name'] . ' Successfully');
            return back();
            } catch (\Throwable $th) {
            dd($th->getMessage());
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790' . $th->getMessage());
            return back();
        }
    }

    public function editcenterGroups($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = Group::findOrFail($id);
            $groupcenters = GroupCenter::where('Status', 'Active')->get();
            $employees = Employee::where('Status', 'Active')->get();
            return view('in.groups.groups.editcenterGroups', compact('data', 'employees', 'groupcenters'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function updatecenterGroups(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([

            'group_center_id'    => 'required|exists:group_centers,id',
            'group_name'         => 'required|string|max:255',
            'group_type'         => 'nullable|string|max:255',
            'location'           => 'nullable|string|max:255',
            'description'        => 'nullable|string',
            'credit_officer_id'  => 'nullable|exists:employees,id',
            'registration_date'  => 'nullable|date',

        ]);

        try {

            $centerGroup = Group::findOrFail($id);
            $centerGroup->update([

                'group_center_id'   => $request->group_center_id,
                'group_name'        => $request->group_name,
                'group_type'        => $request->group_type,
                'location'          => $request->location,
                'description'       => $request->description,
                'credit_officer_id' => $request->credit_officer_id,
                'registration_date' => $request->registration_date,
                'updated_by'        => auth()->id() ?? 1,

            ]);
            Alert::success(
                'Success ' . Auth()->user()->name,
                'You\'ve Updated Group Collection Successfully'
            );
            return back();
        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    public function destroycenterGroups($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $country = Group::findOrFail($id);
            $country->update(['Status' => 'Deleted']);
            Alert::success('Success' . ' ' .  Auth()->user()->name, 'You\'veremoved  Group Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function innactivecenterGroups()
    {
        try{
        $data = Group::where('Status', 'Deleted')->get();
        $groupcenters = GroupCenter::where('Status', 'Active')->get();
        $employees = Employee::where('Status', 'Active')->get();
        return view('in.groups.groups.innactivecenterGroups', compact('data', 'groupcenters', 'employees'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function activatecenterGroups($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $country = Group::findOrFail($id);
            $country->update(['Status' => 'Active']);
            Alert::success('Success' . ' ' .  Auth()->user()->name, 'You\'ve Activated  Group Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function innactivegroupMembers()
    {
        try{
        $data = Client::where('Status', 'Innactive')->latest()->get();
        $groups = Group::where('Status', 'Active')->get();
        $groupcenters = GroupCenter::where('Status', 'Active')->get();
        $employees = Employee::where('Status', 'Active')->get();
        $groupmembers = GroupMember::where('Status', 'Active')->get();
        return view('in.groups.members.innactivegroupMembers', compact('data', 'groups', 'groupcenters', 'employees', 'groupmembers'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }
    
    public function clientinformations()
    {
        try{
       $data = Client::where('Status', 'Active')->latest()->get();
        $groups = Group::where('Status', 'Active')->get();
        $groupcenters = GroupCenter::where('Status', 'Active')->get();
        $employees = Employee::where('Status', 'Active')->get();
        $groupmembers = GroupMember::where('Status', 'Active')->get();
        return view('in.groups.members.clientinformations', compact('data', 'groups', 'groupcenters', 'employees', 'groupmembers'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storeclientinformations(Request $request)
    {
        $validated = $request->validate([

            // USER INFORMATIONS
            'FirstName'                     => 'required|string|max:255',
            'MiddleName'                    => 'nullable|string|max:255',
            'LastName'                      => 'required|string|max:255',
            'email'                         => 'nullable|email|unique:users,email',
            'phone'                         => 'required|string|max:20|unique:users,phone',
            'Dob'                           => 'nullable|date',
            'gender'                        => 'nullable|in:male,female',

            // CLIENT INFORMATIONS
            // 'group_center_id'               => 'nullable|exists:group_centers,id',
            'group_id'                      => 'nullable|exists:groups,id',
            // 'credit_officer_id'             => 'nullable|exists:employees,id',
            'client_type'                   => 'required|string|max:255',
            'business_name'                 => 'nullable|string|max:255',
            'business_capital'              => 'nullable|numeric|min:0',
            'business_income'               => 'nullable|numeric|min:0',
            'business_location'             => 'nullable|string|max:255',
            'partner_in_business'           => 'nullable|string|max:255',
            'business_registration_number'  => 'nullable|string|max:255',
            'tax_identification_number'     => 'nullable|string|max:255',
            'industry_sector'               => 'nullable|string|max:255',
            'years_in_business'             => 'nullable|integer|min:0',
            'months_in_business'            => 'nullable|integer|min:0',
            'number_of_employees'           => 'nullable|integer|min:0',
            'alternative_phone'             => 'nullable|string|max:20',
            'address_line1'                 => 'nullable|string|max:255',
            'address_line2'                 => 'nullable|string|max:255',
            'city'                          => 'nullable|string|max:255',
            'state_province'                => 'nullable|string|max:255',
            'postal_code'                   => 'nullable|string|max:20',
            'country_id'                    => 'nullable|exists:account_countries,id',
            'national_id'                   => 'nullable|string|max:255',
            'marital_status'                => 'nullable|in:Single,Married,Divorced,Widowed',
            'spouse_name'                   => 'nullable|string|max:255',
            'other_name'                    => 'nullable|string|max:255',
            'street_leader'                 => 'nullable|string|max:255',
            'profile_picture'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'sign_image'                    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'credit_score'                  => 'nullable|numeric|min:0|max:1000',
            'credit_rating'                 => 'nullable|string|max:50',
            'risk_category'                 => 'nullable|string|max:50',
            'status'                        => 'nullable|string|max:50',
            'blacklist_reason'              => 'nullable|string|max:255',
            'kyc_completed'                 => 'nullable|boolean',

        ]);

        DB::beginTransaction();

        try {

            // SAFE DEFAULTS
            $validated['credit_officer_id'] = null;
            $validated['group_center_id']   = null;
            $validated['kyc_completed_at']  = $request->kyc_completed ? now() : null;

            // RESOLVE GROUP → AUTO-FILL CREDIT OFFICER & CENTER
            if (!empty($validated['group_id'])) {
                $group = Group::findOrFail($validated['group_id']);
                $validated['credit_officer_id'] = $group->credit_officer_id;
                $group_center_id   = $group->group_center_id;
                $collection_officer_id   = $group->collection_officer_id;
            }

            // SAFE NULLABLE FIELDS
            $middleName = $validated['MiddleName'] ?? null;
            $status     = $validated['status']     ?? 'Active';
            $kycDone    = $request->boolean('kyc_completed', false);

            // USERNAME GENERATION (uses max ID to avoid collision)
            $lastUserId = User::max('id') ?? 0;
            $userCount  = $lastUserId + 1;
            $year       = date('Y');

            $FName    = strtoupper(substr($validated['FirstName'], 0, 1));
            $MName    = !empty($middleName) ? strtoupper(substr($middleName, 0, 1)) : '';
            $LName    = strtoupper(substr($validated['LastName'], 0, 1));
            $initials = $FName . $MName . $LName;

            $username = 'ArBif/' . $initials . '/CLIENT/' . $year . '/' . str_pad($userCount, 5, '0', STR_PAD_LEFT);

            $fullName = $validated['LastName'] . ', ' .
                        $validated['FirstName'] .
                        (!empty($middleName) ? ' ' . $middleName : '');

            // CREATE USER
            $user = User::create([
                'username'   => $username,
                'name'       => $fullName,
                'FirstName'  => $validated['FirstName'],
                'MiddleName' => $middleName,
                'LastName'   => $validated['LastName'],
                'gender'     => $validated['gender']  ?? null,
                'Dob'        => $validated['Dob']      ?? null,
                'email'      => $validated['email']    ?? null,
                'phone'      => $validated['phone'],
                'password'   => Hash::make('AiBifCL1234'),
                'Role'       => 'client',
                'User_id'    => auth()->id(),
            ]);

            // PROFILE PICTURE
            $profilePicturePath = null;
            if ($request->hasFile('profile_picture')) {
                $profilePicturePath = $request->file('profile_picture')
                    ->store('Clients/profile_pictures', 'public');
            }

            // SIGN IMAGE
            $signImagePath = null;
            if ($request->hasFile('sign_image')) {
                $signImagePath = $request->file('sign_image')
                    ->store('Clients/signatures', 'public');
            }

            // CLIENT CODE GENERATION
            $clientCode = 'CLI-' . now()->format('Ymd') . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);

            // CREATE CLIENT — linked to the newly created user
            Client::create([
                'client_id'                       => $user->id,   // ← FIXED: was auth()->id()
                'User_id'                       => auth()->id(), // ← creator/admin ID if your schema needs it
                'group_center_id'               => $group_center_id,
                'group_id'                      => $validated['group_id']          ?? null,
                'credit_officer_id'             => $collection_officer_id,

                'client_code'                   => $clientCode,
                'client_type'                   => $validated['client_type'],

                'business_name'                 => $validated['business_name']                ?? null,
                'business_capital'              => $validated['business_capital']             ?? null,
                'business_income'               => $validated['business_income']              ?? null,
                'business_location'             => $validated['business_location']            ?? null,
                'partner_in_business'           => $validated['partner_in_business']          ?? null,
                'business_registration_number'  => $validated['business_registration_number'] ?? null,
                'tax_identification_number'     => $validated['tax_identification_number']    ?? null,
                'industry_sector'               => $validated['industry_sector']              ?? null,
                'years_in_business'             => $validated['years_in_business']            ?? null,
                'months_in_business'            => $validated['months_in_business']           ?? null,
                'number_of_employees'           => $validated['number_of_employees']          ?? null,

                'alternative_phone'             => $validated['alternative_phone']            ?? null,
                'address_line1'                 => $validated['address_line1']                ?? null,
                'address_line2'                 => $validated['address_line2']                ?? null,
                'city'                          => $validated['city']                         ?? null,
                'state_province'                => $validated['state_province']               ?? null,
                'postal_code'                   => $validated['postal_code']                  ?? null,
                'country_id'                    => $validated['country_id']                   ?? null,

                'national_id'                   => $validated['national_id']                  ?? null,
                'marital_status'                => $validated['marital_status']               ?? null,
                'spouse_name'                   => $validated['spouse_name']                  ?? null,
                'other_name'                    => $validated['other_name']                   ?? null,
                'street_leader'                 => $validated['street_leader']                ?? null,

                'profile_picture'               => $profilePicturePath,
                'sign_image'                    => $signImagePath,

                'credit_score'                  => $validated['credit_score']                 ?? null,
                'credit_rating'                 => $validated['credit_rating']                ?? null,
                'risk_category'                 => $validated['risk_category']                ?? null,
                'status'                        => $status,
                'blacklist_reason'              => $validated['blacklist_reason']             ?? null,

                'kyc_completed'                 => $kycDone ? 1 : 0,
                'kyc_completed_at'              => $validated['kyc_completed_at'],
            ]);

            DB::commit();

            Alert::success(
                'Success ' . auth()->user()->name,
                'Client ' . $fullName . ' has been created successfully.'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error(
                'Error — ' . auth()->user()->name,
                $th->getMessage()
            );
            return back();
        }
    }

    public function showclientinformations($id)
    {
        try{
            $client = Client::with([
                'client',        // User relationship
                'group',
                'groupCenter',
                'loanOfficer',
            ])->findOrFail(decrypt($id));
            return view('in.groups.members.showclientinformations', compact('client'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }


    public function editclientinformations($id)
    {
        try{
            $client = Client::with(['client', 'group', 'groupCenter', 'loanOfficer'])
                            ->findOrFail(decrypt($id));

            $countries    = AccountCountry::where('Status', 'Active')->get();
            $groups = Group::where('Status', 'Active')->get();
            $groupcenters = GroupCenter::where('Status', 'Active')->get();
            $employees = Employee::where('Status', 'Active')->get();
            $groupmembers = GroupMember::where('Status', 'Active')->get();
            return view('in.groups.members.editclientinformations', compact(
                'client',
                'groupcenters',
                'groups',
                'employees',
                'countries'
            ));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }


    public function updateclientinformations(Request $request, $id)
    {
        $client = Client::with('client')->findOrFail(decrypt($id));

        // Guard: check if related user exists
        if (!$client->client) {
            Alert::error('Error', 'This client has no linked user account. Please contact support.');
            return back();
        }

        $userId = $client->client->id;

        $validated = $request->validate([

            // USER FIELDS
            'FirstName'                     => 'required|string|max:255',
            'MiddleName'                    => 'nullable|string|max:255',
            'LastName'                      => 'required|string|max:255',
            'email'                         => 'nullable|email|unique:users,email,' . $userId,
            'phone'                         => 'required|string|max:20|unique:users,phone,' . $userId,
            'Dob'                           => 'nullable|date',
            'gender'                        => 'nullable|in:male,female',

            // CLIENT FIELDS
            'group_center_id'               => 'nullable|exists:group_centers,id',
            'group_id'                      => 'nullable|exists:groups,id',
            'credit_officer_id'             => 'nullable|exists:employees,id',
            'client_type'                   => 'required|string|max:255',
            'business_name'                 => 'nullable|string|max:255',
            'business_capital'              => 'nullable|numeric|min:0',
            'business_income'               => 'nullable|numeric|min:0',
            'business_location'             => 'nullable|string|max:255',
            'partner_in_business'           => 'nullable|string|max:255',
            'business_registration_number'  => 'nullable|string|max:255',
            'tax_identification_number'     => 'nullable|string|max:255',
            'industry_sector'               => 'nullable|string|max:255',
            'years_in_business'             => 'nullable|integer|min:0',
            'months_in_business'            => 'nullable|integer|min:0',
            'number_of_employees'           => 'nullable|integer|min:0',
            'alternative_phone'             => 'nullable|string|max:20',
            'address_line1'                 => 'nullable|string|max:255',
            'address_line2'                 => 'nullable|string|max:255',
            'city'                          => 'nullable|string|max:255',
            'state_province'                => 'nullable|string|max:255',
            'postal_code'                   => 'nullable|string|max:20',
            'country_id'                    => 'nullable|exists:account_countries,id',
            'national_id'                   => 'nullable|string|max:255',
            'marital_status'                => 'nullable|in:Single,Married,Divorced,Widowed',
            'spouse_name'                   => 'nullable|string|max:255',
            'other_name'                    => 'nullable|string|max:255',
            'street_leader'                 => 'nullable|string|max:255',
            'profile_picture'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'sign_image'                    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'credit_score'                  => 'nullable|numeric|min:0|max:1000',
            'credit_rating'                 => 'nullable|string|max:50',
            'risk_category'                 => 'nullable|string|max:50',
            'status'                        => 'required|string|max:50',
            'blacklist_reason'              => 'nullable|string|max:255',
            'kyc_completed'                 => 'nullable|boolean',

        ]);

        DB::beginTransaction();

        try {

            // ── RESOLVE GROUP → AUTO-FILL CREDIT OFFICER & CENTER ──────
            $validated['credit_officer_id'] = $client->credit_officer_id; // keep existing by default
            $validated['group_center_id']   = $client->group_center_id;

            if (!empty($validated['group_id'])) {
                $group = Group::findOrFail($validated['group_id']);
                $validated['credit_officer_id'] = $group->credit_officer_id;
                $validated['group_center_id']   = $group->group_center_id;
            }

            $middleName = $validated['MiddleName'] ?? null;
            $kycDone    = $request->boolean('kyc_completed', false);

            // ── UPDATE USER ─────────────────────────────────────────────
            $fullName = $validated['LastName'] . ', ' .
                        $validated['FirstName'] .
                        (!empty($middleName) ? ' ' . $middleName : '');

            $client->client->update([
                'name'       => $fullName,
                'FirstName'  => $validated['FirstName'],
                'MiddleName' => $middleName,
                'LastName'   => $validated['LastName'],
                'gender'     => $validated['gender']  ?? null,
                'Dob'        => $validated['Dob']      ?? null,
                'email'      => $validated['email']    ?? null,
                'phone'      => $validated['phone'],
                'updated_by' => auth()->id(),
            ]);

            // ── PROFILE PICTURE ─────────────────────────────────────────
            $profilePicturePath = $client->profile_picture; // keep old by default

            if ($request->hasFile('profile_picture')) {

                // Delete old file if exists
                if ($client->profile_picture && Storage::disk('public')->exists($client->profile_picture)) {
                    Storage::disk('public')->delete($client->profile_picture);
                }

                $profilePicturePath = $request->file('profile_picture')
                    ->store('Clients/profile_pictures', 'public');
            }

            // ── SIGNATURE IMAGE ─────────────────────────────────────────
            $signImagePath = $client->sign_image; // keep old by default

            if ($request->hasFile('sign_image')) {

                // Delete old file if exists
                if ($client->sign_image && Storage::disk('public')->exists($client->sign_image)) {
                    Storage::disk('public')->delete($client->sign_image);
                }

                $signImagePath = $request->file('sign_image')
                    ->store('Clients/signatures', 'public');
            }

            // ── KYC TIMESTAMP ───────────────────────────────────────────
            // Only set kyc_completed_at when KYC is newly being marked complete
            $kycCompletedAt = $client->kyc_completed_at;
            if ($kycDone && !$client->kyc_completed) {
                $kycCompletedAt = now();
            } elseif (!$kycDone) {
                $kycCompletedAt = null;
            }

            // ── UPDATE CLIENT ────────────────────────────────────────────
            $client->update([
                'group_center_id'               => $validated['group_center_id'],
                'group_id'                      => $validated['group_id']                      ?? null,
                'credit_officer_id'             => $validated['credit_officer_id'],

                'client_type'                   => $validated['client_type'],

                'business_name'                 => $validated['business_name']                 ?? null,
                'business_capital'              => $validated['business_capital']              ?? null,
                'business_income'               => $validated['business_income']               ?? null,
                'business_location'             => $validated['business_location']             ?? null,
                'partner_in_business'           => $validated['partner_in_business']           ?? null,
                'business_registration_number'  => $validated['business_registration_number']  ?? null,
                'tax_identification_number'     => $validated['tax_identification_number']     ?? null,
                'industry_sector'               => $validated['industry_sector']               ?? null,
                'years_in_business'             => $validated['years_in_business']             ?? null,
                'months_in_business'            => $validated['months_in_business']            ?? null,
                'number_of_employees'           => $validated['number_of_employees']           ?? null,

                'alternative_phone'             => $validated['alternative_phone']             ?? null,
                'address_line1'                 => $validated['address_line1']                 ?? null,
                'address_line2'                 => $validated['address_line2']                 ?? null,
                'city'                          => $validated['city']                          ?? null,
                'state_province'                => $validated['state_province']                ?? null,
                'postal_code'                   => $validated['postal_code']                   ?? null,
                'country_id'                    => $validated['country_id']                    ?? null,

                'national_id'                   => $validated['national_id']                   ?? null,
                'marital_status'                => $validated['marital_status']                ?? null,
                'spouse_name'                   => $validated['spouse_name']                   ?? null,
                'other_name'                    => $validated['other_name']                    ?? null,
                'street_leader'                 => $validated['street_leader']                 ?? null,

                'profile_picture'               => $profilePicturePath,
                'sign_image'                    => $signImagePath,

                'credit_score'                  => $validated['credit_score']                  ?? null,
                'credit_rating'                 => $validated['credit_rating']                 ?? null,
                'risk_category'                 => $validated['risk_category']                 ?? null,
                'status'                        => $validated['status'],
                'blacklist_reason'              => $validated['blacklist_reason']              ?? null,

                'kyc_completed'                 => $kycDone ? 1 : 0,
                'kyc_completed_at'              => $kycCompletedAt,
            ]);

            DB::commit();

            Alert::success(
                'Updated! ' . auth()->user()->name,
                'Client ' . $fullName . ' has been updated successfully.'
            );

            return redirect()->route('showclientinformations', encrypt($client->id));

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error(
                'Error — ' . auth()->user()->name,
                $th->getMessage()
            );
            return back()->withInput();
        }
    }

    public function groupMembers()
    {
        try{
            $data = GroupMember::where('Status', 'Active')->get();
            $clients = Client::where('Status', 'Active')->get();
            $groups = Group::where('Status', 'Active')->get();
            $groupcenters = GroupCenter::where('Status', 'Active')->get();
            $employees = Employee::where('Status', 'Active')->get();
            return view('in.groups.members.groupMembers', compact('data', 'groups', 'groupcenters', 'employees', 'clients'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function storegroupMembers(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'group_id' => 'required|exists:groups,id',
            'client_id' => 'required|exists:clients,id',
            'role_in_group' => 'nullable|string|max:255',
        ]);

        $group = Group::findOrFail($validated['group_id']);

        // Generate unique member code
        $groupCodePart = strtoupper(substr($group->group_name, 0, 3));
        $employeePart = $group->group_name;
        $randomPart = strtoupper(Str::random(3));
        $memberCode = "{$groupCodePart}-{$employeePart}-{$randomPart}";

        while (GroupMember::where('member_code', $memberCode)->exists()) {
            $randomPart = strtoupper(Str::random(3));
            $memberCode = "{$groupCodePart}-{$employeePart}-{$randomPart}";
        }

        $employeeId =  auth()->id();
        try{
            GroupMember::create([
                'group_id' => $group->id,
                'employee_id' => $employeeId,
                'client_id' => $validated['client_id'],
                'role_in_group' => $validated['role_in_group'] ?? null,
                'member_code' => $memberCode,
                'created_by' => auth()->id() ?? 0,
                'User_id' => auth()->id() ?? 1,
            ]);
            
            Alert::success('Success ' . ' ' . Auth()->user()->name, 'You\'ve  Created Group Member ' . $validated['member_code'] . ' Successfully');
            return back();
            } catch (\Throwable $th) {
            dd($th->getMessage());
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790' . $th->getMessage());
            return back();
        }
    }

    public function editgroupMembers()
    {
        try {
            $id = Crypt::decrypt($id);
            $data = GroupMember::findOrFail($id);
            $groups = Group::where('Status', 'Active')->get();
            $groupcenters = GroupCenter::where('Status', 'Active')->get();
            $employees = Employee::where('Status', 'Active')->get();
            return view('in.groups.members.editgroupMembers', compact('data', 'groups', 'groupcenters', 'employees'));
        } catch (\Throwable $th) {
            Alert::error('Sorry! '   . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }

    public function destroygroupMembers($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $country = GroupMember::findOrFail($id);
            $country->update(['Status' => 'Deleted']);
            Alert::success('Success' . ' ' .  Auth()->user()->name, 'You\'veremoved  Group Member Successfully');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Sorry! '  . ' ' .  Auth()->user()->name, 'Technical error exists, please contact Technichal for support Tel:+255657856790');
            return back();
        }
    }














}
