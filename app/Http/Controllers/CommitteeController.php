<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Committee;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\CommitteePosition;
use App\Models\CommitteeMember;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CommitteeController extends Controller
{
    /**
     * Display a listing of active committees.committees.
     */
    public function committee(Request $request)
    {
        try {
            $query = Committee::where('Status', 'Active')
                ->with([
                    'chairman',
                    'secretary',
                    'company',
                    'branch',
                    'user'
                ])
                ->withCount('activeMembers');

            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('committee_name', 'like', "%{$search}%")
                      ->orWhere('committee_code', 'like', "%{$search}%")
                      ->orWhere('ReferenceNo', 'like', "%{$search}%");
                });
            }

            $data = $query->latest('id')->paginate(15)->withQueryString();

            $branchies = Branch::where('Status', 'Active')->with('company')->get();
            $users = User::where('Status', 'Active')->orderBy('name')->get();

            return view('in.committees.committees.committee', compact('data', 'branchies', 'users'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly created committee in storage.
     */
    public function storeCommittee(Request $request)
    {
        $request->validate([
            'branch_id'         => 'required|integer|exists:branchies,id',
            'committee_name'    => 'required|string|max:255',
            'committee_code'    => 'nullable|string|max:50|unique:committees,committee_code',
            'chairman_user_id'  => 'nullable|integer|exists:users,id',
            'secretary_user_id' => 'nullable|integer|exists:users,id',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'description'       => 'nullable|string',
        ]);

        DB::beginTransaction();
        // try {
            $user = Auth::user();
            $branch = Branch::findOrFail($request->branch_id);

            // Generate Branch Prefix for Reference No
            $branchWords = explode(' ', strtoupper($branch->branch_name));
            $branchPrefix = '';
            foreach ($branchWords as $word) {
                $branchPrefix .= substr($word, 0, 1);
            }
            $branchPrefix = substr($branchPrefix, 0, 3);

            // Generate Unique Reference Number

            $year = date('Y');

            $prefix = 'IFLSG/COM/' . $branchPrefix . '/' . $year . '/';

            $lastCommittee = Committee::where('ReferenceNo', 'like', $prefix . '%')
                ->orderByDesc('id')
                ->first();

            if ($lastCommittee) {
                $lastNumber = (int) substr($lastCommittee->ReferenceNo, -4);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            do {
                $generatedRefNo = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber++;
            } while (Committee::where('ReferenceNo', $generatedRefNo)->exists());

            // Assign the generated reference number
            $data['ReferenceNo'] = $generatedRefNo;

            // Generate Committee Code if empty
            $committeeCode = $request->committee_code 
                ? strtoupper($request->committee_code) 
                : 'IFSG-COM-CODE-' . mt_rand(100, 999);

            Committee::create([
                'ReferenceNo'       => $generatedRefNo,
                'committee_code'    => $committeeCode,
                'committee_name'    => strtoupper($request->committee_name),
                'description'       => $request->description,
                'chairman_user_id'  => $request->chairman_user_id,
                'secretary_user_id' => $request->secretary_user_id,
                'start_date'        => $request->start_date,
                'end_date'          => $request->end_date,
                'company_id'        => $branch->company_id,
                'branch_id'         => $branch->id,
                'User_id'           => $user->id,
                'Status'            => 'Active',
                'AuditingStatus'    => 'Pending',
                'ReportStatus'      => 'Pending',
                'created_by'        => $user->id,
                'updated_by'        => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Committee Registered Successfully'
            );

            return back();

        // } catch (\Throwable $th) {
        //     DB::rollBack();

        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical Support Tel:+255657856790'
        //     );

        //     return back()->withInput();
        // }
    }

    /**
     * Show form for editing the specified committee.
     */
    public function editCommittee($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = Committee::findOrFail($id);

            $branchies = Branch::where('Status', 'Active')->with('company')->get();
            $users = User::where('Status', 'Active')->orderBy('name')->get();

            return view('in.committees.committees.editCommittee', compact('data', 'branchies', 'users'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified committee in storage.
     */
    public function updateCommittee(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'branch_id'         => 'required|integer|exists:branchies,id',
            'committee_name'    => 'required|string|max:255',
            'committee_code'    => 'required|string|max:50|unique:committees,committee_code,' . $id,
            'chairman_user_id'  => 'nullable|integer|exists:users,id',
            'secretary_user_id' => 'nullable|integer|exists:users,id',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'description'       => 'nullable|string',
        ]);

        DB::beginTransaction();
        // try {
            $committee = Committee::findOrFail($id);
            $branch = Branch::findOrFail($request->branch_id);

            $committee->update([
                'branch_id'         => $branch->id,
                'company_id'        => $branch->company_id,
                'committee_code'    => strtoupper($request->committee_code),
                'committee_name'    => strtoupper($request->committee_name),
                'description'       => $request->description,
                'chairman_user_id'  => $request->chairman_user_id,
                'secretary_user_id' => $request->secretary_user_id,
                'start_date'        => $request->start_date,
                'end_date'          => $request->end_date,
                'updated_by'        => Auth::id(),
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Committee Details Updated Successfully'
            );

            return back();

        // } catch (\Throwable $th) {
        //     DB::rollBack();

        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical Support Tel:+255657856790'
        //     );

        //     return back()->withInput();
        // }
    }

    /**
     * Display profile/details of a specific committee along with members and requests.
     */
    public function viewCommittee($id)
    {
        // try {
            $id = Crypt::decrypt($id);

            $data = Committee::with([
                'chairman',
                'secretary',
                'company',
                'branch',
                'user',
                'members.user',
                'activeMembers.user',
                'serviceRequests'
            ])->findOrFail($id);

            return view('in.committees.committees.viewCommittee', compact('data'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical Support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Soft delete the specified committee.
     */
    public function destroyCommittee($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $committee = Committee::findOrFail($id);

            $committee->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $committee->delete();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Committee Removed Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted committees.committees.
     */
    public function deletedCommittee()
    {
        try {
            $data = Committee::onlyTrashed()
                ->with(['chairman', 'secretary', 'company', 'branch'])
                ->latest('deleted_at')
                ->get();

            return view('in.committees.committees.deletedCommittee', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted committee back to Active status.
     */
    public function restoreCommittee($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $committee = Committee::withTrashed()->findOrFail($id);

            $committee->restore();

            $committee->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Committee Restored Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }


    /**
     * Display a listing of active committee positions.
     */
    public function committeePosition(Request $request)
    {
        try {
            $query = CommitteePosition::where('Status', 'Active')
                ->with(['company', 'branch', 'user'])
                ->withCount('committeeMembers');

            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('position_name', 'like', "%{$search}%")
                      ->orWhere('position_code', 'like', "%{$search}%")
                      ->orWhere('ReferenceNo', 'like', "%{$search}%");
                });
            }

            $data = $query->latest('id')->paginate(15)->withQueryString();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.committees.committeePosition.committeePosition', compact('data', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly created committee position in storage.
     */
    public function storeCommitteePosition(Request $request)
    {
        $request->validate([
            'branch_id'     => 'required|integer|exists:branchies,id',
            'position_name' => 'required|string|max:255',
            'position_code' => 'nullable|string|max:50|unique:committee_positions,position_code',
            'description'   => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $branch = Branch::findOrFail($request->branch_id);

            // Generate Branch Abbreviation for Reference No
            $branchWords = explode(' ', strtoupper($branch->branch_name));
            $branchPrefix = '';
            foreach ($branchWords as $word) {
                $branchPrefix .= substr($word, 0, 1);
            }
            $branchPrefix = substr($branchPrefix, 0, 3);

            // Generate Unique Incremental Reference Number
            $year = date('Y');

            $prefix = 'IFLSG/CPOS/' . $branchPrefix . '/' . $year . '/';

            $lastPosition = CommitteePosition::where('ReferenceNo', 'like', $prefix . '%')
                ->orderByDesc('id')
                ->first();

            if ($lastPosition) {
                $lastNumber = (int) substr($lastPosition->ReferenceNo, -4);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            do {
                $generatedRefNo = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber++;
            } while (
                CommitteePosition::where('ReferenceNo', $generatedRefNo)->exists()
            );

            // Assign the generated reference number
            $data['ReferenceNo'] = $generatedRefNo;

            // Generate Position Code if empty
            $positionCode = $request->position_code 
                ? strtoupper($request->position_code) 
                : 'POS-' . mt_rand(100, 999);

            CommitteePosition::create([
                'ReferenceNo'    => $generatedRefNo,
                'position_code'  => $positionCode,
                'position_name'  => strtoupper($request->position_name),
                'description'    => $request->description,
                'company_id'     => $branch->company_id,
                'branch_id'      => $branch->id,
                'User_id'        => $user->id,
                'Status'         => 'Active',
                'AuditingStatus' => 'Pending',
                'ReportStatus'   => 'Pending',
                'created_by'     => $user->id,
                'updated_by'     => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Committee Position Registered Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    /**
     * Show form for editing the specified committee position.
     */
    public function editCommitteePosition($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = CommitteePosition::findOrFail($id);
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.committees.committeePosition.editCommitteePosition', compact('data', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified committee position in storage.
     */
    public function updateCommitteePosition(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'branch_id'     => 'required|integer|exists:branchies,id',
            'position_name' => 'required|string|max:255',
            'position_code' => 'required|string|max:50|unique:committee_positions,position_code,' . $id,
            'description'   => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $position = CommitteePosition::findOrFail($id);
            $branch = Branch::findOrFail($request->branch_id);

            $position->update([
                'branch_id'     => $branch->id,
                'company_id'    => $branch->company_id,
                'position_code' => strtoupper($request->position_code),
                'position_name' => strtoupper($request->position_name),
                'description'   => $request->description,
                'updated_by'    => Auth::id(),
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Committee Position Updated Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    /**
     * Display details of a specific committee position.
     */
    public function viewCommitteePosition($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $data = CommitteePosition::with([
                'company',
                'branch',
                'user',
                'committeeMembers'
            ])->findOrFail($id);

            return view('in.committees.committeePosition.viewCommitteePosition', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Soft delete the specified committee position.
     */
    public function destroyCommitteePosition($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $position = CommitteePosition::findOrFail($id);

            $position->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $position->delete();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Committee Position Removed Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted committee positions.
     */
    public function deletedCommitteePosition()
    {
        try {
            $data = CommitteePosition::onlyTrashed()
                ->with(['company', 'branch'])
                ->latest('deleted_at')
                ->get();

            return view('in.committees.committeePosition.deletedCommitteePosition', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted committee position back to Active status.
     */
    public function restoreCommitteePosition($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $position = CommitteePosition::withTrashed()->findOrFail($id);

            $position->restore();

            $position->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Committee Position Restored Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }



    /**
     * Display a listing of active committee members.
     */
    public function committeeMember(Request $request)
    {
        // try {
            $query = CommitteeMember::where('Status', 'Active')
                ->with([
                    'committee',
                    'member',
                    'position',
                    'company',
                    'branch',
                    'user'
                ])
                ->withCount('activeResponsibilities');

            if ($request->filled('committee_id')) {
                $query->where('committee_id', $request->committee_id);
            }

            if ($request->filled('committee_position_id')) {
                $query->where('committee_position_id', $request->committee_position_id);
            }

            if ($request->filled('membership_status')) {
                $query->where('membership_status', $request->membership_status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('ReferenceNo', 'like', "%{$search}%")
                      ->orWhereHas('member', function ($mq) use ($search) {
                          $mq->where('full_name', 'like', "%{$search}%")
                             ->orWhere('first_name', 'like', "%{$search}%")
                             ->orWhere('last_name', 'like', "%{$search}%")
                             ->orWhere('member_code', 'like', "%{$search}%");
                      })
                      ->orWhereHas('committee', function ($cq) use ($search) {
                          $cq->where('committee_name', 'like', "%{$search}%")
                             ->orWhere('committee_code', 'like', "%{$search}%");
                      });
                });
            }

            $data = $query->latest('id')->paginate(15)->withQueryString();

            $committees = Committee::where('Status', 'Active')->orderBy('committee_name')->get();
            $positions  = CommitteePosition::where('Status', 'Active')->orderBy('position_name')->get();
            $members    = Member::where('Status', 'Active')->orderBy('member_name')->get();
            $branchies  = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.committees.committeeMember.committeeMember', compact('data', 'committees', 'positions', 'members', 'branchies'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical Support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Store a newly appointed committee member in storage.
     */
    public function storeCommitteeMember(Request $request)
    {
        $request->validate([
            'committee_id'          => 'required|integer|exists:committees,id',
            'member_id'             => 'required|integer|exists:members,id',
            'committee_position_id' => 'required|integer|exists:committee_positions,id',
            'appointment_date'      => 'required|date',
            'end_date'              => 'nullable|date|after_or_equal:appointment_date',
            'membership_status'     => 'required|string|max:50',
            'appointment_notes'     => 'nullable|string',
        ]);

        DB::beginTransaction();
        // try {
            $user = Auth::user();
            $committee = Committee::findOrFail($request->committee_id);

            // Prevent duplicate active assignment for the same member in the same committee
            $exists = CommitteeMember::where('committee_id', $request->committee_id)
                ->where('member_id', $request->member_id)
                ->where('membership_status', 'Active')
                ->where('Status', 'Active')
                ->exists();

            if ($exists) {
                Alert::warning(
                    'Duplicate Assignment',
                    'This member is already an active member of the selected committee.'
                );
                return back()->withInput();
            }

            // Generate Branch Abbreviation for Reference No
            $branch = Branch::findOrFail($committee->branch_id);
            $branchWords = explode(' ', strtoupper($branch->branch_name));
            $branchPrefix = '';
            foreach ($branchWords as $word) {
                $branchPrefix .= substr($word, 0, 1);
            }
            $branchPrefix = substr($branchPrefix, 0, 3);

            // Generate Unique Incremental Reference Number
            $year = date('Y');

            $prefix = 'IFLSG/CMBR/' . $branchPrefix . '/' . $year . '/';

            $lastMember = CommitteeMember::where('ReferenceNo', 'like', $prefix . '%')
                ->orderByDesc('id')
                ->first();

            if ($lastMember) {
                $lastNumber = (int) substr($lastMember->ReferenceNo, -4);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            do {
                $generatedRefNo = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber++;
            } while (
                CommitteeMember::where('ReferenceNo', $generatedRefNo)->exists()
            );

            // Assign the generated reference number
            $data['ReferenceNo'] = $generatedRefNo;

            CommitteeMember::create([
                'ReferenceNo'           => $generatedRefNo,
                'committee_id'          => $request->committee_id,
                'member_id'             => $request->member_id,
                'committee_position_id' => $request->committee_position_id,
                'appointment_date'      => $request->appointment_date,
                'end_date'              => $request->end_date,
                'membership_status'     => $request->membership_status,
                'appointment_notes'     => $request->appointment_notes,
                'company_id'            => $committee->company_id,
                'branch_id'             => $committee->branch_id,
                'User_id'               => $user->id,
                'Status'                => 'Active',
                'AuditingStatus'        => 'Pending',
                'ReportStatus'          => 'Pending',
                'created_by'            => $user->id,
                'updated_by'            => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Committee Member Appointed Successfully'
            );

            return back();

        // } catch (\Throwable $th) {
        //     DB::rollBack();

        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical Support Tel:+255657856790'
        //     );

        //     return back()->withInput();
        // }
    }

    /**
     * Show form for editing the specified committee member appointment.
     */
    public function editCommitteeMember($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = CommitteeMember::findOrFail($id);

            $committees = Committee::where('Status', 'Active')->orderBy('committee_name')->get();
            $positions  = CommitteePosition::where('Status', 'Active')->orderBy('position_name')->get();
            $members    = Member::where('Status', 'Active')->orderBy('first_name')->get();

            return view('in.committees.committeeMember.editCommitteeMember', compact('data', 'committees', 'positions', 'members'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified committee member assignment in storage.
     */
    public function updateCommitteeMember(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'committee_id'          => 'required|integer|exists:committees,id',
            'member_id'             => 'required|integer|exists:members,id',
            'committee_position_id' => 'required|integer|exists:committee_positions,id',
            'appointment_date'      => 'required|date',
            'end_date'              => 'nullable|date|after_or_equal:appointment_date',
            'membership_status'     => 'required|string|max:50',
            'appointment_notes'     => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $committeeMember = CommitteeMember::findOrFail($id);
            $committee = Committee::findOrFail($request->committee_id);

            $committeeMember->update([
                'committee_id'          => $request->committee_id,
                'member_id'             => $request->member_id,
                'committee_position_id' => $request->committee_position_id,
                'appointment_date'      => $request->appointment_date,
                'end_date'              => $request->end_date,
                'membership_status'     => $request->membership_status,
                'appointment_notes'     => $request->appointment_notes,
                'company_id'            => $committee->company_id,
                'branch_id'             => $committee->branch_id,
                'updated_by'            => Auth::id(),
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Committee Member Details Updated Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    /**
     * Display details of a specific committee member appointment.
     */
    public function viewCommitteeMember($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $data = CommitteeMember::with([
                'committee',
                'member',
                'position',
                'company',
                'branch',
                'user',
                'responsibilities',
                'activeResponsibilities'
            ])->findOrFail($id);

            return view('in.committees.committeeMember.viewCommitteeMember', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Soft delete the specified committee member appointment.
     */
    public function destroyCommitteeMember($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $committeeMember = CommitteeMember::findOrFail($id);

            $committeeMember->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $committeeMember->delete();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Committee Member Removed Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted committee members.
     */
    public function deletedCommitteeMember()
    {
        try {
            $data = CommitteeMember::onlyTrashed()
                ->with(['committee', 'member', 'position', 'company', 'branch'])
                ->latest('deleted_at')
                ->get();

            return view('in.committees.committeeMember.deletedCommitteeMember', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted committee member appointment back to Active status.
     */
    public function restoreCommitteeMember($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $committeeMember = CommitteeMember::withTrashed()->findOrFail($id);

            $committeeMember->restore();

            $committeeMember->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Committee Member Restored Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }
}