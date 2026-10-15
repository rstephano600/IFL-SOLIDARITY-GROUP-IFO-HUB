<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Committee;
use App\Models\Product;
use App\Models\ServiceCatalog;
use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestAttachment;
use App\Models\ServiceRequestItem;
use App\Models\User;
use App\Models\ServiceMoneyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class ServiceRequestController extends Controller
{
    /**
     * Display a listing of active service requests.
     */
    public function serviceRequest(Request $request)
    {
        // try {
            $query = ServiceRequest::where('Status', 'Active')
                ->with([
                    'requester',
                    'committee',
                    'committeeChairman',
                    'committeeApprover',
                    'groupChairmanApprover',
                    'company',
                    'branch'
                ])
                ->withCount(['items', 'attachments', 'moneyRequests']);

            if ($request->filled('request_status')) {
                $query->where('request_status', $request->request_status);
            }

            if ($request->filled('committee_id')) {
                $query->where('committee_id', $request->committee_id);
            }

            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('request_no', 'like', "%{$search}%")
                      ->orWhere('ReferenceNo', 'like', "%{$search}%")
                      ->orWhere('request_title', 'like', "%{$search}%")
                      ->orWhere('beneficiary_name', 'like', "%{$search}%");
                });
            }

            $data = $query->latest('id')->paginate(15)->withQueryString();

            $committees = Committee::where('Status', 'Active')->orderBy('committee_name')->get();
            $branchies  = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.services.services.serviceRequest', compact('data', 'committees', 'branchies'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical Support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Show the form for creating a new service request.
     */
    public function createServiceRequest()
    {
        try {
            $committees = Committee::where('Status', 'Active')->orderBy('committee_name')->get();
            $users      = User::where('Status', 'Active')->orderBy('name')->get();
            $branchies  = Branch::where('Status', 'Active')->with('company')->get();
            $services   = ServiceCatalog::where('Status', 'Active')->get();
            $servicescategories   = ServiceCategory::where('Status', 'Active')->get();
            $products   = Product::where('Status', 'Active')->get();

            return view('in.services.services.createServiceRequest', compact(
                'committees',
                'users',
                'branchies',
                'services',
                'servicescategories',
                'products'
            ));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly created service request with items and attachments in storage.
     */
 /**
     * Store a newly created service request with items and attachments in storage.
     */
    public function storeServiceRequest(Request $request)
    {
        $request->validate([
            'branch_id'             => 'required|integer|exists:branchies,id',
            'committee_id'          => 'required|integer|exists:committees,id',
            'committee_chairman_id' => 'nullable|integer|exists:users,id',
            'service_category_id'   => 'nullable|integer|exists:service_categories,id',
            'request_date'          => 'required|date',
            'required_date'         => 'nullable|date|after_or_equal:request_date',
            'request_title'         => 'required|string|max:255',
            'purpose'               => 'nullable|string',
            'beneficiary_name'      => 'nullable|string|max:255',
            'priority'              => 'required|string|in:Low,Medium,High,Urgent',
            'requested_currency'    => 'required|string|max:10',

            // Multi-Item Validation (No pricing/discount fields)
            'items'                     => 'required|array|min:1',
            'items.*.service_catalog_id'=> 'nullable|integer|exists:service_catalog,id',
            'items.*.product_id'        => 'nullable|integer|exists:products,id',
            'items.*.item_type'         => 'required|string|in:Service,Product,Expense,Other',
            'items.*.item_description'  => 'required|string|max:255',
            'items.*.quantity'          => 'required|numeric|min:0.001',
            'items.*.unit_of_measure'   => 'nullable|string|max:50',

            // Attachment Validation
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $branch = Branch::findOrFail($request->branch_id);
            $committee = Committee::find($request->committee_id);

            // Generate Branch Abbreviation
            $branchWords = explode(' ', strtoupper($branch->branch_name));
            $branchPrefix = '';
            foreach ($branchWords as $word) {
                $branchPrefix .= substr($word, 0, 1);
            }
            $branchPrefix = substr($branchPrefix, 0, 3);

            // Reference & Request No Generation
            $year = date('Y');

            $prefix = 'IFLSG/SREQ/' . $branchPrefix . '/' . $year . '/';

            $lastRequest = ServiceRequest::where('ReferenceNo', 'like', $prefix . '%')
                ->orderByDesc('id')
                ->first();

            $nextNumber = $lastRequest
                ? ((int) substr($lastRequest->ReferenceNo, -4)) + 1
                : 1;

            do {
                $generatedRefNo = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber++;
            } while (ServiceRequest::where('ReferenceNo', $generatedRefNo)->exists());

            $requestNo = 'SR-' . date('Ymd') . '-' . mt_rand(100, 999);

            $serviceRequest = ServiceRequest::create([
                'ReferenceNo'                          => $generatedRefNo,
                'request_no'                           => $requestNo,
                'request_date'                         => $request->request_date,
                'required_date'                        => $request->required_date,
                'service_category_id'                  => $request->service_category_id,
                'requester_id'                         => $user->id,
                'committee_id'                         => $committee->id ?? null,
                'committee_name'                       => $committee->committee_name ?? null,
                'committee_chairman_id'                => $request->committee_chairman_id,
                'request_title'                        => strtoupper($request->request_title),
                'purpose'                              => $request->purpose ?? 'service',
                'beneficiary_name'                     => $request->beneficiary_name,
                'priority'                             => $request->priority,
                'estimated_total'                      => 0.00, // Total estimated price will be assigned at money request phase
                'requested_currency'                   => strtoupper($request->requested_currency),
                'request_status'                       => 'Pending Chairman Approval',
                'committee_chairman_approval_status'   => 'Pending',
                'group_chairman_approval_status'       => 'Pending',
                'money_status'                         => 'Unrequested',
                'company_id'                           => $branch->company_id,
                'branch_id'                            => $branch->id,
                'User_id'                              => $user->id,
                'Status'                               => 'Active',
                'AuditingStatus'                       => 'Pending',
                'ReportStatus'                         => 'Pending',
                'created_by'                           => $user->id,
                'updated_by'                           => $user->id,
            ]);

            // Process Items
            foreach ($request->items as $itemData) {
                $qty = (float) $itemData['quantity'];

                // Generate Unique Incremental Service Request Item Reference Number
                $itemPrefix = 'IFLSG/SITM/' . $year . '/';

                $lastItem = ServiceRequestItem::where('ReferenceNo', 'like', $itemPrefix . '%')
                    ->orderByDesc('id')
                    ->first();

                $nextItemNumber = $lastItem
                    ? ((int) substr($lastItem->ReferenceNo, -4)) + 1
                    : 1;

                do {
                    $itemRef = $itemPrefix . str_pad($nextItemNumber, 4, '0', STR_PAD_LEFT);
                    $nextItemNumber++;
                } while (ServiceRequestItem::where('ReferenceNo', $itemRef)->exists());

                ServiceRequestItem::create([
                    'ReferenceNo'        => $itemRef,
                    'service_request_id' => $serviceRequest->id,
                    'service_catalog_id' => $itemData['service_catalog_id'] ?? null,
                    'product_id'         => $itemData['product_id'] ?? null,
                    'item_type'          => $itemData['item_type'],
                    'item_description'   => $itemData['item_description'],
                    'quantity'           => $qty,
                    'unit_of_measure'    => $itemData['unit_of_measure'] ?? 'PCS',
                    'unit_price'         => 0.00,
                    'discount_amount'    => 0.00,
                    'line_total'         => 0.00,
                    'notes'              => $itemData['notes'] ?? null,
                    'company_id'         => $branch->company_id,
                    'branch_id'          => $branch->id,
                    'User_id'            => $user->id,
                    'Status'             => 'Active',
                    'AuditingStatus'     => 'Pending',
                    'ReportStatus'       => 'Pending',
                    'created_by'         => $user->id,
                    'updated_by'         => $user->id,
                ]);
            }

            // Handle Attachments Upload
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('service_requests/attachments', $fileName, 'public');

                    // Generate Unique Incremental Service Request Attachment Reference Number
                    $attPrefix = 'IFLSG/SATT/' . $year . '/';

                    $lastAttachment = ServiceRequestAttachment::where('ReferenceNo', 'like', $attPrefix . '%')
                        ->orderByDesc('id')
                        ->first();

                    $nextAttNumber = $lastAttachment
                        ? ((int) substr($lastAttachment->ReferenceNo, -4)) + 1
                        : 1;

                    do {
                        $attRef = $attPrefix . str_pad($nextAttNumber, 4, '0', STR_PAD_LEFT);
                        $nextAttNumber++;
                    } while (ServiceRequestAttachment::where('ReferenceNo', $attRef)->exists());

                    ServiceRequestAttachment::create([
                        'ReferenceNo'        => $attRef,
                        'service_request_id' => $serviceRequest->id,
                        'attachment_type'    => 'Supporting Document',
                        'file_name'          => $file->getClientOriginalName(),
                        'file_path'          => $filePath,
                        'mime_type'          => $file->getClientMimeType(),
                        'file_size'          => $file->getSize(),
                        'description'        => 'Attachment for request ' . $requestNo,
                        'company_id'         => $branch->company_id,
                        'branch_id'          => $branch->id,
                        'User_id'            => $user->id,
                        'Status'             => 'Active',
                        'AuditingStatus'     => 'Pending',
                        'ReportStatus'       => 'Pending',
                        'created_by'         => $user->id,
                        'updated_by'         => $user->id,
                    ]);
                }
            }

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Service Request Created Successfully (' . $requestNo . ')'
            );

            return redirect()->route('viewServiceRequest', encrypt($serviceRequest->id));

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
     * Display profile details of a specific service request.
     */
    public function viewServiceRequest($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $data = ServiceRequest::with([
                'requester',
                'committee',
                'committeeChairman',
                'committeeApprover',
                'groupChairmanApprover',
                'items.service',
                'items.product',
                'attachments',
                'moneyRequests',
                'company',
                'branch',
                'user'
            ])->findOrFail($id);

            return view('in.services.services.viewServiceRequest', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Show the form for editing the specified service request.
     */
    public function editServiceRequest($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = ServiceRequest::with(['items', 'attachments'])->findOrFail($id);

            if (in_array($data->request_status, ['Approved', 'Completed', 'Cancelled'])) {
                Alert::warning(
                    'Action Restricted',
                    'Approved, Completed, or Cancelled service requests cannot be edited.'
                );
                return back();
            }

            $committees = Committee::where('Status', 'Active')->orderBy('committee_name')->get();
            $users      = User::where('Status', 'Active')->orderBy('name')->get();
            $branchies  = Branch::where('Status', 'Active')->with('company')->get();
            $services   = ServiceCatalog::where('Status', 'Active')->get();
            $products   = Product::where('Status', 'Active')->get();

            return view('in.services.services.editServiceRequest', compact(
                'data',
                'committees',
                'users',
                'branchies',
                'services',
                'products'
            ));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified service request in storage.
     */
    public function updateServiceRequest(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'branch_id'             => 'required|integer|exists:branchies,id',
            'committee_id'          => 'required|integer|exists:committees,id',
            'committee_chairman_id' => 'nullable|integer|exists:users,id',
            'request_date'          => 'required|date',
            'required_date'         => 'nullable|date|after_or_equal:request_date',
            'request_title'         => 'required|string|max:255',
            'purpose'               => 'nullable|string',
            'beneficiary_name'      => 'nullable|string|max:255',
            'priority'              => 'required|string|in:Low,Medium,High,Urgent',
            'requested_currency'    => 'required|string|max:10',
        ]);

        DB::beginTransaction();
        try {
            $serviceRequest = ServiceRequest::findOrFail($id);
            $branch = Branch::findOrFail($request->branch_id);
            $committee = Committee::find($request->committee_id);

            $serviceRequest->update([
                'branch_id'             => $branch->id,
                'company_id'            => $branch->company_id,
                'committee_id'          => $committee->id ?? null,
                'committee_name'        => $committee->committee_name ?? null,
                'committee_chairman_id' => $request->committee_chairman_id,
                'request_date'          => $request->request_date,
                'required_date'         => $request->required_date,
                'request_title'         => strtoupper($request->request_title),
                'purpose'               => $request->purpose,
                'beneficiary_name'      => $request->beneficiary_name,
                'priority'              => $request->priority,
                'requested_currency'    => strtoupper($request->requested_currency),
                'updated_by'            => Auth::id(),
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Service Request Header Updated Successfully'
            );

            return redirect()->route('viewServiceRequest', encrypt($serviceRequest->id));

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
     * Committee Chairman Approval / Rejection action.
     */
    public function committeeChairmanApproval(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'status'  => 'required|in:Approved,Rejected',
            'comment' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $serviceRequest = ServiceRequest::findOrFail($id);

            $isApproved = $request->status === 'Approved';

            $serviceRequest->update([
                'committee_chairman_approval_status'  => $request->status,
                'committee_chairman_approved_by'      => $user->id,
                'committee_chairman_approved_at'      => now(),
                'committee_chairman_approval_comment' => $request->comment,
                'request_status'                      => $isApproved ? 'Pending Group Chairman Approval' : 'Rejected',
                'updated_by'                          => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Committee Chairman Approval Status Saved (' . $request->status . ')'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Group Chairman Approval / Rejection action.
     */
    public function groupChairmanApproval(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'status'  => 'required|in:Approved,Rejected',
            'comment' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $serviceRequest = ServiceRequest::findOrFail($id);

            $isApproved = $request->status === 'Approved';

            $serviceRequest->update([
                'group_chairman_approval_status'  => $request->status,
                'group_chairman_approved_by'      => $user->id,
                'group_chairman_approved_at'      => now(),
                'group_chairman_approval_comment' => $request->comment,
                'request_status'                  => $isApproved ? 'Approved' : 'Rejected',
                'updated_by'                      => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Group Chairman Final Approval Status Saved (' . $request->status . ')'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Soft delete the specified service request.
     */
    public function destroyServiceRequest($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $serviceRequest = ServiceRequest::findOrFail($id);

            if ($serviceRequest->committee_chairman_approval_status !== 'Pending') {
                Alert::error(
                    'Action Restricted',
                    'Cannot delete a Service request that has already been approved.'
                );

                return back();
            }
            $serviceRequest->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $serviceRequest->delete();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Service Request Removed Successfully'
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
     * Display a listing of soft-deleted service requests.
     */
    public function deletedServiceRequest()
    {
        try {
            $data = ServiceRequest::onlyTrashed()
                ->with(['requester', 'committee', 'branch'])
                ->latest('deleted_at')
                ->get();

            return view('in.services.services.deletedServiceRequest', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted service request back to Active status.
     */
    public function restoreServiceRequest($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $serviceRequest = ServiceRequest::withTrashed()->findOrFail($id);

            $serviceRequest->restore();

            $serviceRequest->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Service Request Restored Successfully'
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

    public function printServiceRequest($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = ServiceRequest::with([
                'requester',
                'committee',
                'committeeChairman',
                'committeeApprover',
                'groupChairmanApprover',
                'items.service',
                'items.product',
                'company',
                'branch'
            ])->findOrFail($id);

            return view('in.services.services.printServiceRequest', compact('data'));
        } catch (\Throwable $th) {
            Alert::error('Sorry!', 'Could not generate printable form.');
            return back();
        }
    }
    public function printServiceMoneyRequest($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = ServiceMoneyRequest::with([
                'requester',
                'committee',
                'committeeChairman',
                'committeeApprover',
                'groupChairmanApprover',
                'items.service',
                'items.product',
                'company',
                'branch'
            ])->findOrFail($id);

            return view('in.services.serviceMoneyRequest.printServiceMoneyRequest', compact('data'));
        } catch (\Throwable $th) {
            Alert::error('Sorry!', 'Could not generate printable form.');
            return back();
        }
    }


    /**
     * Display a listing of active service money requests.
     */
    public function serviceMoneyRequest(Request $request)
    {
        try {
            $query = ServiceMoneyRequest::where('Status', 'Active')
                ->with([
                    'serviceRequest',
                    'preparedBy',
                    'financeChairmanApprover',
                    'groupChairmanApprover',
                    'processedBy',
                    'financialPostedBy',
                    'company',
                    'branch'
                ])
                ->withCount('vouchers');

            if ($request->filled('money_status')) {
                $query->where('money_status', $request->money_status);
            }

            if ($request->filled('finance_chairman_approval_status')) {
                $query->where('finance_chairman_approval_status', $request->finance_chairman_approval_status);
            }

            if ($request->filled('group_chairman_approval_status')) {
                $query->where('group_chairman_approval_status', $request->group_chairman_approval_status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('money_request_no', 'like', "%{$search}%")
                      ->orWhere('ReferenceNo', 'like', "%{$search}%")
                      ->orWhere('payee_name', 'like', "%{$search}%")
                      ->orWhere('payment_purpose', 'like', "%{$search}%");
                });
            }

            $data = $query->latest('id')->paginate(15)->withQueryString();

            return view('in.services.serviceMoneyRequest.serviceMoneyRequest', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Show form to prepare a new money request for an approved Service Request.
     */
    public function createServiceMoneyRequest($serviceRequestId = null)
    {
        try {
            $selectedServiceRequest = null;
            if ($serviceRequestId) {
                $decryptedId = Crypt::decrypt($serviceRequestId);
                $selectedServiceRequest = ServiceRequest::with(['items', 'branch', 'company'])->findOrFail($decryptedId);
            }

            // Fetch Approved Service Requests that are either Unrequested or Partially Requested
            $serviceRequests = ServiceRequest::where('Status', 'Active')
                ->where('request_status', 'Approved')
                ->whereIn('money_status', ['Unrequested', 'Partially Requested'])
                ->orderBy('request_no')
                ->get();

            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.services.serviceMoneyRequest.createServiceMoneyRequest', compact('serviceRequests', 'selectedServiceRequest', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly prepared money request in storage.
     */
    public function storeServiceMoneyRequest(Request $request)
    {
        $request->validate([
            'service_request_id' => 'required|integer|exists:service_requests,id',
            'branch_id'          => 'required|integer|exists:branchies,id',
            'money_request_date' => 'required|date',
            'payee_name'         => 'required|string|max:255',
            'payment_purpose'    => 'required|string',
            'amount_requested'   => 'required|numeric|min:0.01',
            'currency_code'      => 'required|string|max:10',
            'cashier_comment'    => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $serviceRequest = ServiceRequest::findOrFail($request->service_request_id);
            $branch = Branch::findOrFail($request->branch_id);

            // Generate Branch Abbreviation
            $branchWords = explode(' ', strtoupper($branch->branch_name));
            $branchPrefix = '';
            foreach ($branchWords as $word) {
                $branchPrefix .= substr($word, 0, 1);
            }
            $branchPrefix = substr($branchPrefix, 0, 3);

            // Reference Number Generation
            $year = date('Y');
            $prefix = 'IFLSG/SMRQ/' . $branchPrefix . '/' . $year . '/';

            $lastRequest = ServiceMoneyRequest::where('ReferenceNo', 'like', $prefix . '%')
                ->orderByDesc('id')
                ->first();

            $nextNumber = $lastRequest
                ? ((int) substr($lastRequest->ReferenceNo, -4)) + 1
                : 1;

            do {
                $generatedRefNo = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $nextNumber++;
            } while (ServiceMoneyRequest::where('ReferenceNo', $generatedRefNo)->exists());

            $moneyRequestNo = 'SMR-' . date('Ymd') . '-' . mt_rand(100, 999);

            $moneyRequest = ServiceMoneyRequest::create([
                'ReferenceNo'                       => $generatedRefNo,
                'money_request_no'                  => $moneyRequestNo,
                'service_request_id'                => $serviceRequest->id,
                'money_request_date'                => $request->money_request_date,
                'payee_name'                        => strtoupper($request->payee_name),
                'payment_purpose'                   => $request->payment_purpose,
                'amount_requested'                  => $request->amount_requested,
                'amount_approved'                   => 0.00,
                'amount_processed'                  => 0.00,
                'currency_code'                     => strtoupper($request->currency_code),
                'prepared_by'                       => $user->id,
                'prepared_at'                       => now(),
                'cashier_comment'                   => $request->cashier_comment,
                'finance_chairman_approval_status'  => 'Pending',
                'group_chairman_approval_status'    => 'Pending',
                'money_status'                      => 'Prepared',
                'financial_posting_status'          => 'Unposted',
                'notes'                             => $request->notes,
                'company_id'                        => $branch->company_id,
                'branch_id'                         => $branch->id,
                'User_id'                           => $user->id,
                'Status'                            => 'Active',
                'AuditingStatus'                    => 'Pending',
                'ReportStatus'                      => 'Pending',
                'created_by'                        => $user->id,
                'updated_by'                        => $user->id,
            ]);

            // Update parent ServiceRequest money tracking totals and status
            $newMoneyRequestedTotal = (float) $serviceRequest->money_requested_total + (float) $request->amount_requested;
            $serviceRequest->update([
                'money_requested_total' => $newMoneyRequestedTotal,
                'money_status'          => 'Requested',
                'updated_by'            => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Service Money Request Prepared Successfully (' . $moneyRequestNo . ')'
            );

            return redirect()->route('viewServiceMoneyRequest', encrypt($moneyRequest->id));

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
     * Display profile/details of a specific money request.
     */
    public function viewServiceMoneyRequest($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $data = ServiceMoneyRequest::with([
                'serviceRequest.requester',
                'serviceRequest.items',
                'preparedBy',
                'financeChairmanApprover',
                'groupChairmanApprover',
                'processedBy',
                'financialPostedBy',
                'vouchers',
                'company',
                'branch',
                'user'
            ])->findOrFail($id);

            return view('in.services.serviceMoneyRequest.viewServiceMoneyRequest', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Show form for editing a prepared money request.
     */
    public function editServiceMoneyRequest($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = ServiceMoneyRequest::findOrFail($id);

            if ($data->finance_chairman_approval_status === 'Approved' || $data->money_status === 'Paid') {
                Alert::warning(
                    'Action Denied',
                    'Approved or Paid money requests cannot be edited.'
                );
                return back();
            }

            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.services.serviceMoneyRequest.editServiceMoneyRequest', compact('data', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified money request in storage.
     */
    public function updateServiceMoneyRequest(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'money_request_date' => 'required|date',
            'payee_name'         => 'required|string|max:255',
            'payment_purpose'    => 'required|string',
            'amount_requested'   => 'required|numeric|min:0.01',
            'currency_code'      => 'required|string|max:10',
            'cashier_comment'    => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $moneyRequest = ServiceMoneyRequest::findOrFail($id);

            if ($moneyRequest->finance_chairman_approval_status === 'Approved' || $moneyRequest->money_status === 'Paid') {
                Alert::warning('Warning', 'Approved or Paid money requests cannot be updated.');
                return back();
            }

            // Calculate change in requested amount for parent ServiceRequest adjustment
            $diffAmount = (float) $request->amount_requested - (float) $moneyRequest->amount_requested;

            $moneyRequest->update([
                'money_request_date' => $request->money_request_date,
                'payee_name'         => strtoupper($request->payee_name),
                'payment_purpose'    => $request->payment_purpose,
                'amount_requested'   => $request->amount_requested,
                'currency_code'      => strtoupper($request->currency_code),
                'cashier_comment'    => $request->cashier_comment,
                'updated_by'         => Auth::id(),
            ]);

            // Adjust parent ServiceRequest requested total
            if ($moneyRequest->serviceRequest) {
                $parentSR = $moneyRequest->serviceRequest;
                $parentSR->update([
                    'money_requested_total' => (float) $parentSR->money_requested_total + $diffAmount,
                    'updated_by'            => Auth::id(),
                ]);
            }

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Service Money Request Updated Successfully'
            );

            return redirect()->route('viewServiceMoneyRequest', encrypt($moneyRequest->id));

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
     * Finance Committee Chairman Approval action.
    */
    public function financeChairmanApproval(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'status'          => 'required|in:Approved,Rejected',
            'amount_approved' => 'required_if:status,Approved|numeric|min:0',
            'comment'         => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $moneyRequest = ServiceMoneyRequest::findOrFail($id);

            $isApproved = $request->status === 'Approved';
            $approvedAmt = $isApproved ? (float) $request->amount_approved : 0.00;

            $moneyRequest->update([
                'finance_chairman_approval_status'  => $request->status,
                'finance_chairman_approved_by'      => $user->id,
                'finance_chairman_approved_at'      => now(),
                'finance_chairman_approval_comment' => $request->comment,
                'amount_approved'                   => $approvedAmt,
                'money_status'                      => $isApproved ? 'Approved by Finance Chairman' : 'Rejected',
                'updated_by'                          => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Finance Chairman Approval Saved (' . $request->status . ')'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Group Chairman Final Approval action.
     */
    public function groupChairmanMoneyApproval(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'status'  => 'required|in:Approved,Rejected',
            'comment' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $moneyRequest = ServiceMoneyRequest::findOrFail($id);

            $isApproved = $request->status === 'Approved';

            $moneyRequest->update([
                'group_chairman_approval_status'  => $request->status,
                'group_chairman_approved_by'      => $user->id,
                'group_chairman_approved_at'      => now(),
                'group_chairman_approval_comment' => $request->comment,
                'money_status'                    => $isApproved ? 'Approved for Payment' : 'Rejected',
                'updated_by'                      => $user->id,
            ]);

            // Update parent ServiceRequest approved money totals
            if ($isApproved && $moneyRequest->serviceRequest) {
                $parentSR = $moneyRequest->serviceRequest;
                $parentSR->update([
                    'money_approved_total' => (float) $parentSR->money_approved_total + (float) $moneyRequest->amount_approved,
                    'updated_by'           => $user->id,
                ]);
            }

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Group Chairman Approval Saved (' . $request->status . ')'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Process Payment / Disbursement for the Money Request.
     */
    public function processPayment(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'amount_processed'  => 'required|numeric|min:0.01',
            'payment_method'    => 'required|string|max:100',
            'payment_reference' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $moneyRequest = ServiceMoneyRequest::findOrFail($id);

            $processedAmt = (float) $request->amount_processed;

            $moneyRequest->update([
                'amount_processed'  => $processedAmt,
                'payment_method'    => $request->payment_method,
                'payment_reference' => $request->payment_reference,
                'processed_by'      => $user->id,
                'processed_at'      => now(),
                'money_status'      => 'Paid',
                'updated_by'        => $user->id,
            ]);

            // Update parent ServiceRequest processed money total
            if ($moneyRequest->serviceRequest) {
                $parentSR = $moneyRequest->serviceRequest;
                $newProcessedTotal = (float) $parentSR->money_processed_total + $processedAmt;
                $parentSR->update([
                    'money_processed_total' => $newProcessedTotal,
                    'completed_at'          => now(),
                    'updated_by'            => $user->id,
                ]);
            }

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Payment Disbursement Processed Successfully'
            );

            return back();

        } catch (\Throwable $th) {
            DB::rollBack();

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );

            return back();
        }
    }

    /**
     * Soft delete the specified money request.
     */
    public function destroyServiceMoneyRequest($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $moneyRequest = ServiceMoneyRequest::findOrFail($id);

            if ($moneyRequest->finance_chairman_approval_status !== 'Pending') {
                Alert::error(
                    'Action Restricted',
                    'Cannot delete a money request that has already been approved.'
                );

                return back();
            }

            $moneyRequest->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $moneyRequest->delete();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Service Money Request Removed Successfully'
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
     * Display a listing of soft-deleted money requests.
     */
    public function deletedServiceMoneyRequest()
    {
        try {
            $data = ServiceMoneyRequest::onlyTrashed()
                ->with(['serviceRequest', 'preparedBy', 'branch'])
                ->latest('deleted_at')
                ->get();

            return view('in.services.serviceMoneyRequest.deletedServiceMoneyRequest', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted money request back to Active status.
     */
    public function restoreServiceMoneyRequest($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $moneyRequest = ServiceMoneyRequest::withTrashed()->findOrFail($id);

            $moneyRequest->restore();

            $moneyRequest->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Service Money Request Restored Successfully'
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