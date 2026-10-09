<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ProcurementType;
use App\Models\ProcurementWorkflowStep;
use App\Models\Product;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\PurchaseRequestWorkflow;
use App\Models\UnitOfMeasure;
use App\Models\Company;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PurchaseController extends Controller
{
    /**
     * Display a listing of purchase requests with filters.
     */
    public function purchaseRequest(Request $request)
    {
        // try {
            $query = PurchaseRequest::with([
                'procurementType',
                'requester',
                'currentStep',
                'branch',
                'items.product'
            ]);

            if ($request->filled('status')) {
                $query->where('Status', $request->status);
            }

            if ($request->filled('procurement_type_id')) {
                $query->where('procurement_type_id', $request->procurement_type_id);
            }

            if ($request->filled('requester_id')) {
                $query->where('requester_id', $request->requester_id);
            }

            if ($request->filled('from_date')) {
                $query->whereDate('request_date', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('request_date', '<=', $request->to_date);
            }

            $procurementTypes = ProcurementType::where('Status', 'Active')
                ->orderBy('display_order')
                ->get();

            $data = $query->latest('id')->paginate(20)->withQueryString();

            return view('in.purchase.purchaseRequests', compact('data', 'procurementTypes'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Show form for creating a new purchase request.
     */
    public function createpurchaseRequest()
    {
        // try {
            $procurementTypes = ProcurementType::where('Status', 'Active')
                ->orderBy('display_order')
                ->get();

            $products = Product::where('Status', 'Active')
                ->with(['procurementType'])
                ->orderBy('product_name')
                ->get();

            $units = UnitOfMeasure::where('Status', 'Active')
                ->orderBy('UnitName')
                ->get();

            $branchies = Branch::where('Status', 'Active')->get();

            return view('in.purchase.createPurchaseRequest', compact('procurementTypes', 'products', 'units', 'branchies'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Store a newly created purchase request in storage.
     */
    public function storepurchaseRequest(Request $request)
    {
        $request->validate([
            'procurement_type_id'          => 'required|integer|exists:procurement_types,id',
            'request_date'                 => 'required|date',
            'required_date'                => 'nullable|date|after_or_equal:request_date',
            'priority'                     => 'required|string|max:30',
            'purpose'                      => 'nullable|string|max:255',
            'justification'                => 'nullable|string',
            'budget_amount'                => 'nullable|numeric|min:0',
            'items'                        => 'required|array|min:1',
            'items.*.product_id'           => 'required|integer|exists:products,id',
            'items.*.unit_of_measure_id'    => 'nullable|integer|exists:units_of_measure,id',
            'items.*.requested_quantity'   => 'required|numeric|min:0.001',
            'items.*.estimated_unit_price' => 'nullable|numeric|min:0',
            'items.*.description'          => 'nullable|string',
        ]);

        DB::beginTransaction();
        // try {
            $user = Auth::user();
            $requestNo = $this->generateRequestNumber();

            $purchaseRequest = PurchaseRequest::create([
                'request_no'          => $requestNo,
                'procurement_type_id' => $request->procurement_type_id,
                'requester_id'        => $user->id,
                'company_id'          => $user->company_id ?? null,
                'branch_id'           => $user->branch_id ?? null,
                'User_id'             => $user->id,
                'request_date'        => $request->request_date,
                'required_date'       => $request->required_date,
                'priority'            => $request->priority,
                'purpose'             => $request->purpose,
                'justification'       => $request->justification,
                'budget_amount'       => $request->budget_amount ?? 0,
                'estimated_total'     => 0,
                'approved_total'      => 0,
                'currency_code'       => 'TZS',
                'Status'              => 'Draft',
                'AuditingStatus'      => 'Pending',
                'ReportStatus'        => 'Pending',
                'created_by'          => $user->id,
            ]);

            $estimatedTotal = 0;

            foreach ($request->items as $item) {
                $quantity  = (float) $item['requested_quantity'];
                $unitPrice = (float) ($item['estimated_unit_price'] ?? 0);
                $itemTotal = $quantity * $unitPrice;

                PurchaseRequestItem::create([
                    'purchase_request_id'  => $purchaseRequest->id,
                    'product_id'           => $item['product_id'],
                    'unit_of_measure_id'   => $item['unit_of_measure_id'] ?? null,
                    'description'          => $item['description'] ?? null,
                    'requested_quantity'  => $quantity,
                    'estimated_unit_price' => $unitPrice,
                    'estimated_total'      => $itemTotal,
                    'approved_quantity'   => 0,
                    'approved_unit_price'  => 0,
                    'approved_total'       => 0,
                    'item_status'          => 'Pending',
                ]);

                $estimatedTotal += $itemTotal;
            }

            $purchaseRequest->update([
                'estimated_total' => $estimatedTotal
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Purchase Request Draft Created Successfully (' . $requestNo . ')'
            );

            return redirect()->route('purchaseRequest');

        // } catch (\Throwable $th) {
        //     DB::rollBack();

        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );

        //     return back()->withInput();
        // }
    }

    /**
     * View full details of a specific purchase request.
     */
    public function viewpurchaseRequest($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $data = PurchaseRequest::with([
                'procurementType',
                'requester',
                'currentStep',
                'company',
                'branch',
                'items.product',
                'items.unitOfMeasure',
                'creator'
            ])->findOrFail($id);

            $workflows = PurchaseRequestWorkflow::where('purchase_request_id', $id)
                ->with(['workflowStep', 'assignedTo', 'actedBy', 'previousStep', 'nextStep'])
                ->latest('id')
                ->get();

            return view('in.purchase.viewPurchaseRequest', compact('data', 'workflows'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Show form to edit an existing draft or rejected purchase request.
     */
    public function editpurchaseRequest($id)
    {
        // try {
            $id = Crypt::decrypt($id);
            $data = PurchaseRequest::with('items')->findOrFail($id);

            if (!in_array($data->Status, ['Draft', 'Rejected'])) {
                Alert::warning(
                    'Action Denied',
                    'Only draft or rejected purchase requests can be edited.'
                );
                return back();
            }

            $procurementTypes = ProcurementType::where('Status', 'Active')
                ->orderBy('display_order')
                ->get();

            $products = Product::where('Status', 'Active')
                ->with(['procurementType'])
                ->orderBy('product_name')
                ->get();

            $units = UnitOfMeasure::where('Status', 'Active')
                ->orderBy('UnitName')
                ->get();

            $branchies = Branch::where('Status', 'Active')->get();

            return view('in.purchase.editPurchaseRequest', compact('data', 'procurementTypes', 'products', 'units', 'branchies'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Update the specified purchase request in storage.
     */
    public function updatepurchaseRequest(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'procurement_type_id'          => 'required|integer|exists:procurement_types,id',
            'request_date'                 => 'required|date',
            'required_date'                => 'nullable|date|after_or_equal:request_date',
            'priority'                     => 'required|string|max:30',
            'purpose'                      => 'nullable|string|max:255',
            'justification'                => 'nullable|string',
            'budget_amount'                => 'nullable|numeric|min:0',
            'items'                        => 'required|array|min:1',
            'items.*.product_id'           => 'required|integer|exists:products,id',
            'items.*.unit_of_measure_id'    => 'nullable|integer|exists:units_of_measure,id',
            'items.*.requested_quantity'   => 'required|numeric|min:0.001',
            'items.*.estimated_unit_price' => 'nullable|numeric|min:0',
            'items.*.description'          => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $purchaseRequest = PurchaseRequest::findOrFail($id);

            if (!in_array($purchaseRequest->Status, ['Draft', 'Rejected'])) {
                Alert::warning('Warning', 'Only draft or rejected requests can be updated.');
                return back();
            }

            $purchaseRequest->update([
                'procurement_type_id' => $request->procurement_type_id,
                'request_date'        => $request->request_date,
                'required_date'       => $request->required_date,
                'priority'            => $request->priority,
                'purpose'             => $request->purpose,
                'justification'       => $request->justification,
                'budget_amount'       => $request->budget_amount ?? 0,
                'rejection_reason'    => null,
                'rejected_at'         => null,
                'updated_by'          => $user->id,
            ]);

            $purchaseRequest->items()->delete();

            $estimatedTotal = 0;

            foreach ($request->items as $item) {
                $quantity  = (float) $item['requested_quantity'];
                $unitPrice = (float) ($item['estimated_unit_price'] ?? 0);
                $itemTotal = $quantity * $unitPrice;

                PurchaseRequestItem::create([
                    'purchase_request_id'  => $purchaseRequest->id,
                    'product_id'           => $item['product_id'],
                    'unit_of_measure_id'   => $item['unit_of_measure_id'] ?? null,
                    'description'          => $item['description'] ?? null,
                    'requested_quantity'  => $quantity,
                    'estimated_unit_price' => $unitPrice,
                    'estimated_total'      => $itemTotal,
                    'item_status'          => 'Pending',
                ]);

                $estimatedTotal += $itemTotal;
            }

            $purchaseRequest->update([
                'estimated_total' => $estimatedTotal
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Purchase Request Updated Successfully'
            );

            return redirect()->route('viewpurchaseRequest', encrypt($purchaseRequest->id));

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );

            return back()->withInput();
        }
    }

    /**
     * Submit purchase request into procurement workflow.
     */
    public function submitpurchaseRequest($id)
    {
        $id = Crypt::decrypt($id);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $purchaseRequest = PurchaseRequest::with(['procurementType', 'items'])->findOrFail($id);

            if (!in_array($purchaseRequest->Status, ['Draft', 'Rejected'])) {
                Alert::warning('Notice', 'This purchase request cannot be submitted.');
                return back();
            }

            if ($purchaseRequest->items->isEmpty()) {
                Alert::warning('Validation Error', 'Purchase request must contain at least one item.');
                return back();
            }

            $firstStep = ProcurementWorkflowStep::active()
                ->where('procurement_type_id', $purchaseRequest->procurement_type_id)
                ->ordered()
                ->first();

            if (!$firstStep) {
                Alert::error('Configuration Missing', 'No active workflow configured for this procurement type.');
                return back();
            }

            $purchaseRequest->update([
                'Status'           => 'Pending Approval',
                'current_step_id'  => $firstStep->id,
                'submitted_at'     => now(),
                'rejected_at'      => null,
                'rejection_reason' => null,
                'updated_by'       => $user->id,
            ]);

            PurchaseRequestWorkflow::create([
                'purchase_request_id' => $purchaseRequest->id,
                'workflow_step_id'    => $firstStep->id,
                'purchase_status'     => 'Pending',
                'action'              => 'SUBMITTED',
                'assigned_to'         => null,
                'acted_by'            => $user->id,
                'started_at'          => now(),
                'acted_at'            => now(),
                'completed_at'        => now(),
                'comment'             => 'Purchase request submitted into procurement approval workflow.',
                'company_id'          => $purchaseRequest->company_id,
                'branch_id'           => $purchaseRequest->branch_id,
                'User_id'             => $user->id,
                'Status'              => 'Active',
                'AuditingStatus'      => 'Pending',
                'ReportStatus'        => 'Pending',
                'created_by'          => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Purchase Request Submitted for Approval Successfully'
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
     * Approve current workflow step in procurement sequence.
     */
    public function approvepurchaseRequest(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        DB::beginTransaction();
        // try {
            $user = Auth::user();

            $purchaseRequest = PurchaseRequest::with(['procurementType', 'currentStep', 'items'])
                ->findOrFail($id);

            if (!$purchaseRequest->current_step_id || !$purchaseRequest->currentStep) {
                Alert::warning('Notice', 'No active workflow step found for approval.');
                return back();
            }

            $currentStep = $purchaseRequest->currentStep;

            // Enforce step requirements
            if ($currentStep->requires_comment && empty($request->comment)) {
                Alert::warning('Validation Required', 'A comment/remark is mandatory for approving this step.');
                return back()->withInput();
            }


            if ($purchaseRequest->requester_id === $user->id) {
                Alert::warning('Authorization Error', 'Requesters are prohibited from approving their own purchase requests.');
                return back();
            }

            if (!$this->userHasWorkflowPermission(
                $user,
                $currentStep->approval_permission
            )) {
                Alert::warning(
                    'Access Denied',
                    'You do not have permission to approve this workflow step.'
                );

                return back();
            }

            // Handle optional attachment upload
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('procurement_attachments', 'public');
            }

            // Locate next step in order
            $nextStep = ProcurementWorkflowStep::active()
                ->where('procurement_type_id', $purchaseRequest->procurement_type_id)
                ->where('step_order', '>', $currentStep->step_order)
                ->ordered()
                ->first();

            // Update existing pending workflow log
            $currentWorkflow = PurchaseRequestWorkflow::where('purchase_request_id', $purchaseRequest->id)
                ->where('workflow_step_id', $currentStep->id)
                ->where('purchase_status', 'Pending')
                ->latest('id')
                ->first();

            if ($currentWorkflow) {
                $currentWorkflow->update([
                    'purchase_status' => 'Completed',
                    'action'          => 'APPROVED',
                    'acted_by'        => $user->id,
                    'acted_at'        => now(),
                    'completed_at'    => now(),
                    'next_step_id'    => $nextStep->id,
                    'comment'         => $request->comment,
                    'attachment_path' => $attachmentPath ?? $currentWorkflow->attachment_path,
                    'updated_by'      => $user->id,
                ]);
            }

            // Final Approval Check (if no next step OR explicitly marked final step)
            if (!$nextStep || $currentStep->is_final_step) {
                
                // Copy estimated quantities to approved quantities
                foreach ($purchaseRequest->items as $item) {
                    $item->update([
                        'approved_quantity'   => $item->requested_quantity,
                        'approved_unit_price'  => $item->estimated_unit_price,
                        'approved_total'       => $item->estimated_total,
                        'item_status'          => 'Approved',
                    ]);
                }

                $purchaseRequest->update([
                    'Status'           => 'Approved',
                    'current_step_id'  => null,
                    'approved_at'      => now(),
                    'approved_total'   => $purchaseRequest->calculateEstimatedTotal(),
                    'approval_comment' => $request->comment,
                    'updated_by'       => $user->id,
                ]);

                DB::commit();

                Alert::success(
                    'Success ' . Auth::user()->name,
                    'Purchase Request Fully Approved Successfully'
                );

                return back();
            }

            // Transition to Next Workflow Step
            $purchaseRequest->update([
                'Status'          => 'Pending Approval',
                'current_step_id' => $nextStep->id,
                'updated_by'      => $user->id,
            ]);

            PurchaseRequestWorkflow::create([
                'purchase_request_id' => $purchaseRequest->id,
                'workflow_step_id'    => $nextStep->id,
                'purchase_status'     => 'Pending',
                'action'              => 'STARTED',
                'started_at'          => now(),
                'previous_step_id'    => $currentStep->id,
                'company_id'          => $purchaseRequest->company_id,
                'branch_id'           => $purchaseRequest->branch_id,
                'User_id'             => $user->id,
                'Status'              => 'Active',
                'AuditingStatus'      => 'Pending',
                'ReportStatus'        => 'Pending',
                'created_by'          => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Workflow Step Approved. Request moved to step: ' . $nextStep->step_name
            );

            return back();

        // } catch (\Throwable $th) {
        //     DB::rollBack();

        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );

        //     return back();
        // }
    }

    /**
     * Reject current workflow step for purchase request.
     */
    public function rejectpurchaseRequest(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'rejection_reason' => 'required|string|min:3',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $purchaseRequest = PurchaseRequest::with('currentStep')->findOrFail($id);

            if (!$purchaseRequest->currentStep) {
                Alert::warning('Notice', 'No active workflow step found for rejection.');
                return back();
            }

            $currentStep = $purchaseRequest->currentStep;

            $workflow = PurchaseRequestWorkflow::where('purchase_request_id', $purchaseRequest->id)
                ->where('workflow_step_id', $currentStep->id)
                ->where('purchase_status', 'Pending')
                ->latest('id')
                ->first();

            if ($workflow) {
                $workflow->update([
                    'purchase_status'  => 'Rejected',
                    'action'           => 'REJECTED',
                    'acted_by'         => $user->id,
                    'acted_at'         => now(),
                    'completed_at'     => now(),
                    'comment'          => $request->rejection_reason,
                    'rejection_reason' => $request->rejection_reason,
                    'updated_by'       => $user->id,
                ]);
            }

            $purchaseRequest->update([
                'Status'           => 'Rejected',
                'rejected_at'      => now(),
                'rejection_reason' => $request->rejection_reason,
                'approval_comment' => null,
                'updated_by'       => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Purchase Request Rejected Successfully'
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
     * Resubmit a rejected purchase request back to step 1.
     */
    public function resubmitpurchaseRequest($id)
    {
        $id = Crypt::decrypt($id);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $purchaseRequest = PurchaseRequest::with('procurementType')->findOrFail($id);

            if ($purchaseRequest->Status !== 'Rejected') {
                Alert::warning('Notice', 'Only rejected purchase requests can be resubmitted.');
                return back();
            }

            $firstStep = ProcurementWorkflowStep::active()
                ->where('procurement_type_id', $purchaseRequest->procurement_type_id)
                ->ordered()
                ->first();

            if (!$firstStep) {
                Alert::error('Configuration Error', 'No active workflow step found.');
                return back();
            }

            $purchaseRequest->update([
                'Status'           => 'Pending Approval',
                'current_step_id'  => $firstStep->id,
                'submitted_at'     => now(),
                'rejected_at'      => null,
                'rejection_reason' => null,
                'updated_by'       => $user->id,
            ]);

            PurchaseRequestWorkflow::create([
                'purchase_request_id' => $purchaseRequest->id,
                'workflow_step_id'    => $firstStep->id,
                'purchase_status'     => 'Pending',
                'action'              => 'RESUBMITTED',
                'started_at'          => now(),
                'acted_at'            => now(),
                'company_id'          => $purchaseRequest->company_id,
                'branch_id'           => $purchaseRequest->branch_id,
                'User_id'             => $user->id,
                'Status'              => 'Active',
                'created_by'          => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Purchase Request Resubmitted Successfully'
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
     * Cancel an active purchase request.
     */
    public function cancelpurchaseRequest($id)
    {
        $id = Crypt::decrypt($id);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $purchaseRequest = PurchaseRequest::findOrFail($id);

            if (in_array($purchaseRequest->Status, ['Approved', 'Completed', 'Cancelled'])) {
                Alert::warning('Notice', 'Approved or completed purchase requests cannot be cancelled.');
                return back();
            }

            $purchaseRequest->update([
                'Status'     => 'Cancelled',
                'updated_by' => $user->id,
            ]);

            PurchaseRequestWorkflow::create([
                'purchase_request_id' => $purchaseRequest->id,
                'workflow_step_id'    => $purchaseRequest->current_step_id,
                'purchase_status'     => 'Cancelled',
                'action'              => 'CANCELLED',
                'acted_by'            => $user->id,
                'acted_at'            => now(),
                'completed_at'        => now(),
                'comment'             => 'Purchase request cancelled by user.',
                'company_id'          => $purchaseRequest->company_id,
                'branch_id'           => $purchaseRequest->branch_id,
                'User_id'             => $user->id,
                'Status'              => 'Active',
                'created_by'          => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Purchase Request Cancelled Successfully'
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
     * View audit log workflow history for a purchase request.
     */
    public function purchaseRequestWorkflow($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $purchaseRequest = PurchaseRequest::with([
                'procurementType',
                'requester',
                'currentStep',
                'items.product'
            ])->findOrFail($id);

            $workflows = PurchaseRequestWorkflow::where('purchase_request_id', $id)
                ->with([
                    'workflowStep',
                    'previousStep',
                    'nextStep',
                    'assignedTo',
                    'actedBy'
                ])
                ->latest('id')
                ->get();

            return view('in.purchase.purchaseRequestWorkflow', compact('purchaseRequest', 'workflows'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Soft delete a purchase request.
     */
    public function destroypurchaseRequest($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $purchaseRequest = PurchaseRequest::findOrFail($id);

            $purchaseRequest->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $purchaseRequest->delete();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Purchase Request Soft Deleted Successfully'
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
     * Display a listing of soft-deleted purchase requests.
     */
    public function deletedpurchaseRequest()
    {
        try {
            $data = PurchaseRequest::onlyTrashed()
                ->with(['procurementType', 'requester', 'deleter'])
                ->latest('deleted_at')
                ->get();

            return view('in.purchase.deletedPurchaseRequest', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted purchase request.
     */
    public function restorepurchaseRequest($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $purchaseRequest = PurchaseRequest::withTrashed()->findOrFail($id);

            $purchaseRequest->restore();

            $purchaseRequest->update([
                'Status'     => 'Draft',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Purchase Request Restored Successfully'
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
     * Generate unique sequence request number (PR-YYYY-00001).
     */
    private function generateRequestNumber(): string
    {
        $year = date('Y');

        $lastRequest = PurchaseRequest::where('request_no', 'like', 'IFLSG/PR/' . $year . '/%')
            ->orderByDesc('id')
            ->first();

        if (!$lastRequest) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastRequest->request_no, -5);
            $number = $lastNumber + 1;
        }

        return 'IFLSG/PR/' . $year . '/' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }


    /**
     * Check whether the user has the required workflow permission.
     */
    private function userHasWorkflowPermission($user, ?string $permission): bool
    {
        if (empty($permission)) {
            return false;
        }

        return $user->can($permission);
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVAL QUEUES — LEVELS 1 TO 6
    |--------------------------------------------------------------------------
    */

    public function purchaseRequestApprovalLevel1()
    {
        return $this->showPurchaseRequestApprovalLevel(1);
    }

    public function purchaseRequestApprovalLevel2()
    {
        return $this->showPurchaseRequestApprovalLevel(2);
    }

    public function purchaseRequestApprovalLevel3()
    {
        return $this->showPurchaseRequestApprovalLevel(3);
    }

    public function purchaseRequestApprovalLevel4()
    {
        return $this->showPurchaseRequestApprovalLevel(4);
    }

    public function purchaseRequestApprovalLevel5()
    {
        return $this->showPurchaseRequestApprovalLevel(5);
    }

    public function purchaseRequestApprovalLevel6()
    {
        return $this->showPurchaseRequestApprovalLevel(6);
    }

    /**
     * Display the requests waiting at a specific approval level.
     */
    private function showPurchaseRequestApprovalLevel(int $level)
    {
        $user = Auth::user();

        $viewPermission = "view-purchase-request-level-{$level}";

        abort_unless($user->can($viewPermission), 403);

        $data = PurchaseRequest::with([
            'procurementType',
            'currentStep',
            'requester',
            'branch',
            'items.product',
            'items.unitOfMeasure',
        ])
            ->where('Status', 'Pending Approval')
            ->whereHas('currentStep', function ($query) use ($level) {
                $query->where(
                    'view_permission',
                    "view-purchase-request-level-{$level}"
                );
            })
            ->latest('id')
            ->paginate(15);

        return view(
            "in.purchase.approvals.level{$level}",
            compact('data', 'level')
        );
    }



    /*
    |--------------------------------------------------------------------------
    | APPROVE PURCHASE REQUEST — LEVELS 1 TO 6
    |--------------------------------------------------------------------------
    */

    public function approvepurchaseRequestLevel1(Request $request, $id)
    {
        return $this->approvePurchaseRequestAtLevel($request, $id, 1);
    }

    public function approvepurchaseRequestLevel2(Request $request, $id)
    {
        return $this->approvePurchaseRequestAtLevel($request, $id, 2);
    }

    public function approvepurchaseRequestLevel3(Request $request, $id)
    {
        return $this->approvePurchaseRequestAtLevel($request, $id, 3);
    }

    public function approvepurchaseRequestLevel4(Request $request, $id)
    {
        return $this->approvePurchaseRequestAtLevel($request, $id, 4);
    }

    public function approvepurchaseRequestLevel5(Request $request, $id)
    {
        return $this->approvePurchaseRequestAtLevel($request, $id, 5);
    }

    public function approvepurchaseRequestLevel6(Request $request, $id)
    {
        return $this->approvePurchaseRequestAtLevel($request, $id, 6);
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT PURCHASE REQUEST — LEVELS 1 TO 6
    |--------------------------------------------------------------------------
    */

    public function rejectpurchaseRequestLevel1(Request $request, $id)
    {
        return $this->rejectPurchaseRequestAtLevel($request, $id, 1);
    }

    public function rejectpurchaseRequestLevel2(Request $request, $id)
    {
        return $this->rejectPurchaseRequestAtLevel($request, $id, 2);
    }

    public function rejectpurchaseRequestLevel3(Request $request, $id)
    {
        return $this->rejectPurchaseRequestAtLevel($request, $id, 3);
    }

    public function rejectpurchaseRequestLevel4(Request $request, $id)
    {
        return $this->rejectPurchaseRequestAtLevel($request, $id, 4);
    }

    public function rejectpurchaseRequestLevel5(Request $request, $id)
    {
        return $this->rejectPurchaseRequestAtLevel($request, $id, 5);
    }

    public function rejectpurchaseRequestLevel6(Request $request, $id)
    {
        return $this->rejectPurchaseRequestAtLevel($request, $id, 6);
    }

    /**
     * Approve a purchase request at a specific permission level.
     */
    private function approvePurchaseRequestAtLevel(
        Request $request,
        $encryptedId,
        int $level
    ) {
        $user = Auth::user();

        $permission = "approve-purchase-request-level-{$level}";

        abort_unless($user && $user->can($permission), 403);

        $id = Crypt::decrypt($encryptedId);

        $request->validate([
            'comment' => 'nullable|string|max:5000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $attachmentPath = null;

        try {
            $result = DB::transaction(function () use (
                $request,
                $id,
                $level,
                $user,
                $attachmentPath
            ) {
                $purchaseRequest = PurchaseRequest::with([
                    'procurementType',
                    'currentStep',
                    'items',
                ])
                    ->lockForUpdate()
                    ->findOrFail($id);

                if ($purchaseRequest->Status !== 'Pending Approval') {
                    throw ValidationException::withMessages([
                        'purchase_request' =>
                            'This purchase request is not awaiting approval.',
                    ]);
                }

                $currentStep = $purchaseRequest->currentStep;

                if (!$currentStep || !$purchaseRequest->current_step_id) {
                    throw ValidationException::withMessages([
                        'purchase_request' =>
                            'No active workflow step was found.',
                    ]);
                }

                if (
                    $currentStep->approval_permission !==
                    "approve-purchase-request-level-{$level}"
                ) {
                    abort(403, 'This request is not assigned to your approval level.');
                }

                if ((int) $purchaseRequest->requester_id === (int) $user->id) {
                    abort(403, 'You cannot approve your own purchase request.');
                }

                if (
                    $currentStep->requires_comment &&
                    trim((string) $request->input('comment')) === ''
                ) {
                    throw ValidationException::withMessages([
                        'comment' =>
                            'An approval comment is required for this step.',
                    ]);
                }

                $nextStep = ProcurementWorkflowStep::active()
                    ->where(
                        'procurement_type_id',
                        $purchaseRequest->procurement_type_id
                    )
                    ->where('step_order', '>', $currentStep->step_order)
                    ->ordered()
                    ->first();

                $workflow = PurchaseRequestWorkflow::where(
                    'purchase_request_id',
                    $purchaseRequest->id
                )
                    ->where('workflow_step_id', $currentStep->id)
                    ->where('purchase_status', 'Pending')
                    ->latest('id')
                    ->lockForUpdate()
                    ->first();

                if (!$workflow) {
                    throw ValidationException::withMessages([
                        'purchase_request' =>
                            'No pending workflow record exists for this step.',
                    ]);
                }

                // Attachments are optional.
                $uploadedPath = null;

                if ($request->hasFile('attachment')) {
                    $uploadedPath = $request->file('attachment')
                        ->store('procurement_attachments', 'public');
                }

                $workflow->update([
                    'purchase_status' => 'Completed',
                    'action' => 'APPROVED',
                    'acted_by' => $user->id,
                    'acted_at' => now(),
                    'completed_at' => now(),
                    'next_step_id' => $nextStep->id,
                    'comment' => $request->input('comment'),
                    'attachment_path' =>
                        $uploadedPath ?? $workflow->attachment_path,
                    'updated_by' => $user->id,
                ]);

                // Final approval: no next step or explicitly marked final.
                if (!$nextStep || $currentStep->is_final_step) {
                    foreach ($purchaseRequest->items as $item) {
                        $item->update([
                            'approved_quantity' => $item->requested_quantity,
                            'approved_unit_price' => $item->estimated_unit_price,
                            'approved_total' => $item->estimated_total,
                            'item_status' => 'Approved',
                        ]);
                    }

                    $purchaseRequest->update([
                        'Status' => 'Approved',
                        'current_step_id' => null,
                        'approved_at' => now(),
                        'approved_total' =>
                            $purchaseRequest->calculateEstimatedTotal(),
                        'approval_comment' => $request->input('comment'),
                        'updated_by' => $user->id,
                    ]);

                    return [
                        'final' => true,
                        'message' => 'Purchase Request Fully Approved Successfully.',
                    ];
                }

                // Start the next configured workflow step.
                $purchaseRequest->update([
                    'Status' => 'Pending Approval',
                    'current_step_id' => $nextStep->id,
                    'updated_by' => $user->id,
                ]);

                PurchaseRequestWorkflow::create([
                    'purchase_request_id' => $purchaseRequest->id,
                    'workflow_step_id' => $nextStep->id,
                    'purchase_status' => 'Pending',
                    'action' => 'STARTED',
                    'started_at' => now(),
                    'previous_step_id' => $currentStep->id,
                    'company_id' => $purchaseRequest->company_id,
                    'branch_id' => $purchaseRequest->branch_id,
                    'User_id' => $user->id,
                    'Status' => 'Active',
                    'AuditingStatus' => 'Pending',
                    'ReportStatus' => 'Pending',
                    'created_by' => $user->id,
                ]);

                return [
                    'final' => false,
                    'message' => 'Step approved. Request moved to: '
                        . $nextStep->step_name,
                ];
            });

            Alert::success('Success', $result['message']);

            return back();

        } catch (ValidationException $e) {
            throw $e;

        } catch (\Throwable $e) {
            report($e);

            Alert::error(
                'Approval Failed',
                'The purchase request could not be approved. Please contact the system administrator.'
            );

            return back();
        }
    }
}