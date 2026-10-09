<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Models\SupplierAward;
use App\Models\UnitOfMeasure;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\SupplierInvoice;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of purchase orders with filters.
     */
    public function purchaseOrder(Request $request)
    {
        // try {
            $query = PurchaseOrder::where('Status', 'Active')
                ->with([
                    'purchaseRequest',
                    'supplierAward',
                    'supplier',
                    'branch',
                    'approver',
                    'items.product'
                ]);

            if ($request->filled('order_status')) {
                $query->where('order_status', $request->order_status);
            }

            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->filled('from_date')) {
                $query->whereDate('order_date', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('order_date', '<=', $request->to_date);
            }

            $suppliers = Supplier::where('Status', 'Active')->orderBy('SupplierName')->get();

            $data = $query->latest('id')->paginate(20)->withQueryString();

            return view('in.suppliers.purchaseorders.purchaseOrder', compact('data', 'suppliers'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**ers
     * Show form for creating a new purchase order.
     */
    public function createpurchaseOrder()
    {
        try {
            $suppliers = Supplier::where('Status', 'Active')->orderBy('SupplierName')->get();
            $purchaseRequests = PurchaseRequest::where('Status', 'Approved')->get();
            $supplierAwards = SupplierAward::where('award_status', 'Approved')->get();
            $products = Product::where('Status', 'Active')->orderBy('product_name')->get();
            $units = UnitOfMeasure::where('Status', 'Active')->orderBy('UnitName')->get();
            $branchies = Branch::where('Status', 'Active')->get();

            return view('in.suppliers.purchaseorders.createpurchaseOrder', compact(
                'suppliers',
                'purchaseRequests',
                'supplierAwards',
                'products',
                'units',
                'branchies'
            ));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly created purchase order in storage.
     */
    public function storepurchaseOrder(Request $request)
    {
        $request->validate([
            'branch_id'                   => 'required|integer|exists:branchies,id',
            'supplier_id'                 => 'required|integer|exists:suppliers,id',
            'purchase_request_id'         => 'nullable|integer|exists:purchase_requests,id',
            'supplier_award_id'           => 'nullable|integer|exists:supplier_awards,id',
            'order_date'                  => 'required|date',
            'expected_delivery_date'      => 'nullable|date|after_or_equal:order_date',
            'currency_code'               => 'required|string|max:10',
            'payment_terms'               => 'nullable|string|max:255',
            'delivery_terms'              => 'nullable|string|max:255',
            'notes'                       => 'nullable|string',
            'items'                       => 'required|array|min:1',
            'items.*.product_id'          => 'required|integer|exists:products,id',
            'items.*.unit_of_measure_id'   => 'nullable|integer|exists:units_of_measure,id',
            'items.*.ordered_quantity'    => 'required|numeric|min:0.001',
            'items.*.unit_price'          => 'required|numeric|min:0',
            'items.*.tax_rate'            => 'nullable|numeric|min:0|max:100',
            'items.*.discount_amount'     => 'nullable|numeric|min:0',
            'items.*.description'         => 'nullable|string',
        ]);

        DB::beginTransaction();
        // try {
            $user = Auth::user();
            $branch = Branch::findOrFail($request->branch_id);
            $poNumber = $this->generatePONumber();

            $purchaseOrder = PurchaseOrder::create([
                'purchase_order_no'      => $poNumber,
                'purchase_request_id'    => $request->purchase_request_id,
                'supplier_award_id'      => $request->supplier_award_id,
                'supplier_id'            => $request->supplier_id,
                'order_date'             => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'currency_code'          => strtoupper($request->currency_code),
                'subtotal'               => 0,
                'tax_amount'             => 0,
                'discount_amount'        => 0,
                'total_amount'           => 0,
                'order_status'           => 'Draft',
                'payment_terms'          => $request->payment_terms,
                'delivery_terms'         => $request->delivery_terms,
                'notes'                  => $request->notes,
                'company_id'             => $branch->company_id,
                'branch_id'              => $branch->id,
                'User_id'                => $user->id,
                'Status'                 => 'Active',
                'AuditingStatus'         => 'Pending',
                'ReportStatus'           => 'Pending',
                'created_by'             => $user->id,
                'updated_by'             => $user->id,
            ]);

            $calculatedSubtotal = 0;
            $calculatedTaxTotal = 0;
            $calculatedDiscountTotal = 0;

            foreach ($request->items as $item) {
                $qty = (float) $item['ordered_quantity'];
                $price = (float) $item['unit_price'];
                $taxRate = (float) ($item['tax_rate'] ?? 0);
                $discount = (float) ($item['discount_amount'] ?? 0);

                $lineSubtotal = $qty * $price;
                $lineTax = ($lineSubtotal - $discount) * ($taxRate / 100);
                if ($lineTax < 0) $lineTax = 0;

                $lineTotal = ($lineSubtotal - $discount) + $lineTax;

                PurchaseOrderItem::create([
                    'purchase_order_id'          => $purchaseOrder->id,
                    'supplier_quotation_item_id' => $item['supplier_quotation_item_id'] ?? null,
                    'product_id'                 => $item['product_id'],
                    'unit_of_measure_id'         => $item['unit_of_measure_id'] ?? null,
                    'description'                => $item['description'] ?? null,
                    'ordered_quantity'          => $qty,
                    'unit_price'                => $price,
                    'tax_rate'                  => $taxRate,
                    'tax_amount'                 => $lineTax,
                    'discount_amount'           => $discount,
                    'total_amount'              => $lineTotal,
                    'received_quantity'         => 0,
                    'pending_quantity'          => $qty,
                    'item_status'                => 'Pending',
                    'remarks'                    => $item['remarks'] ?? null,
                ]);

                $calculatedSubtotal += $lineSubtotal;
                $calculatedTaxTotal += $lineTax;
                $calculatedDiscountTotal += $discount;
            }

            $overallTotal = ($calculatedSubtotal - $calculatedDiscountTotal) + $calculatedTaxTotal;

            $purchaseOrder->update([
                'subtotal'        => $calculatedSubtotal,
                'tax_amount'      => $calculatedTaxTotal,
                'discount_amount' => $calculatedDiscountTotal,
                'total_amount'    => $overallTotal,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Purchase Order Created Successfully (' . $poNumber . ')'
            );

            return redirect()->route('purchaseOrder');

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
     * Display details/profile of a specific purchase order.
     */
    public function viewpurchaseOrder($id)
    {
        // try {
            $id = Crypt::decrypt($id);

            $data = PurchaseOrder::with([
                'purchaseRequest',
                'supplierAward',
                'supplier',
                'approver',
                'company',
                'branch',
                'creator',
                'updater',
                'items.product',
                'items.unitOfMeasure',
                'goodsReceipts',
                'invoices'
            ])->findOrFail($id);

            return view('in.suppliers.purchaseorders.viewpurchaseOrder', compact('data'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Show form for editing an existing purchase order.
     */
    public function editpurchaseOrder($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = PurchaseOrder::with('items')->findOrFail($id);

            if (in_array($data->order_status, ['Approved', 'Issued', 'Completed'])) {
                Alert::warning(
                    'Action Denied',
                    'Approved, Issued, or Completed Purchase Orders cannot be edited.'
                );
                return back();
            }

            $suppliers = Supplier::where('Status', 'Active')->orderBy('SupplierName')->get();
            $purchaseRequests = PurchaseRequest::where('Status', 'Approved')->get();
            $supplierAwards = SupplierAward::where('award_status', 'Approved')->get();
            $products = Product::where('Status', 'Active')->orderBy('product_name')->get();
            $units = UnitOfMeasure::where('Status', 'Active')->orderBy('UnitName')->get();
            $branchies = Branch::where('Status', 'Active')->get();

            return view('in.suppliers.purchaseorders.editpurchaseOrder', compact(
                'data',
                'suppliers',
                'purchaseRequests',
                'supplierAwards',
                'products',
                'units',
                'branchies'
            ));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified purchase order in storage.
     */
    public function updatepurchaseOrder(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'branch_id'                   => 'required|integer|exists:branchies,id',
            'supplier_id'                 => 'required|integer|exists:suppliers,id',
            'purchase_request_id'         => 'nullable|integer|exists:purchase_requests,id',
            'supplier_award_id'           => 'nullable|integer|exists:supplier_awards,id',
            'order_date'                  => 'required|date',
            'expected_delivery_date'      => 'nullable|date|after_or_equal:order_date',
            'currency_code'               => 'required|string|max:10',
            'payment_terms'               => 'nullable|string|max:255',
            'delivery_terms'              => 'nullable|string|max:255',
            'notes'                       => 'nullable|string',
            'items'                       => 'required|array|min:1',
            'items.*.product_id'          => 'required|integer|exists:products,id',
            'items.*.unit_of_measure_id'   => 'nullable|integer|exists:units_of_measure,id',
            'items.*.ordered_quantity'    => 'required|numeric|min:0.001',
            'items.*.unit_price'          => 'required|numeric|min:0',
            'items.*.tax_rate'            => 'nullable|numeric|min:0|max:100',
            'items.*.discount_amount'     => 'nullable|numeric|min:0',
            'items.*.description'         => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $purchaseOrder = PurchaseOrder::findOrFail($id);

            if (in_array($purchaseOrder->order_status, ['Approved', 'Issued', 'Completed'])) {
                Alert::warning('Warning', 'Approved, Issued, or Completed Purchase Orders cannot be modified.');
                return back();
            }

            $branch = Branch::findOrFail($request->branch_id);

            $purchaseOrder->update([
                'purchase_request_id'    => $request->purchase_request_id,
                'supplier_award_id'      => $request->supplier_award_id,
                'supplier_id'            => $request->supplier_id,
                'order_date'             => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'currency_code'          => strtoupper($request->currency_code),
                'payment_terms'          => $request->payment_terms,
                'delivery_terms'         => $request->delivery_terms,
                'notes'                  => $request->notes,
                'company_id'             => $branch->company_id,
                'branch_id'              => $branch->id,
                'updated_by'             => Auth::id(),
            ]);

            $purchaseOrder->items()->delete();

            $calculatedSubtotal = 0;
            $calculatedTaxTotal = 0;
            $calculatedDiscountTotal = 0;

            foreach ($request->items as $item) {
                $qty = (float) $item['ordered_quantity'];
                $price = (float) $item['unit_price'];
                $taxRate = (float) ($item['tax_rate'] ?? 0);
                $discount = (float) ($item['discount_amount'] ?? 0);

                $lineSubtotal = $qty * $price;
                $lineTax = ($lineSubtotal - $discount) * ($taxRate / 100);
                if ($lineTax < 0) $lineTax = 0;

                $lineTotal = ($lineSubtotal - $discount) + $lineTax;

                PurchaseOrderItem::create([
                    'purchase_order_id'          => $purchaseOrder->id,
                    'supplier_quotation_item_id' => $item['supplier_quotation_item_id'] ?? null,
                    'product_id'                 => $item['product_id'],
                    'unit_of_measure_id'         => $item['unit_of_measure_id'] ?? null,
                    'description'                => $item['description'] ?? null,
                    'ordered_quantity'          => $qty,
                    'unit_price'                => $price,
                    'tax_rate'                  => $taxRate,
                    'tax_amount'                 => $lineTax,
                    'discount_amount'           => $discount,
                    'total_amount'              => $lineTotal,
                    'received_quantity'         => 0,
                    'pending_quantity'          => $qty,
                    'item_status'                => 'Pending',
                ]);

                $calculatedSubtotal += $lineSubtotal;
                $calculatedTaxTotal += $lineTax;
                $calculatedDiscountTotal += $discount;
            }

            $overallTotal = ($calculatedSubtotal - $calculatedDiscountTotal) + $calculatedTaxTotal;

            $purchaseOrder->update([
                'subtotal'        => $calculatedSubtotal,
                'tax_amount'      => $calculatedTaxTotal,
                'discount_amount' => $calculatedDiscountTotal,
                'total_amount'    => $overallTotal,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Purchase Order Updated Successfully'
            );

            return redirect()->route('viewpurchaseOrder', encrypt($purchaseOrder->id));

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
     * Approve the specified purchase order.
     */
    public function approvepurchaseOrder(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $purchaseOrder = PurchaseOrder::findOrFail($id);

            if ($purchaseOrder->order_status === 'Approved') {
                Alert::warning('Notice', 'Purchase Order is already approved.');
                return back();
            }

            $purchaseOrder->update([
                'order_status' => 'Approved',
                'approved_by'  => $user->id,
                'approved_at'  => now(),
                'notes'        => $request->notes ?? $purchaseOrder->notes,
                'updated_by'   => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Purchase Order Approved Successfully'
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
     * Reject or Cancel a purchase order.
     */
    public function rejectpurchaseOrder(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'notes' => 'required|string|min:3',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $purchaseOrder = PurchaseOrder::findOrFail($id);

            $purchaseOrder->update([
                'order_status' => 'Cancelled',
                'notes'        => $request->notes,
                'updated_by'   => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Purchase Order Cancelled / Rejected Successfully'
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
     * Soft delete a purchase order.
     */
    public function destroypurchaseOrder($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $purchaseOrder = PurchaseOrder::findOrFail($id);

            $purchaseOrder->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $purchaseOrder->delete();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Purchase Order Soft Deleted Successfully'
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
     * Display soft-deleted purchase orders.
     */
    public function deletedpurchaseOrder()
    {
        try {
            $data = PurchaseOrder::onlyTrashed()
                ->with(['supplier', 'deleter'])
                ->latest('deleted_at')
                ->get();

            return view('in.suppliers.purchaseorders.deletedpurchaseOrder', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted purchase order.
     */
    public function restorepurchaseOrder($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $purchaseOrder = PurchaseOrder::withTrashed()->findOrFail($id);

            $purchaseOrder->restore();

            $purchaseOrder->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Purchase Order Restored Successfully'
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
     * Generate unique sequence PO number (PO-YYYY-00001).
     */
    private function generatePONumber(): string
    {
        $year = date('Y');

        // Match the exact prefix format used in the return statement
        $lastPO = PurchaseOrder::where('purchase_order_no', 'like', 'IFLSG/PO/' . $year . '/%')
            ->orderByDesc('id')
            ->first();

        if (!$lastPO) {
            $number = 1;
        } else {
            // Extracts the trailing 5-digit sequence (e.g., 00001 -> 1)
            $lastNumber = (int) substr($lastPO->purchase_order_no, -5);
            $number = $lastNumber + 1;
        }

        return 'IFLSG/PO/' . $year . '/' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Display a listing of existing Goods Receipts.
     */
    public function goodsReceipt(Request $request)
    {
        // try {
            $query = GoodsReceipt::where('Status', 'Active')
                ->with(['purchaseOrder', 'supplier', 'receivedBy', 'branch', 'items']);

            if ($request->filled('receipt_status')) {
                $query->where('receipt_status', $request->receipt_status);
            }

            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->filled('from_date')) {
                $query->whereDate('receipt_date', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('receipt_date', '<=', $request->to_date);
            }

            $data = $query->latest('id')->paginate(20)->withQueryString();

            return view('in.suppliers.goodsreceipts.goodsReceipt', compact('data'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical for support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Display a listing of approved Purchase Orders that have pending unreceived quantities.
     */
    public function unreceivedPurchaseOrders(Request $request)
    {
        try {
            $query = PurchaseOrder::where('Status', 'Active')
                ->whereIn('order_status', ['Approved', 'Issued', 'Partially Received'])
                ->whereHas('items', function ($q) {
                    $q->where('pending_quantity', '>', 0);
                })
                ->with(['supplier', 'branch', 'items.product']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('purchase_order_no', 'like', "%{$search}%")
                      ->orWhereHas('supplier', function ($sq) use ($search) {
                          $sq->where('SupplierName', 'like', "%{$search}%");
                      });
                });
            }

            $data = $query->latest('id')->paginate(20)->withQueryString();

            return view('in.suppliers.goodsreceipts.unreceivedPurchaseOrders', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Show receiving form populated with line items for a selected Purchase Order.
     */
    public function creategoodsReceipt($encryptedPoId)
    {
        try {
            $poId = Crypt::decrypt($encryptedPoId);

            $purchaseOrder = PurchaseOrder::where('Status', 'Active')
                ->with(['supplier', 'branch', 'items.product', 'items.unitOfMeasure'])
                ->findOrFail($poId);

            // Filter items that still have pending quantity to receive
            $pendingItems = $purchaseOrder->items->filter(function ($item) {
                return $item->pending_quantity > 0;
            });

            if ($pendingItems->isEmpty()) {
                Alert::warning(
                    'Fully Received',
                    'All items for Purchase Order (' . $purchaseOrder->purchase_order_no . ') have already been fully received.'
                );
                return redirect()->route('unreceivedPurchaseOrders');
            }

            return view('in.suppliers.goodsreceipts.creategoodsReceipt', compact('purchaseOrder', 'pendingItems'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Process receiving items, create GoodsReceipt & Items, and evaluate partial/full PO status.
     */
    public function storegoodsReceipt(Request $request, $encryptedPoId)
    {
        $poId = Crypt::decrypt($encryptedPoId);

        $request->validate([
            'receipt_date'             => 'required|date',
            'supplier_delivery_note'   => 'nullable|string|max:100',
            'vehicle_no'               => 'nullable|string|max:50',
            'remarks'                  => 'nullable|string',
            'attachment'               => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'items'                    => 'required|array|min:1',
            'items.*.po_item_id'       => 'required|integer|exists:purchase_order_items,id',
            'items.*.received_qty'     => 'required|numeric|min:0',
            'items.*.accepted_qty'     => 'required|numeric|min:0',
            'items.*.rejected_qty'     => 'nullable|numeric|min:0',
            'items.*.unit_price'       => 'required|numeric|min:0', // Purchased unit price (editable)
            'items.*.rejection_reason' => 'nullable|string',
            'items.*.remarks'          => 'nullable|string',
        ]);

        DB::beginTransaction();
        // try {
            $user = Auth::user();
            $purchaseOrder = PurchaseOrder::with('items')->findOrFail($poId);
            $receiptNo = $this->generateGRNNumber();

            // Handle optional attachment upload
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('goods_receipts', 'public');
            }

            // Create Master Goods Receipt Header
            $goodsReceipt = GoodsReceipt::create([
                'receipt_no'             => $receiptNo,
                'purchase_order_id'      => $purchaseOrder->id,
                'supplier_id'            => $purchaseOrder->supplier_id,
                'receipt_date'           => $request->receipt_date,
                'supplier_delivery_note' => $request->supplier_delivery_note,
                'vehicle_no'             => $request->vehicle_no,
                'received_by'            => $user->id,
                'inspection_status'      => 'Passed',
                'receipt_status'         => 'Received',
                'remarks'                => $request->remarks,
                'attachment_path'        => $attachmentPath,
                'company_id'             => $purchaseOrder->company_id,
                'branch_id'              => $purchaseOrder->branch_id,
                'User_id'                => $user->id,
                'Status'                 => 'Active',
                'AuditingStatus'         => 'Pending',
                'ReportStatus'           => 'Pending',
                'created_by'             => $user->id,
                'updated_by'             => $user->id,
            ]);

            $totalAcceptedCount = 0;

            foreach ($request->items as $itemData) {
                $poItem = PurchaseOrderItem::findOrFail($itemData['po_item_id']);

                $receivedQty = (float) $itemData['received_qty'];
                $acceptedQty = (float) $itemData['accepted_qty'];
                $rejectedQty = (float) ($itemData['rejected_qty'] ?? 0);
                $updatedUnitPrice = (float) $itemData['unit_price'];

                if ($receivedQty <= 0) {
                    continue; // Skip items where zero quantity was received
                }

                // Ensure user does not receive more than pending quantity
                if ($acceptedQty > $poItem->pending_quantity) {
                    DB::rollBack();
                    Alert::warning(
                        'Quantity Exceeded',
                        'Accepted quantity (' . $acceptedQty . ') exceeds pending quantity (' . $poItem->pending_quantity . ') for item #' . $poItem->id
                    );
                    return back()->withInput();
                }

                // Create Goods Receipt Item Log
                GoodsReceiptItem::create([
                    'goods_receipt_id'       => $goodsReceipt->id,
                    'purchase_order_item_id' => $poItem->id,
                    'product_id'             => $poItem->product_id,
                    'unit_of_measure_id'     => $poItem->unit_of_measure_id,
                    'ordered_quantity'      => $poItem->ordered_quantity,
                    'received_quantity'     => $receivedQty,
                    'accepted_quantity'     => $acceptedQty,
                    'rejected_quantity'     => $rejectedQty,
                    'rejection_reason'       => $itemData['rejection_reason'] ?? null,
                    'item_status'            => $acceptedQty > 0 ? 'Received' : 'Rejected',
                    'remarks'                => $itemData['remarks'] ?? null,
                ]);

                // Update PO Item line balances
                $newReceivedQty = $poItem->received_quantity + $acceptedQty;
                $newPendingQty  = max(0, $poItem->ordered_quantity - $newReceivedQty);
                $itemStatus     = $newPendingQty == 0 ? 'Completed' : 'Partially Received';

                $poItem->update([
                    'unit_price'        => $updatedUnitPrice, // Enable editing price purchased
                    'received_quantity' => $newReceivedQty,
                    'pending_quantity'  => $newPendingQty,
                    'item_status'       => $itemStatus,
                ]);

                $totalAcceptedCount += $acceptedQty;
            }

            if ($totalAcceptedCount <= 0) {
                DB::rollBack();
                Alert::warning('Validation Error', 'Please enter a valid accepted quantity for at least one item.');
                return back()->withInput();
            }

            // Check if Purchase Order is fully received or partially received
            $hasRemainingPending = $purchaseOrder->items()->where('pending_quantity', '>', 0)->exists();

            $newPoStatus = $hasRemainingPending ? 'Partially Received' : 'Completed';

            $purchaseOrder->update([
                'order_status' => $newPoStatus,
                'updated_by'   => $user->id,
            ]);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Goods Receipt Registered Successfully (' . $receiptNo . '). Purchase Order status updated to: ' . $newPoStatus
            );

            return redirect()->route('viewgoodsReceipt', encrypt($goodsReceipt->id));

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
     * View profile/details of a specific Goods Receipt.
     */
    public function viewgoodsReceipt($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);

            $data = GoodsReceipt::with([
                'purchaseOrder.branch',
                'supplier',
                'receivedBy',
                'branch',
                'company',
                'creator',
                'items.product',
                'items.unitOfMeasure',
                'items.purchaseOrderItem'
            ])->findOrFail($id);

            return view('in.suppliers.goodsreceipts.viewgoodsReceipt', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Soft delete a Goods Receipt.
     */
    public function destroygoodsReceipt($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $receipt = GoodsReceipt::findOrFail($id);

            $receipt->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $receipt->delete();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Goods Receipt Soft Deleted Successfully'
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
     * Display a listing of soft-deleted Goods Receipts.
     */
    public function deletedgoodsReceipt()
    {
        try {
            $data = GoodsReceipt::onlyTrashed()
                ->with(['purchaseOrder', 'supplier', 'deleter'])
                ->latest('deleted_at')
                ->get();

            return view('in.suppliers.goodsreceipts.deletedgoodsReceipt', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical for support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted Goods Receipt.
     */
    public function restoregoodsReceipt($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $receipt = GoodsReceipt::withTrashed()->findOrFail($id);

            $receipt->restore();

            $receipt->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Goods Receipt Restored Successfully'
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
     * Generate unique sequence GRN number (GRN-YYYY-00001).
     */
    private function generateGRNNumber(): string
    {
        $year = date('Y');

        $lastGRN = GoodsReceipt::where('receipt_no', 'like', 'IFLSG/GRN/' . $year . '-%')
            ->orderByDesc('id')
            ->first();

        if (!$lastGRN) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastGRN->receipt_no, -5);
            $number = $lastNumber + 1;
        }

        return 'IFLSG/GRN/' . $year . '/' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}