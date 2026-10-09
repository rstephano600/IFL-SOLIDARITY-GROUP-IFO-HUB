<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\PurchaseRequestWorkflow;
use App\Models\Product;
use App\Models\ProcurementType;
use App\Models\ProcurementWorkflowStep;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;

class ProcurementController extends Controller
{

    /**
     * Display a listing of active procurement types.
     */
    public function procurementType()
    {
        // try {
            $data = ProcurementType::where('Status', 'Active')
                ->with(['company', 'branch', 'user', 'creator'])
                ->orderBy('display_order', 'asc')
                ->get();

            $companies = Company::where('Status', 'Active')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.procurements.procurementType.procurementType', compact('data', 'companies', 'branchies'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Store a newly created procurement type in storage.
     */
    public function storeprocurementType(Request $request)
    {
        $request->validate([
            'branch_id'        => 'required|integer|exists:branchies,id',
            'procurement_code' => 'required|string|max:50|unique:procurement_types,procurement_code',
            'procurement_name' => 'required|string|max:150',
            'description'      => 'nullable|string',
            'display_order'    => 'nullable|integer|min:1',
        ]);

        try {

            $branch = Branch::findOrFail($request->branch_id);

            ProcurementType::create([
                'company_id'                    => $branch->company_id,
                'branch_id'                     => $branch->id,
                'User_id'                       => Auth::id(),
                'created_by'                    => Auth::id(),

                'procurement_code'              => strtoupper($request->procurement_code),
                'procurement_name'              => strtoupper($request->procurement_name),
                'description'                   => $request->description,
                'display_order'                 => $request->display_order ?? 1,

                // Workflow & Requirement Flags
                'requires_request'              => $request->has('requires_request'),
                'requires_quotation'            => $request->has('requires_quotation'),
                'requires_quotation_evaluation' => $request->has('requires_quotation_evaluation'),
                'requires_purchase_order'       => $request->has('requires_purchase_order'),
                'requires_receiving'            => $request->has('requires_receiving'),
                'requires_inspection'           => $request->has('requires_inspection'),
                'requires_invoice'              => $request->has('requires_invoice'),
                'requires_payment_approval'     => $request->has('requires_payment_approval'),

                // Financial & Inventory Impact Flags
                'creates_stock'                 => $request->has('creates_stock'),
                'creates_expense'               => $request->has('creates_expense'),
                'creates_asset'                 => $request->has('creates_asset'),

                // Governance Flags
                'requires_budget'               => $request->has('requires_budget'),
                'requires_approval'             => $request->has('requires_approval'),

                'Status'                        => 'Active',
                'AuditingStatus'                => 'Pending',
                'ReportStatus'                  => 'Pending',
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'You\'ve Registered Procurement Type Successfully'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Show the form for editing the specified procurement type.
     */
    public function editprocurementType($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = ProcurementType::findOrFail($id);

            $companies = Company::where('Status', 'Active')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.procurements.procurementType.editprocurementType', compact('data', 'companies', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified procurement type in storage.
     */
    public function updateprocurementType(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'branch_id'        => 'required|integer|exists:branchies,id',
            'procurement_code' => 'required|string|max:50|unique:procurement_types,procurement_code,' . $id,
            'procurement_name' => 'required|string|max:150',
            'description'      => 'nullable|string',
            'display_order'    => 'nullable|integer|min:1',
        ]);

        try {

            $procurementType = ProcurementType::findOrFail($id);
            $branch = Branch::findOrFail($request->branch_id);

            $procurementType->update([
                'company_id'                    => $branch->company_id,
                'branch_id'                     => $branch->id,
                'updated_by'                    => Auth::id(),

                'procurement_code'              => strtoupper($request->procurement_code),
                'procurement_name'              => strtoupper($request->procurement_name),
                'description'                   => $request->description,
                'display_order'                 => $request->display_order ?? 1,

                // Workflow & Requirement Flags
                'requires_request'              => $request->has('requires_request'),
                'requires_quotation'            => $request->has('requires_quotation'),
                'requires_quotation_evaluation' => $request->has('requires_quotation_evaluation'),
                'requires_purchase_order'       => $request->has('requires_purchase_order'),
                'requires_receiving'            => $request->has('requires_receiving'),
                'requires_inspection'           => $request->has('requires_inspection'),
                'requires_invoice'              => $request->has('requires_invoice'),
                'requires_payment_approval'     => $request->has('requires_payment_approval'),

                // Financial & Inventory Impact Flags
                'creates_stock'                 => $request->has('creates_stock'),
                'creates_expense'               => $request->has('creates_expense'),
                'creates_asset'                 => $request->has('creates_asset'),

                // Governance Flags
                'requires_budget'               => $request->has('requires_budget'),
                'requires_approval'             => $request->has('requires_approval'),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'You\'ve Updated Procurement Type Successfully'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Display the specified procurement type profile.
     */
    public function viewprocurementType($id)
    {
        // try {
            $id = Crypt::decrypt($id);
            $data = ProcurementType::with(['company', 'branch', 'user', 'creator', 'updater'])->findOrFail($id);

            return view('in.procurements.procurementType.viewprocurementType', compact('data'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Soft delete the specified procurement type.
     */
    public function destroyprocurementType($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $procurementType = ProcurementType::findOrFail($id);

            $procurementType->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $procurementType->delete(); // Eloquent Soft Delete

            Alert::success(
                'Success ' . Auth::user()->name,
                'You\'ve Removed Procurement Type Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted procurement types.
     */
    public function deletedprocurementType()
    {
        try {
            $data = ProcurementType::onlyTrashed()
                ->with(['company', 'branch', 'deleter'])
                ->get();

            return view('in.procurements.procurementType.deletedprocurementType', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted procurement type back to Active status.
     */
    public function restoreprocurementType($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $procurementType = ProcurementType::withTrashed()->findOrFail($id);

            $procurementType->restore(); // Eloquent Restore

            $procurementType->update([
                'Status'     => 'Active',
                'deleted_by' => null,
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'You\'ve Restored Procurement Type Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }



    /**
     * Display a listing of active procurement workflow steps.
     */
    public function procurementWorkflowStep(Request $request)
    {
        // try {
            $procurementTypes = ProcurementType::where('Status', 'Active')
                ->orderBy('display_order')
                ->orderBy('procurement_name')
                ->get();

            $branchies = Branch::where('Status', 'Active')->with('company')->get();
            $permissions = Permission::select('slug')->where('Status', 'Active')->get();
            $query = ProcurementWorkflowStep::where('Status', 'Active')
                ->with(['procurementType', 'company', 'branch', 'user', 'creator']);

            if ($request->filled('procurement_type_id')) {
                $query->where('procurement_type_id', $request->procurement_type_id);
            }

            $data = $query->orderBy('procurement_type_id')
                ->orderBy('step_order')
                ->get();

            return view('in.procurements.procurementworkflowsteps.procurementWorkflowStep', compact('data', 'procurementTypes', 'branchies', 'permissions'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Store a newly created procurement workflow step in storage.
     */
    public function storeprocurementWorkflowStep(Request $request)
    {
        $request->validate([
            'branch_id'           => 'required|integer|exists:branchies,id',
            'procurement_type_id' => 'required|integer|exists:procurement_types,id',
            'step_code'           => [
                'required',
                'string',
                'max:50',
                Rule::unique('procurement_workflow_steps')->where(function ($query) use ($request) {
                    return $query->where('procurement_type_id', $request->procurement_type_id)
                        ->whereNull('deleted_at');
                })
            ],
            'step_name'           => 'required|string|max:150',
            'step_order'          => 'required|integer|min:1',
            'description'         => 'nullable|string',
            'assigned_role'       => 'nullable|string|max:100',
            'rejection_permission'       => 'nullable|string|max:100',
            'view_permission'       => 'nullable|string|max:100',
            'approval_permission'       => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();
        try {

            $branch = Branch::findOrFail($request->branch_id);
            $userId = Auth::id();

            $isFinalStep = $request->has('is_final_step');

            // Reset other final steps if this new step is marked final
            if ($isFinalStep) {
                ProcurementWorkflowStep::where('procurement_type_id', $request->procurement_type_id)
                    ->update([
                        'is_final_step' => false,
                        'updated_by'    => $userId,
                    ]);
            }

            ProcurementWorkflowStep::create([
                'company_id'          => $branch->company_id,
                'branch_id'           => $branch->id,
                'procurement_type_id' => $request->procurement_type_id,
                'step_code'           => strtoupper($request->step_code),
                'step_name'           => strtoupper($request->step_name),
                'step_order'          => $request->step_order,
                'description'         => $request->description,
                'assigned_role'       => $request->assigned_role,
                'rejection_permission'       => $request->rejection_permission,
                'view_permission'       => $request->view_permission,
                'approval_permission'       => $request->approval_permission,

                'is_required'         => $request->has('is_required'),
                'is_active'           => $request->has('is_active') ? $request->boolean('is_active') : true,
                'can_skip'            => $request->has('can_skip'),
                'is_final_step'       => $isFinalStep,

                'requires_approval'   => $request->has('requires_approval'),
                'requires_attachment' => $request->has('requires_attachment'),
                'requires_comment'    => $request->has('requires_comment'),

                'User_id'             => $userId,
                'created_by'          => $userId,
                'updated_by'          => $userId,

                'Status'              => 'Active',
                'AuditingStatus'      => 'Pending',
                'ReportStatus'        => 'Pending',
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Procurement Workflow Step Registered Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Show the form for editing the specified procurement workflow step.
     */
    public function editprocurementWorkflowStep($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = ProcurementWorkflowStep::findOrFail($id);

            $procurementTypes = ProcurementType::where('Status', 'Active')
                ->orderBy('display_order')
                ->orderBy('procurement_name')
                ->get();
            $permissions = Permission::select('slug')->where('Status', 'Active')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.procurements.procurementworkflowsteps.editprocurementWorkflowStep', compact('data', 'procurementTypes', 'branchies', 'permissions'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified procurement workflow step in storage.
     */
    public function updateprocurementWorkflowStep(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'branch_id'           => 'required|integer|exists:branchies,id',
            'procurement_type_id' => 'required|integer|exists:procurement_types,id',
            'step_code'           => [
                'required',
                'string',
                'max:50',
                Rule::unique('procurement_workflow_steps')->where(function ($query) use ($request) {
                    return $query->where('procurement_type_id', $request->procurement_type_id)
                        ->whereNull('deleted_at');
                })->ignore($id)
            ],
            'step_name'           => 'required|string|max:150',
            'step_order'          => 'required|integer|min:1',
            'description'         => 'nullable|string',
            'assigned_role'       => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            $workflowStep = ProcurementWorkflowStep::findOrFail($id);
            $branch = Branch::findOrFail($request->branch_id);
            $userId = Auth::id();

            $isFinalStep = $request->has('is_final_step');

            if ($isFinalStep) {
                ProcurementWorkflowStep::where('procurement_type_id', $request->procurement_type_id)
                    ->where('id', '!=', $workflowStep->id)
                    ->update([
                        'is_final_step' => false,
                        'updated_by'    => $userId,
                    ]);
            }

            $workflowStep->update([
                'company_id'          => $branch->company_id,
                'branch_id'           => $branch->id,
                'procurement_type_id' => $request->procurement_type_id,
                'step_code'           => strtoupper($request->step_code),
                'step_name'           => strtoupper($request->step_name),
                'step_order'          => $request->step_order,
                'description'         => $request->description,
                'assigned_role'       => $request->assigned_role,

                'is_required'         => $request->has('is_required'),
                'is_active'           => $request->has('is_active') ? $request->boolean('is_active') : true,
                'can_skip'            => $request->has('can_skip'),
                'is_final_step'       => $isFinalStep,

                'requires_approval'   => $request->has('requires_approval'),
                'requires_attachment' => $request->has('requires_attachment'),
                'requires_comment'    => $request->has('requires_comment'),

                'updated_by'          => $userId,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Procurement Workflow Step Updated Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Display the specified procurement workflow step profile.
     */
    public function viewprocurementWorkflowStep($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = ProcurementWorkflowStep::with([
                'procurementType',
                'company',
                'branch',
                'user',
                'creator',
                'updater'
            ])->findOrFail($id);

            return view('in.procurements.procurementworkflowsteps.viewprocurementWorkflowStep', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Toggle active/inactive status of a workflow step.
     */
    public function toggleprocurementWorkflowStep($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $workflowStep = ProcurementWorkflowStep::findOrFail($id);

            $newStatus = !$workflowStep->is_active;

            $workflowStep->update([
                'is_active'  => $newStatus,
                'Status'     => $newStatus ? 'Active' : 'Inactive',
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                $newStatus ? 'Workflow Step Activated Successfully' : 'Workflow Step Deactivated Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Reorder workflow steps within a procurement type.
     */
    public function reorderprocurementWorkflowSteps(Request $request)
    {
        $request->validate([
            'procurement_type_id' => 'required|integer|exists:procurement_types,id',
            'steps'               => 'required|array',
            'steps.*'             => 'integer|exists:procurement_workflow_steps,id',
        ]);

        DB::beginTransaction();
        try {
            $userId = Auth::id();

            foreach ($request->steps as $order => $stepId) {
                ProcurementWorkflowStep::where('id', $stepId)
                    ->where('procurement_type_id', $request->procurement_type_id)
                    ->update([
                        'step_order' => $order + 1,
                        'updated_by' => $userId,
                    ]);
            }

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Workflow Steps Reordered Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Soft delete the specified procurement workflow step.
     */
    public function destroyprocurementWorkflowStep($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $workflowStep = ProcurementWorkflowStep::findOrFail($id);

            $workflowStep->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $workflowStep->delete();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Procurement Workflow Step Removed Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted procurement workflow steps.
     */
    public function deletedprocurementWorkflowStep()
    {
        try {
            $data = ProcurementWorkflowStep::onlyTrashed()
                ->with(['procurementType', 'company', 'branch', 'deleter'])
                ->get();

            return view('in.procurements.procurementworkflowsteps.deletedprocurementWorkflowStep', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted procurement workflow step.
     */
    public function restoreprocurementWorkflowStep($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $workflowStep = ProcurementWorkflowStep::withTrashed()->findOrFail($id);

            $workflowStep->restore();

            $workflowStep->update([
                'Status'     => 'Active',
                'is_active'  => true,
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Procurement Workflow Step Restored Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }
}