<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SalesCompanyBill;
use App\Models\SalesCompanyBillItem;
use App\Models\SalesCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    /**
     * Display listing of active customers with balance metrics.
     */
    public function customers(Request $request)
    {
        try {
            $query = SalesCustomer::where('Status', 'Active')
                ->withCount('sales')
                ->withSum(['sales' => function ($q) {$q->where('Status', 'Completed');
                }], 'total_amount')
                ->withSum(['sales' => function ($q) {$q->where('Status', 'Completed');
                }], 'paid_amount')
                ->withSum(['sales' => function ($q) {$q->where('Status', 'Completed');
                }], 'balance_amount');

            if ($request->filled('search')) {
                $search =$request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "\%{$search}%")
                      ->orWhere('customer_code', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            $data =$query->latest('id')->paginate(20)->withQueryString();

            return view('in.sales.customers.customers', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * View Customer Profile with monthly sales and billed vs unbilled status.
     */
    public function viewsaleCustomer(Request $request,$encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $customer = SalesCustomer::with(['branch', 'company'])->findOrFail($id);

            $selectedMonth =$request->input('month', date('Y-m'));

            $salesQuery = Sale::where('customer_id',$customer->id)
                ->where('Status', 'Completed');

            if ($selectedMonth) {
                $date = Carbon::parse($selectedMonth . '-01');
                $salesQuery->whereYear('sale_date',$date->year)
                           ->whereMonth('sale_date', $date->month);
            }

            $sales =$salesQuery->latest('sale_date')->get();

            // Calculate Period Financial Summaries
            $totalSales =$sales->sum('total_amount');
            $totalPaid =$sales->sum('paid_amount');
            $totalPending =$sales->sum('balance_amount');

            // Invoices generated for this customer
            $invoices = SalesCompanyBill::where('customer_id',$customer->id)
                ->where('Status', 'Active')
                ->latest('bill_date')
                ->get();

            return view('in.sales.customers.viewCustomer', compact(
                'customer',
                'sales',
                'invoices',
                'selectedMonth',
                'totalSales',
                'totalPaid',
                'totalPending'
            ));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Form to create a new invoice for unbilled sales.
     */
    public function createCustomerInvoice(Request $request,$encryptedCustomerId)
    {
        try {
            $customerId = Crypt::decrypt($encryptedCustomerId);
            $customer = SalesCustomer::findOrFail($customerId);

            $selectedMonth =$request->input('month', date('Y-m'));
            $date = Carbon::parse($selectedMonth . '-01');

            // Fetch COMPLETED & UNBILLED sales only (is_invoiced = false)
            $unbilledSales = Sale::where('customer_id',$customer->id)
                ->where('Status', 'Completed')
                ->where('is_invoiced', false)
                ->whereYear('sale_date', $date->year)
                ->whereMonth('sale_date', $date->month)
                ->get();

            // Check if there is already an existing active invoice for this customer in this month
            $existingInvoice = SalesCompanyBill::where('customer_id',$customer->id)
                ->where('Status', 'Active')
                ->whereYear('bill_date', $date->year)
                ->whereMonth('bill_date', $date->month)
                ->first();

            return view('in.sales.customers.createCustomerInvoice', compact(
                'customer',
                'unbilledSales',
                'selectedMonth',
                'existingInvoice'
            ));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Store new Invoice and mark associated sales as `is_invoiced = true`.
     */
    public function storeCustomerInvoice(Request $request,$encryptedCustomerId)
    {
        $customerId = Crypt::decrypt($encryptedCustomerId);

        $request->validate([
            'bill_date'  => 'required|date',
            'due_date'   => 'required|date|after_or_equal:bill_date',
            'sale_ids'   => 'required|array|min:1',
            'sale_ids.*' => 'exists:sales,id',
            'notes'      => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $customer = SalesCustomer::findOrFail($customerId);

            // Fetch sales ensuring none of them are already invoiced
            $sales = Sale::whereIn('id',$request->sale_ids)
                ->where('customer_id', $customer->id)
                ->where('is_invoiced', false)
                ->get();

            if ($sales->isEmpty()) {
                Alert::warning('Warning', 'All selected sales have already been invoiced or are invalid.');
                return back();
            }

            $subtotal =$sales->sum('total_amount');
            $totalPaid =$sales->sum('paid_amount');
            $balance =$sales->sum('balance_amount');

            $billNo = 'INV-CUST-' . date('Y') . '-' . mt_rand(10000, 99999);

            $invoice = SalesCompanyBill::create([
                'ReferenceNo'    => 'REF-BILL-' . time() . '-' . rand(100, 999),
                'bill_no'        => $billNo,
                'customer_id'    => $customer->id,
                'bill_date'      => $request->bill_date,
                'due_date'       => $request->due_date,
                'subtotal'       => $subtotal,
                'adjustments'    => 0.00,
                'total_amount'   => $subtotal,
                'paid_amount'    => $totalPaid,
                'balance_amount' => $balance,
                'approved_by'    => $user->id,
                'approved_at'    => now(),
                'notes'          => $request->notes,
                'company_id'     => $customer->company_id,
                'branch_id'      => $customer->branch_id,
                'User_id'        => $user->id,
                'Status'         => 'Active',
                'created_by'     => $user->id,
                'updated_by'     => $user->id,
            ]);

            foreach ($sales as$sale) {
                SalesCompanyBillItem::create([
                    'ReferenceNo'     => 'REF-BITM-' . time() . '-' . rand(1000, 9999),
                    'company_bill_id' => $invoice->id,
                    'sale_id'         => $sale->id,
                    'description'     => 'Sale No: ' . $sale->sale_no . ' Date: ' . Carbon::parse($sale->sale_date)->format('d/m/Y'),
                    'quantity'        => 1.000,
                    'unit_price'      => $sale->total_amount,
                    'amount'          => $sale->total_amount,
                    'company_id'      => $customer->company_id,
                    'branch_id'       => $customer->branch_id,
                    'User_id'         => $user->id,
                    'Status'          => 'Active',
                    'created_by'      => $user->id,
                    'updated_by'      => $user->id,
                ]);

                // UPDATE SALE IS_INVOICED TO TRUE
                $sale->update([
                    'is_invoiced' => true,
                    'updated_by'  => $user->id,
                ]);
            }

            DB::commit();

            Alert::success('Success ' . $user->name, 'Invoice generated successfully (' .$billNo . ')');
            return redirect()->route('viewCustomerInvoice', encrypt($invoice->id));

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back()->withInput();
        }
    }

    /**
     * REVISE EXISTING INVOICE: Add additional unbilled sales to an already created invoice.
     */
    public function addSalesToInvoice(Request $request,$encryptedInvoiceId)
    {
        $invoiceId = Crypt::decrypt($encryptedInvoiceId);

        $request->validate([
            'sale_ids'   => 'required|array|min:1',
            'sale_ids.*' => 'exists:sales,id',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $invoice = SalesCompanyBill::findOrFail($invoiceId);

            $sales = Sale::whereIn('id',$request->sale_ids)
                ->where('customer_id', $invoice->customer_id)
                ->where('is_invoiced', false)
                ->get();

            if ($sales->isEmpty()) {
                Alert::warning('Notice', 'No valid unbilled sales selected.');
                return back();
            }

            $addedTotal = 0;
            $addedPaid = 0;
            $addedBalance = 0;

            foreach ($sales as$sale) {
                SalesCompanyBillItem::create([
                    'ReferenceNo'     => 'REF-BITM-' . time() . '-' . rand(1000, 9999),
                    'company_bill_id' => $invoice->id,
                    'sale_id'         => $sale->id,
                    'description'     => 'Sale No: ' . $sale->sale_no . ' Date: ' . Carbon::parse($sale->sale_date)->format('d/m/Y'),
                    'quantity'        => 1.000,
                    'unit_price'      => $sale->total_amount,
                    'amount'          => $sale->total_amount,
                    'company_id'      => $invoice->company_id,
                    'branch_id'       => $invoice->branch_id,
                    'User_id'         => $user->id,
                    'Status'          => 'Active',
                    'created_by'      => $user->id,
                    'updated_by'      => $user->id,
                ]);

                // Mark sale as invoiced
                $sale->update([
                    'is_invoiced' => true,
                    'updated_by'  => $user->id,
                ]);

                $addedTotal +=$sale->total_amount;
                $addedPaid +=$sale->paid_amount;
                $addedBalance +=$sale->balance_amount;
            }

            // Recalculate and update Invoice totals
            $invoice->update([
                'subtotal'       => $invoice->subtotal +$addedTotal,
                'total_amount'   => $invoice->total_amount +$addedTotal,
                'paid_amount'    => $invoice->paid_amount +$addedPaid,
                'balance_amount' => $invoice->balance_amount +$addedBalance,
                'updated_by'     => $user->id,
            ]);

            DB::commit();

            Alert::success('Success ' . $user->name, 'Additional sales added to Invoice ' .$invoice->bill_no . ' successfully.');
            return back();

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * View Invoice details along with unbilled sales available for adding.
     */
    public function viewCustomerInvoice($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $invoice = SalesCompanyBill::with(['customer', 'items.sale', 'company', 'branch', 'approver'])->findOrFail($id);

            // Get any remaining unbilled sales for this customer to allow appending to this bill
            $unbilledSales = Sale::where('customer_id',$invoice->customer_id)
                ->where('Status', 'Completed')
                ->where('is_invoiced', false)
                ->latest('sale_date')
                ->get();

            return view('in.sales.customers.viewCustomerInvoice', compact('invoice', 'unbilledSales'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Record payment on Invoice and update underlying sales balances.
     */
    public function storeInvoicePayment(Request $request,$encryptedInvoiceId)
    {
        $invoiceId = Crypt::decrypt($encryptedInvoiceId);

        $request->validate([
            'payment_date'          => 'required|date',
            'amount'                => 'required|numeric|min:0.01',
            'reference_transaction' => 'nullable|string|max:150',
            'notes'                 => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $invoice = SalesCompanyBill::with(['items.sale'])->findOrFail($invoiceId);
            $payAmount = (float)$request->amount;

            if ($payAmount >$invoice->balance_amount) {
                Alert::warning('Overpayment Warning', 'Amount exceeds the outstanding invoice balance.');
                return back();
            }

            $remainingToDistribute =$payAmount;

            foreach ($invoice->items as$item) {
                if ($remainingToDistribute <= 0) break;

                $sale =$item->sale;
                if ($sale && $sale->balance_amount > 0) {$applyAmount = min($remainingToDistribute,$sale->balance_amount);

                    SalePayment::create([
                        'ReferenceNo'           => 'PAY-INV-' . time() . '-' . rand(1000, 9999),
                        'payment_no'            => 'SPAY-' . date('Y') . '-' . mt_rand(10000, 99999),
                        'sale_id'               => $sale->id,
                        'payment_date'          => $request->payment_date,
                        'amount'                => $applyAmount,
                        'reference_transaction' => $request->reference_transaction,
                        'received_by'           => $user->id,
                        'notes'                 => 'Payment via Invoice ' . $invoice->bill_no . '. ' .$request->notes,
                        'company_id'            => $sale->company_id,
                        'branch_id'             => $sale->branch_id,
                        'User_id'               => $user->id,
                        'Status'                => 'Active',
                        'created_by'            => $user->id,
                        'updated_by'            => $user->id,
                    ]);

                    $newPaid = $sale->paid_amount +$applyAmount;
                    $newBal = max(0,$sale->total_amount - $newPaid);$sale->update([
                        'paid_amount'    => $newPaid,
                        'balance_amount' => $newBal,
                        'payment_status' => $newBal == 0 ? 'PAID' : 'PARTIAL',
                    ]);

                    $remainingToDistribute -=$applyAmount;
                }
            }

            $invPaid = $invoice->paid_amount +$payAmount;
            $invBal = max(0, $invoice->total_amount -$invPaid);

            $invoice->update([
                'paid_amount'    => $invPaid,
                'balance_amount' => $invBal,
                'updated_by'     => $user->id,
            ]);

            DB::commit();

            Alert::success('Success ' . $user->name, 'Payment of TZS ' . number_format($payAmount, 2) . ' recorded successfully.');
            return back();

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Soft delete an invoice and revert all associated sales `is_invoiced = false`.
     */
    public function destroyCustomerInvoice($encryptedInvoiceId)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($encryptedInvoiceId);
            $invoice = SalesCompanyBill::with('items')->findOrFail($id);

            // Revert all billed sales back to unbilled state
            foreach ($invoice->items as$item) {
                if ($item->sale_id) {
                    Sale::where('id', $item->sale_id)->update([
                        'is_invoiced' => false,
                        'updated_by'  => Auth::id(),
                    ]);
                }
            }

            $invoice->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $invoice->delete();

            DB::commit();

            Alert::success('Success ' . Auth::user()->name, 'Invoice deleted successfully. All sales are now available for re-invoicing.');
            return redirect()->route('viewCustomer', encrypt($invoice->customer_id));

        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Printable invoice layout.
     */
public function printCustomerInvoice($encryptedId)
{
    try {
        $id = Crypt::decrypt($encryptedId);
        
        $invoice = SalesCompanyBill::with([
            'customer',
            'company',
            'branch',
            'items.sale.items.product'
        ])->findOrFail($id);

        return view('in.sales.customers.printCustomerInvoice', compact('invoice'));

    } catch (\Throwable $th) {
        Alert::error('Error', 'Unable to generate print invoice view.');
        return back();
    }
}
}