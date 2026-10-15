<?php

namespace App\Http\Controllers;


use App\Models\Branch;
use App\Models\Company;
use App\Models\SalesType;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use App\Models\SalesBillingPeriod;
use App\Models\SalesCustomer;
use App\Models\UnitOfMeasure;
use App\Models\BillingCompany;
use App\Models\StaffMealShift;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
class SaleController extends Controller
{

    /**
     * Display a listing of active sales types.
     */
    public function salesType()
    {
        try {
            $data = SalesType::where('Status', 'Active')
                ->with(['company', 'branch', 'user', 'creator'])
                ->orderBy('display_order', 'asc')
                ->latest('id')
                ->get();

            $companies = Company::where('Status', 'Active')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.sales.salestypes.salesType', compact('data', 'companies', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly created sales type in storage.
     */
    public function storesalesType(Request $request)
    {
        $request->validate([
            'branch_id'             => 'required|integer|exists:branchies,id',
            'type_code'             => 'required|string|max:50|unique:sales_types,type_code',
            'type_name'             => 'required|string|max:150',
            'description'           => 'nullable|string',
            'default_payment_terms' => 'required|in:IMMEDIATE,CREDIT,COMPANY_ACCOUNT',
            'display_order'         => 'nullable|integer|min:0',
            'requires_customer'     => 'nullable|boolean',
            'requires_employee'     => 'nullable|boolean',
            'allows_discount'       => 'nullable|boolean',
        ]);

        try {
            $branch = Branch::findOrFail($request->branch_id);

            // Generate Branch Abbreviation for Reference No
            $branchWords  = explode(' ', strtoupper($branch->branch_name));
            $branchPrefix = '';
            foreach ($branchWords as $word) {
                $branchPrefix .= substr($word, 0, 1);
            }
            $branchPrefix = substr($branchPrefix, 0, 3);

            // Generate Unique Reference Number
            $prefix = 'IFLSG/STP/' . $branchPrefix . '/' . date('Y') . '/';

            $lastSalesType = SalesType::where('ReferenceNo', 'LIKE', $prefix . '%')
                ->orderByDesc('id')
                ->first();

            $lastNumber = 0;

            if ($lastSalesType) {
                $lastNumber = (int) substr(
                    $lastSalesType->ReferenceNo,
                    strlen($prefix)
                );
            }

            $generatedRefNo = $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            SalesType::create([
                'ReferenceNo'           => $generatedRefNo,
                'type_code'             => strtoupper($request->type_code),
                'type_name'             => strtoupper($request->type_name),
                'description'           => $request->description,
                'requires_customer'     => $request->has('requires_customer') ? (bool) $request->requires_customer : false,
                'requires_employee'     => $request->has('requires_employee') ? (bool) $request->requires_employee : false,
                'default_payment_terms' => $request->default_payment_terms ?? 'IMMEDIATE',
                'allows_discount'       => $request->has('allows_discount') ? (bool) $request->allows_discount : true,
                'display_order'         => $request->display_order ?? 0,
                'company_id'            => $branch->company_id,
                'branch_id'             => $branch->id,
                'User_id'               => Auth::id(),
                'Status'                => 'Active',
                'AuditingStatus'        => 'Pending',
                'ReportStatus'          => 'Pending',
                'created_by'            => Auth::id(),
                'updated_by'            => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Sales Type Registered Successfully.'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back()->withInput();
        }
    }

    /**
     * Display profile/details of a specific sales type.
     */
    public function viewsalesType($id)
    {
        // try {
            $id = Crypt::decrypt($id);

            $data = SalesType::with([
                'company',
                'branch',
                'user',
                'creator',
                'updater',
                'sales'
            ])->findOrFail($id);

            return view('in.sales.salestypes.viewsalesType', compact('data'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical Support. Tel: +255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Show form for editing the specified sales type.
     */
    public function editsalesType($id)
    {
        try {
            $id   = Crypt::decrypt($id);
            $data = SalesType::findOrFail($id);

            $companies = Company::where('Status', 'Active')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.sales.salestypes.editsalesType', compact('data', 'companies', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified sales type in storage.
     */
    public function updatesalesType(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'branch_id'             => 'required|integer|exists:branchies,id',
            'type_code'             => 'required|string|max:50|unique:sales_types,type_code,' . $id,
            'type_name'             => 'required|string|max:150',
            'description'           => 'nullable|string',
            'default_payment_terms' => 'required|in:IMMEDIATE,CREDIT,COMPANY_ACCOUNT',
            'display_order'         => 'nullable|integer|min:0',
            'requires_customer'     => 'nullable|boolean',
            'requires_employee'     => 'nullable|boolean',
            'allows_discount'       => 'nullable|boolean',
        ]);

        try {
            $salesType = SalesType::findOrFail($id);
            $branch    = Branch::findOrFail($request->branch_id);

            $salesType->update([
                'type_code'             => strtoupper($request->type_code),
                'type_name'             => strtoupper($request->type_name),
                'description'           => $request->description,
                'requires_customer'     => $request->has('requires_customer') ? (bool) $request->requires_customer : false,
                'requires_employee'     => $request->has('requires_employee') ? (bool) $request->requires_employee : false,
                'default_payment_terms' => $request->default_payment_terms ?? $salesType->default_payment_terms,
                'allows_discount'       => $request->has('allows_discount') ? (bool) $request->allows_discount : false,
                'display_order'         => $request->display_order ?? $salesType->display_order,
                'company_id'            => $branch->company_id,
                'branch_id'             => $branch->id,
                'updated_by'            => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Sales Type Updated Successfully.'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back()->withInput();
        }
    }

    /**
     * Soft delete the specified sales type.
     */
    public function destroysalesType($id)
    {
        try {
            $id        = Crypt::decrypt($id);
            $salesType = SalesType::findOrFail($id);

            $salesType->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $salesType->delete();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Sales Type Removed Successfully.'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted sales types.
     */
    public function deletedsalesType()
    {
        try {
            $data = SalesType::onlyTrashed()
                ->with(['company', 'branch', 'deleter'])
                ->latest('deleted_at')
                ->get();

            return view('in.sales.salestypes.deletedsalesType', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted sales type back to Active status.
     */
    public function restoresalesType($id)
    {
        try {
            $id        = Crypt::decrypt($id);
            $salesType = SalesType::withTrashed()->findOrFail($id);

            $salesType->restore();

            $salesType->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success(
                'Success ' . Auth::user()->name,
                'Sales Type Restored Successfully.'
            );

            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }



    /**
     * Display a listing of active sales customers.
     */
    public function salesCustomer(Request $request)
    {
        try {
            $query = SalesCustomer::where('Status', 'Active')
                ->with(['billingCompany', 'company', 'branch', 'user', 'creator']);

            if ($request->filled('customer_type')) {
                $query->where('customer_type', $request->customer_type);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_code', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $companies = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();
            $billingCompanies = BillingCompany::where('Status', 'Active')->get();
            $data      = $query->latest('id')->paginate(20)->withQueryString();

            return view('in.sales.customers.salesCustomer', compact('data', 'companies', 'branchies', 'billingCompanies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Store a newly created sales customer in storage.
     */
    public function storesalesCustomer(Request $request)
    {
        $request->validate([
            'branch_id'          => 'required|integer|exists:branchies,id',
            'customer_name'      => 'required|string|max:200',
            'customer_type'      => 'required|in:COMPANY,INDIVIDUAL,WALK_IN',
            'billing_company_id' => 'nullable|required_if:customer_type,COMPANY|integer|exists:companies,id',
            'customer_code'      => 'nullable|string|max:50|unique:sales_customers,customer_code',
            'contact_person'     => 'nullable|string|max:150',
            'phone'              => 'nullable|string|max:30',
            'email'              => 'nullable|email|max:150',
            'address'            => 'nullable|string',
            'credit_limit'       => 'nullable|numeric|min:0',
            'payment_terms_days' => 'nullable|integer|min:0',
        ]);

        try {
            $user   = Auth::user();
            $branch = Branch::findOrFail($request->branch_id);

            // Generate Branch Code Prefix
            $branchWords  = explode(' ', strtoupper($branch->branch_name));
            $branchPrefix = '';
            foreach ($branchWords as $word) {
                $branchPrefix .= substr($word, 0, 1);
            }
            $branchPrefix = substr($branchPrefix, 0, 3);

            // Generate Sequential Customer Code if not supplied
            $customerCode = $request->customer_code 
                ? strtoupper($request->customer_code) 
                : $this->generateCustomerCode($request->customer_type);

            // Generate Incremental Reference Number
            $typePrefix = $request->customer_type === 'COMPANY'
                ? 'CMP'
                : ($request->customer_type === 'WALK_IN' ? 'WLK' : 'IND');

            $prefix = 'IFLSG/' . $typePrefix . '/' . date('Y') . '/';

            $lastCustomer = SalesCustomer::where('ReferenceNo', 'LIKE', $prefix . '%')
                ->orderByDesc('id')
                ->first();

            $lastNumber = 0;

            if ($lastCustomer) {
                $lastNumber = (int) substr(
                    $lastCustomer->ReferenceNo,
                    strlen($prefix)
                );
            }

            $generatedRefNo = $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            SalesCustomer::create([
                'ReferenceNo'        => $generatedRefNo,
                'customer_code'      => $customerCode,
                'customer_name'      => strtoupper($request->customer_name),
                'customer_type'      => $request->customer_type,
                'billing_company_id' => $request->customer_type === 'COMPANY' ? $request->billing_company_id : null,
                'contact_person'     => $request->contact_person ? strtoupper($request->contact_person) : null,
                'phone'              => $request->phone,
                'email'              => $request->email,
                'address'            => $request->address,
                'credit_limit'       => $request->credit_limit ?? 0.00,
                'payment_terms_days' => $request->payment_terms_days ?? 0,
                'company_id'         => $branch->company_id,
                'branch_id'          => $branch->id,
                'User_id'            => $user->id,
                'Status'             => 'Active',
                'AuditingStatus'     => 'Pending',
                'ReportStatus'       => 'Pending',
                'created_by'         => $user->id,
                'updated_by'         => $user->id,
            ]);

            Alert::success('Success ' . $user->name, 'Sales Customer Registered Successfully.');
            return back();

        } catch (\Throwable $th) {
            \Log::error('Sales Customer Error: ' . $th->getMessage());

            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back()->withInput();
        }
    }
    /**
     * Display details of a specific sales customer.
     */
    public function viewsalesCustomer($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);

            $data = SalesCustomer::with([
                'billingCompany',
                'company',
                'branch',
                'user',
                'creator',
                'updater',
                'sales.salesType',
                'companyBills'
            ])->findOrFail($id);

            return view('in.sales.customers.viewsalesCustomer', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Show form for editing the specified sales customer.
     */
    public function editsalesCustomer($encryptedId)
    {
        try {
            $id   = Crypt::decrypt($encryptedId);
            $data = SalesCustomer::findOrFail($id);

            $companies = Company::where('Status', 'Active')->orderBy('company_name')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.sales.customers.editsalesCustomer', compact('data', 'companies', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified sales customer in storage.
     */
    public function updatesalesCustomer(Request $request, $encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);

        $request->validate([
            'branch_id'          => 'required|integer|exists:branchies,id',
            'customer_name'      => 'required|string|max:200',
            'customer_type'      => 'required|in:COMPANY,INDIVIDUAL,WALK_IN',
            'billing_company_id' => 'nullable|required_if:customer_type,COMPANY|integer|exists:companies,id',
            'customer_code'      => 'required|string|max:50|unique:sales_customers,customer_code,' . $id,
            'contact_person'     => 'nullable|string|max:150',
            'phone'              => 'nullable|string|max:30',
            'email'              => 'nullable|email|max:150',
            'address'            => 'nullable|string',
            'credit_limit'       => 'nullable|numeric|min:0',
            'payment_terms_days' => 'nullable|integer|min:0',
        ]);

        try {
            $customer = SalesCustomer::findOrFail($id);
            $branch   = Branch::findOrFail($request->branch_id);

            $customer->update([
                'customer_code'      => strtoupper($request->customer_code),
                'customer_name'      => strtoupper($request->customer_name),
                'customer_type'      => $request->customer_type,
                'billing_company_id' => $request->customer_type === 'COMPANY' ? $request->billing_company_id : null,
                'contact_person'     => $request->contact_person ? strtoupper($request->contact_person) : null,
                'phone'              => $request->phone,
                'email'              => $request->email,
                'address'            => $request->address,
                'credit_limit'       => $request->credit_limit ?? 0.00,
                'payment_terms_days' => $request->payment_terms_days ?? 0,
                'company_id'         => $branch->company_id,
                'branch_id'          => $branch->id,
                'updated_by'         => Auth::id(),
            ]);

            Alert::success('Success ' . Auth::user()->name, 'Customer Details Updated Successfully.');
            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back()->withInput();
        }
    }

    /**
     * Soft delete the specified sales customer.
     */
    public function destroysalesCustomer($encryptedId)
    {
        try {
            $id       = Crypt::decrypt($encryptedId);
            $customer = SalesCustomer::findOrFail($id);

            $customer->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $customer->delete();

            Alert::success('Success ' . Auth::user()->name, 'Sales Customer Removed Successfully.');
            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Display a listing of soft-deleted sales customers.
     */
    public function deletedsalesCustomer()
    {
        try {
            $data = SalesCustomer::onlyTrashed()
                ->with(['company', 'branch', 'deleter'])
                ->latest('deleted_at')
                ->get();

            return view('in.sales.customers.deletedsalesCustomer', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a soft-deleted sales customer.
     */
    public function restoresalesCustomer($encryptedId)
    {
        try {
            $id       = Crypt::decrypt($encryptedId);
            $customer = SalesCustomer::withTrashed()->findOrFail($id);

            $customer->restore();

            $customer->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success('Success ' . Auth::user()->name, 'Sales Customer Restored Successfully.');
            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Generate unique sequence Customer Code (e.g., CUST-00001).
     */
    private function generateCustomerCode(string $type): string
    {
        $prefix = 'IFLSG/CUST/';

        $lastCustomer = SalesCustomer::withTrashed()
            ->where('customer_code', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        if (!$lastCustomer) {
            $number = 1;
        } else {
            $lastNumber = (int) substr($lastCustomer->customer_code, -5);
            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }


    /**
     * Display a listing of sales transactions.
     */
    public function sales(Request $request)
    {
        try {
            $query = Sale::where('Status', '!=', 'Deleted')
                ->with(['salesType', 'customer', 'cashier', 'branch', 'company']);

            if ($request->filled('sales_type_id')) {
                $query->where('sales_type_id', $request->sales_type_id);
            }

            if ($request->filled('customer_id')) {
                $query->where('customer_id', $request->customer_id);
            }

            if ($request->filled('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            if ($request->filled('from_date')) {
                $query->whereDate('sale_date', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('sale_date', '<=', $request->to_date);
            }

            $salesTypes = SalesType::where('Status', 'Active')->orderBy('type_name')->get();
            $customers  = SalesCustomer::where('Status', 'Active')->orderBy('customer_name')->get();
            $data       = $query->latest('id')->paginate(20)->withQueryString();

            return view('in.sales.sales.sales', compact('data', 'salesTypes', 'customers'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Show form to create a new sale transaction.
     */
    public function createSale()
    {
        try {
            $salesTypes = SalesType::where('Status', 'Active')->orderBy('type_name')->get();
            $customers  = SalesCustomer::where('Status', 'Active')->orderBy('customer_name')->get();
            $products   = Product::where('Status', 'Active')->get();
            $uoms       = UnitOfMeasure::all();
            $branchies  = Branch::where('Status', 'Active')->get();

            // Fetch active billing period if present
            $activeBillingPeriod = SalesBillingPeriod::where('Status', 'Open')
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            return view('in.sales.sales.createSale', compact(
                'salesTypes',
                'customers',
                'products',
                'uoms',
                'branchies',
                'activeBillingPeriod'
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
     * Store a newly created sale in storage.
     */
    public function storeSale(Request $request)
    {
        $request->validate([
            'branch_id'               => 'required|integer|exists:branchies,id',
            'sales_type_id'           => 'nullable|integer|exists:sales_types,id',
            'customer_id'             => 'nullable|integer|exists:sales_customers,id',
            'sale_date'               => 'required|date',
            'payment_terms'           => 'required|in:IMMEDIATE,CREDIT,COMPANY_ACCOUNT',
            'currency_code'           => 'nullable|string|max:10',
            'notes'                   => 'nullable|string',
            'items'                   => 'required|array|min:1',
            'items.*.product_id'      => 'required|integer|exists:products,id',
            'items.*.quantity'        => 'required|numeric|min:0.001',
            'items.*.unit_price'      => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.unit_of_measure_id' => 'nullable|integer',
            'items.*.notes'           => 'nullable|string',
            'initial_payment_amount'  => 'nullable|numeric|min:0',
            'payment_method_id'       => 'nullable|integer',
            'reference_transaction'   => 'nullable|string|max:150',
        ]);

        DB::beginTransaction();
        // try {
            $user   = Auth::user();
            $branch = Branch::findOrFail($request->branch_id);

            // Fetch active open billing period if available
            $billingPeriod = SalesBillingPeriod::where('company_id', $branch->company_id)
                ->where('Status', 'Open')
                ->whereDate('start_date', '<=', $request->sale_date)
                ->whereDate('end_date', '>=', $request->sale_date)
                ->first();

            $saleNo = $this->generateSaleNumber();

            // 1. Create Sale Header
            $sale = Sale::create([
                'ReferenceNo'       => 'IFLSG/REF/' . str_replace('-', '', $saleNo),
                'sale_no'           => $saleNo,
                'sale_date'         => $request->sale_date,
                'sales_type_id'     => $request->sales_type_id,
                'customer_id'       => $request->customer_id,
                'billing_period_id' => $billingPeriod->id ?? null,
                'cashier_id'        => $user->id,
                'payment_terms'     => $request->payment_terms,
                'payment_status'    => 'UNPAID',
                'subtotal'          => 0,
                'discount_amount'   => 0,
                'tax_amount'        => 0,
                'total_amount'      => 0,
                'paid_amount'       => 0,
                'balance_amount'    => 0,
                'currency_code'     => $request->currency_code ?? 'TZS',
                'notes'             => $request->notes,
                'company_id'        => $branch->company_id,
                'branch_id'         => $branch->id,
                'User_id'           => $user->id,
                'Status'            => 'Completed',
                'AuditingStatus'    => 'Pending',
                'ReportStatus'      => 'Pending',
                'created_by'        => $user->id,
                'updated_by'        => $user->id,
            ]);

            $subtotalCalculated = 0;
            $discountCalculated = 0;

            // 2. Insert Sale Items
            foreach ($request->items as $itemData) {
                $qty      = (float) $itemData['quantity'];
                $price    = (float) $itemData['unit_price'];
                $discount = (float) ($itemData['discount_amount'] ?? 0);

                $lineTotal = ($qty * $price) - $discount;

                SaleItem::create([
                    'ReferenceNo'        => 'IFLSG/ITM/' . mt_rand(100000, 999999),
                    'sale_id'            => $sale->id,
                    'product_id'         => $itemData['product_id'],
                    'quantity'           => $qty,
                    'unit_of_measure_id' => $itemData['unit_of_measure_id'] ?? null,
                    'unit_price'         => $price,
                    'discount_amount'    => $discount,
                    'line_total'         => max(0, $lineTotal),
                    'notes'              => $itemData['notes'] ?? null,
                    'company_id'         => $branch->company_id,
                    'branch_id'          => $branch->id,
                    'User_id'            => $user->id,
                    'Status'             => 'Active',
                    'created_by'         => $user->id,
                    'updated_by'         => $user->id,
                ]);

                $subtotalCalculated += ($qty * $price);
                $discountCalculated += $discount;
            }

            $totalAmount = max(0, $subtotalCalculated - $discountCalculated);
            $initialPayment = (float) ($request->initial_payment_amount ?? 0);

            // 3. Handle Immediate/Initial Payment if provided
            $paidAmount = 0;
            if ($initialPayment > 0) {
                $paidAmount = min($totalAmount, $initialPayment);

                SalePayment::create([
                    'ReferenceNo'           => 'IFLSG/PAYREF/' . mt_rand(100000, 999999),
                    'payment_no'            => $this->generatePaymentNumber(),
                    'sale_id'               => $sale->id,
                    'payment_date'          => $request->sale_date,
                    'amount'                => $paidAmount,
                    'payment_method_id'     => $request->payment_method_id ?? null,
                    'reference_transaction' => $request->reference_transaction ?? null,
                    'received_by'           => $user->id,
                    'notes'                 => 'Initial payment on sale creation',
                    'company_id'            => $branch->company_id,
                    'branch_id'             => $branch->id,
                    'User_id'               => $user->id,
                    'Status'                => 'Active',
                    'created_by'            => $user->id,
                    'updated_by'            => $user->id,
                ]);
            }

            // 4. Update Header Totals & Payment Status
            $balanceAmount = $totalAmount - $paidAmount;
            $paymentStatus = 'UNPAID';

            if ($paidAmount >= $totalAmount && $totalAmount > 0) {
                $paymentStatus = 'PAID';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'PARTIAL';
            }

            $sale->update([
                'subtotal'        => $subtotalCalculated,
                'discount_amount' => $discountCalculated,
                'total_amount'    => $totalAmount,
                'paid_amount'     => $paidAmount,
                'balance_amount'  => max(0, $balanceAmount),
                'payment_status'  => $paymentStatus,
            ]);

            DB::commit();

            Alert::success('Success ' . $user->name, 'Sale Transaction Recorded Successfully (' . $saleNo . ')');
            return redirect()->route('viewSale', encrypt($sale->id));

        // } catch (\Throwable $th) {
        //     DB::rollBack();

        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical Support. Tel: +255657856790'
        //     );
        //     return back()->withInput();
        // }
    }

    /**
     * Display details of a specific sale.
     */
    public function viewSale($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);

            $data = Sale::with([
                'salesType',
                'customer',
                'cashier',
                'branch',
                'company',
                'billingPeriod',
                'creator',
                'items.product',
                'items.unitOfMeasure',
                'payments.receiver'
            ])->findOrFail($id);

            return view('in.sales.sales.viewSale', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Store additional payment against unpaid/partial sale balance.
     */
    public function storeSalePayment(Request $request, $encryptedSaleId)
    {
        $saleId = Crypt::decrypt($encryptedSaleId);

        $request->validate([
            'payment_date'          => 'required|date',
            'amount'                => 'required|numeric|min:0.01',
            'payment_method_id'     => 'nullable|integer',
            'reference_transaction' => 'nullable|string|max:150',
            'notes'                 => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $sale = Sale::findOrFail($saleId);

            if ($sale->balance_amount <= 0 || $sale->payment_status === 'PAID') {
                Alert::warning('Action Denied', 'This sale is already fully paid.');
                return back();
            }

            $payAmount   = min($sale->balance_amount, (float) $request->amount);
            $newPaid     = $sale->paid_amount + $payAmount;
            $newBalance  = max(0, $sale->total_amount - $newPaid);

            $paymentStatus = $newBalance <= 0 ? 'PAID' : 'PARTIAL';

            // Record Payment
            SalePayment::create([
                'ReferenceNo'           => 'IFLSG/PAYREF/' . mt_rand(100000, 999999),
                'payment_no'            => $this->generatePaymentNumber(),
                'sale_id'               => $sale->id,
                'payment_date'          => $request->payment_date,
                'amount'                => $payAmount,
                'payment_method_id'     => $request->payment_method_id,
                'reference_transaction' => $request->reference_transaction,
                'received_by'           => $user->id,
                'notes'                 => $request->notes,
                'company_id'            => $sale->company_id,
                'branch_id'             => $sale->branch_id,
                'User_id'               => $user->id,
                'Status'                => 'Active',
                'created_by'            => $user->id,
                'updated_by'            => $user->id,
            ]);

            // Update Sale Balance
            $sale->update([
                'paid_amount'    => $newPaid,
                'balance_amount' => $newBalance,
                'payment_status' => $paymentStatus,
                'updated_by'     => $user->id,
            ]);

            DB::commit();

            Alert::success('Success ' . $user->name, 'Payment of TZS ' . number_format($payAmount, 2) . ' Recorded Successfully.');
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
     * Soft delete a sale transaction.
     */
    public function destroySale($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $sale = Sale::findOrFail($id);

            if ($sale->paid_amount > 0 || $sale->payments()->count() > 0) {
                Alert::error('Action Restricted', 'Cannot delete a sale that has existing payments.');
                return back();
            }

            $sale->update([
                'deleted_by' => Auth::id(),
            ]);
            
            $sale->delete();

            Alert::success('Success', 'Sale record deleted successfully.');
            return back();

        } catch (\Throwable $th) {
            Alert::error('Error', 'Technical error occurred.');
            return back();
        }
    }

    /**
     * Display soft-deleted sales list.
     */
    public function deletedSales()
    {
        try {
            $data = Sale::onlyTrashed()
                ->with(['salesType', 'customer', 'deleter', 'branch'])
                ->latest('deleted_at')
                ->get();

            return view('in.sales.sales.deletedSales', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Restore soft-deleted sale transaction.
     */
    public function restoreSale($encryptedId)
    {
        try {
            $id   = Crypt::decrypt($encryptedId);
            $sale = Sale::withTrashed()->findOrFail($id);

            $sale->restore();

            $sale->update([
                'Status'     => 'Completed',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            Alert::success('Success ' . Auth::user()->name, 'Sale Record Restored Successfully.');
            return back();

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }


    /**
     * Generate unique sequential Sale Number: IFLSG/SAL/YYYY/00001
     */
    private function generateSaleNumber(): string
    {
        $year = date('Y');
        $prefix = 'IFLSG/SAL/' . $year . '/';

        $lastSale = Sale::withTrashed()
            ->where('sale_no', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $number = $lastSale
            ? ((int) substr($lastSale->sale_no, -5)) + 1
            : 1;

        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique sequential Sale Payment Number: IFLSG/PAY/YYYY/00001
     */
    private function generatePaymentNumber(): string
    {
        $year = date('Y');
        $prefix = 'IFLSG/PAY/' . $year . '/';

        $lastPayment = SalePayment::withTrashed()
            ->where('payment_no', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $number = $lastPayment
            ? ((int) substr($lastPayment->payment_no, -5)) + 1
            : 1;

        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Display a listing of all recorded sale payments.
     */
    public function salesPayments(Request $request)
    {
        try {
            $query = SalePayment::where('Status', 'Active')
                ->with(['sale', 'sale.customer', 'receiver', 'branch']);

            if ($request->filled('customer_id')) {
                $query->whereHas('sale', function ($q) use ($request) {
                    $q->where('customer_id', $request->customer_id);
                });
            }

            if ($request->filled('from_date')) {
                $query->whereDate('payment_date', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('payment_date', '<=', $request->to_date);
            }

            $customers = SalesCustomer::where('Status', 'Active')->orderBy('customer_name')->get();
            $data = $query->latest('id')->paginate(20)->withQueryString();

            return view('in.sales.payments.salesPayments', compact('data', 'customers'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Display pending/partially paid sales for easy tracking and inline payment processing.
     */
    public function pendingSalesPayments(Request $request)
    {
        try {
            $query = Sale::where('Status', 'Completed')
                ->where('balance_amount', '>', 0)
                ->with(['customer', 'salesType', 'branch', 'payments']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('sale_no', 'like', "%{$search}%")
                      ->orWhere('ReferenceNo', 'like', "%{$search}%")
                      ->orWhereHas('customer', function ($cq) use ($search) {
                          $cq->where('customer_name', 'like', "%{$search}%");
                      });
                });
            }

            if ($request->filled('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            $data = $query->latest('id')->paginate(20)->withQueryString();

            return view('in.sales.payments.pendingSalesPayments', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

 
    /**
     * View details of a single sale payment receipt.
     */
    public function viewSalePayment($encryptedId)
    {
        try {
            $id = Crypt::decrypt($encryptedId);
            $data = SalePayment::with(['sale', 'sale.customer', 'receiver', 'branch', 'creator'])->findOrFail($id);

            return view('in.sales.payments.viewSalePayment', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Soft delete a sale payment and revert sale balances.
     */
    public function destroySalePayment($encryptedId)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($encryptedId);
            $payment = SalePayment::findOrFail($id);

            if ($payment->sale) {
                $sale = $payment->sale;
                $revertedPaid = max(0, $sale->paid_amount - $payment->amount);
                $revertedBalance = $sale->total_amount - $revertedPaid;
                $revertedStatus = $revertedPaid > 0 ? 'PARTIAL' : 'UNPAID';

                $sale->update([
                    'paid_amount'    => $revertedPaid,
                    'balance_amount' => $revertedBalance,
                    'payment_status' => $revertedStatus,
                ]);
            }

            $payment->update([
                'Status'     => 'Deleted',
                'deleted_by' => Auth::id(),
            ]);

            $payment->delete();

            DB::commit();

            Alert::success('Success ' . Auth::user()->name, 'Payment Record Soft Deleted Successfully.');
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
     * Soft-deleted payments list.
     */
    public function deletedSalePayments()
    {
        try {
            $data = SalePayment::onlyTrashed()
                ->with(['sale', 'sale.customer', 'deleter'])
                ->latest('deleted_at')
                ->get();

            return view('in.sales.payments.deletedSalePayments', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support. Tel: +255657856790'
            );
            return back();
        }
    }

    /**
     * Restore soft-deleted payment record.
     */
    public function restoreSalePayment($encryptedId)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decrypt($encryptedId);
            $payment = SalePayment::withTrashed()->findOrFail($id);

            $payment->restore();

            $payment->update([
                'Status'     => 'Active',
                'deleted_by' => null,
                'updated_by' => Auth::id(),
            ]);

            if ($payment->sale) {
                $sale = $payment->sale;
                $restoredPaid = $sale->paid_amount + $payment->amount;
                $restoredBalance = max(0, $sale->total_amount - $restoredPaid);
                $restoredStatus = $restoredBalance == 0 ? 'PAID' : 'PARTIAL';

                $sale->update([
                    'paid_amount'    => $restoredPaid,
                    'balance_amount' => $restoredBalance,
                    'payment_status' => restoredStatus,
                ]);
            }

            DB::commit();

            Alert::success('Success ' . Auth::user()->name, 'Payment Record Restored Successfully.');
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

}