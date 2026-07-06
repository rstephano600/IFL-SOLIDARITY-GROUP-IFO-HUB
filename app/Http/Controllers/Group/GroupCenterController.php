<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use App\Models\GroupCenter;
use App\Models\Group;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\LogActivity;

class GroupCenterController extends Controller
{

public function index(Request $request)
{
    // Search and filter inputs
    $search = $request->input('search');
    $status = $request->input('status');
    $groupId = $request->input('group_id');

    $user = auth()->user(); // Logged-in user

    // ✅ Base query
    $query = GroupCenter::with(['groups', 'collectionOfficer']);

    /**
     * ✅ Restrict by role
     * - If the logged-in user is a loan officer,
     *   show only centers where their employee record matches collection_officer_id
     */
    if ($user->role === 'loanofficer') {
        $employee = Employee::where('user_id', $user->id)->first();

        if ($employee) {
            $query->where('collection_officer_id', $employee->id);
        } else {
            // If not linked to an employee, show nothing
            $query->whereRaw('1 = 0');
        }
    }

    // ✅ Search by center name, code, or location
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('center_name', 'like', "%{$search}%")
              ->orWhere('center_code', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%");
        });
    }

    // ✅ Filter by active/inactive status
    if ($status !== null && $status !== '') {
        $query->where('is_active', $status);
    }

    // ✅ Filter by specific group ID (if selected)
    if ($groupId) {
        $query->whereHas('groups', function ($q) use ($groupId) {
            $q->where('id', $groupId);
        });
    }

    // ✅ Fetch centers and groups
    $centers = $query->orderBy('id', 'desc')->paginate(10);
    $groups = Group::select('id', 'group_name')->orderBy('group_name')->get();

    return view('in.groups.group_centers.index', compact('centers', 'groups', 'search', 'status', 'groupId'));
}




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('is_active', true)->get();

        return view('in.groups.group_centers.create', compact( 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

        $validated['created_by'] = auth()->id() ?? 1;
        $validated['is_active'] = true;

        GroupCenter::create($validated);

        return redirect()->route('group_centers.index')->with('success', 'Group Center created successfully!');
    }

    /**
     * Display the specified resource.
     */
public function show(GroupCenter $groupCenter)
{
    $groupCenter->load([
        'collectionOfficer',
        'groups.loanOfficer',
    ]);

    $creditOfficers = \App\Models\Employee::where('position', 'Loan Officer')->get();

    return view('in.groups.group_centers.show', compact('groupCenter','creditOfficers'));
}



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupCenter $groupCenter)
    {
        $employees = Employee::where('is_active', true)->get();
        return view('in.groups.group_centers.edit', compact('groupCenter', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GroupCenter $groupCenter)
    {
        $validated = $request->validate([
            'center_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'collection_officer_id' => 'nullable|exists:employees,id',
            'established_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = auth()->id() ?? 1;

        $groupCenter->update($validated);

        return redirect()->route('group_centers.index')->with('success', 'Group Center updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupCenter $groupCenter)
    {
        $groupCenter->delete();

        return redirect()->route('group_centers.index')->with('success', 'Group Center deleted successfully!');
    }
}




    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');

        $groups = Group::query()
            ->when($search, function ($query, $search) {
                $query->where('group_name', 'like', "%{$search}%")
                      ->orWhere('group_code', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
            })
            // Restrict Loan Officer's view
            ->when($user->hasRole('loanofficer'), function ($query) use ($user) {
                // Assuming `Employee` is linked to `User` via user_id
                $employee = Employee::where('user_id', $user->id)->first();
                if ($employee) {
                    $query->where('credit_officer_id', $employee->id);
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('in.groups.index', compact('groups', 'search'));
    }


    /**
     * Show the form for creating a new group.
     */

    public function create(Request $request)
    {
    $groupCenters = GroupCenter::where('is_active', 1)->get();
    $creditOfficers = Employee::where('is_active', true)->get();

    $selectedCenterId = $request->get('center_id'); // capture if passed from center show page
    $selectedCreditOfficerId = $request->get('credit_officer_id'); // capture if passed from center show page

    return view('in.groups.create', compact('creditOfficers', 'groupCenters', 'selectedCenterId', 'selectedCreditOfficerId'));
   }


    /**
     * Store a newly created group.
     */
    public function store(Request $request)
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

    // Auto-generate unique group code based on name and date
    $namePart = strtoupper(substr(preg_replace('/\s+/', '', $request->group_name), 0, 3)); // first 3 letters (no spaces)
    $datePart = now()->format('Ymd'); // current date (e.g. 20251006)
    $randomPart = strtoupper(Str::random(3)); // random 3 letters

    $groupCode = "{$namePart}-{$datePart}-{$randomPart}";

    // Ensure code is unique (retry if not)
    while (\App\Models\Group::where('group_code', $groupCode)->exists()) {
        $randomPart = strtoupper(Str::random(3));
        $groupCode = "{$namePart}-{$datePart}-{$randomPart}";
    }

    $validated['group_code'] = $groupCode;
    $validated['created_by'] = auth()->id() ?? 1;

    Group::create($validated);

    return back()->with('success', 'Group ' . $validated['group_name'] . ' created successfully!');
    }


    /**
     * Display the specified group.
     */
public function show(Group $group)
{
    // Eager load clients to avoid N+1 queries
    $group->load('clients');

    return view('in.groups.show', compact('group'));
}

    /**
     * Show the form for editing the specified group.
     */
    public function edit(Group $group)
    {
        $groupCenters = GroupCenter::where('is_active', 1)->get();
        $creditOfficers = Employee::where('is_active', true)->get();
        return view('in.groups.edit', compact('group', 'creditOfficers', 'groupCenters'));
    }

    /**
     * Update the specified group.
     */
    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'group_center_id' => 'required|exists:group_centers,id',
            'group_name' => 'required|string|max:255',
            'group_type' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'credit_officer_id' => 'nullable|exists:employees,id',
            'registration_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = auth()->id() ?? 1;

        $group->update($validated);

        return redirect()->route('groups.index')->with('success', 'Group updated successfully!');
    }

    

    /**
     * Remove the specified group.
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Group deleted successfully!');
    }