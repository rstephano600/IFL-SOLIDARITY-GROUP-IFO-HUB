<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseItem;
use App\Models\ExpenseCategory;
use App\Models\ExpensePayment;
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
use App\Models\Company;
use App\Models\Branch;
use App\Models\Product;

use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class ExpenseController extends Controller
{

    public function expensecategoryinformations()
    {
        // try {

            $data = ExpenseCategory::with([
                    'user',
                    'creator',
                    'updater'
                ])
                ->latest()
                ->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view(
                'in.expenses.categories.expensecategoryinformations',
                compact('data', 'branchies')
            );

        // } catch (\Throwable $th) {

        //     Alert::error(
        //         'Sorry! '.Auth()->user()->name,
        //         'Technical error exists, please contact Technical Support.'
        //     );

        //     return back();
        // }
    }

    public function storeexpensecategory(Request $request)
    {
        $request->validate([
                'branch_id' => 'required|integer|exists:branchies,id',
                'name' => 'required|string|max:255|unique:expense_categories,name',
                'description' => 'nullable|string'
            ]);

        // try {

            // Generate Expense Category Code
            $today = date('Ymd');

            $branch = Branch::findOrFail($request->branch_id);
            $companyId = $branch->company_id;

            $lastCategory = ExpenseCategory::whereDate('created_at', today())
                ->latest('id')
                ->first();

            $nextNumber = $lastCategory
                ? ((int) substr($lastCategory->expense_code, -4)) + 1
                : 1;

            $expenseCode = 'IFLSG/EXP/' . $today . '/' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            ExpenseCategory::create([

                'expense_code' => $expenseCode,
                'name' => $request->name,
                'description' => $request->description,

                'company_id' => $companyId,
                'branch_id'  => $branch->id,

                'User_id' => Auth::id(),
                'created_by' => Auth::id(),

                'Status' => 'Active',
                'AuditingStatus' => 'Pending',
                'ReportStatus' => 'Pending'

            ]);

            Alert::success(
                'Success',
                'Expense Category Registered Successfully.'
            );

            return back();

        // } catch (\Throwable $th) {

        //     Alert::error(
        //         'Error',
        //         $th->getMessage()
        //     );

        //     return back()->withInput();
        // }
    }


    public function viewexpensecategory($id)
    {
        try {
            // Eager load relationships along with the expenses relation count
            $data = ExpenseCategory::with([
                    'user',
                    'creator',
                    'updater',
                    'expenses'
                ])
                ->findOrFail(decrypt($id));

            return view(
                'in.expenses.categories.viewexpensecategory',
                compact('data')
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . auth()->user()->name,
                'Technical error exists, please contact Technical Support.'
            );

            return back();
        }
    }

    public function editexpensecategory($id)
    {
        // try {
            // Eager load relevant user meta mapping records for the edit tracking view
            $data = ExpenseCategory::with([
                    'user',
                    'creator',
                    'updater'
                ])
                ->findOrFail(decrypt($id));
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view(
                'in.expenses.categories.editexpensecategory',
                compact('data', 'branchies')
            );

        // } catch (\Throwable $th) {

        //     Alert::error(
        //         'Sorry! ' . auth()->user()->name,
        //         'Technical error exists, please contact Technical Support.'
        //     );

        //     return back();
        // }
    }
    public function updateexpensecategory(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255|unique:expense_categories,name,' . decrypt($id),
                'description' => 'nullable|string',
            ]);

            $data = ExpenseCategory::findOrFail(decrypt($id));
            $branch    = Branch::findOrFail($request->branch_id);

            $data->update([
                'name' => $request->name,
                'description' => $request->description,
                'company_id'            => $branch->company_id,
                'branch_id'             => $branch->id,
                'updated_by' => Auth::id(),

                'AuditingStatus' => 'Pending',
                'ReportStatus' => 'Show',
            ]);

            Alert::success(
                'Success!',
                'Expense Category Updated Successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! ' . Auth()->user()->name,
                'Technical error exists, please contact Technical Support. ' . $th->getMessage()
            );

            return back()->withInput();
        }
    }
    public function deleteexpensecategory($id)
    {
        try {

            $data = ExpenseCategory::findOrFail(decrypt($id));

            $data->update([

                'Status' => 'Deleted',
                'updated_by' => Auth::id(),

            ]);

            Alert::success(
                'Success',
                'Expense Category Deleted Successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Error',
                $th->getMessage()
            );

            return back();
        }
    }


    public function destroyExpense($id)
    {
        try {

            $data = Expense::findOrFail(decrypt($id));

            if ($data->AppStatus === 'Approved' || $data->PaymentStatus === 'Paid') {
                Alert::warning('Action Denied', 'Approved or Paid expenses cannot be edited.');
                return back();
            }

            $data->update([
                'Status' => 'Deleted',
                'updated_by' => Auth::id(),

            ]);

            Alert::success(
                'Success',
                'Expense Category Deleted Successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Error',
                $th->getMessage()
            );

            return back();
        }
    }

    public function activeexpensecategory($id)
    {
        try {

            ExpenseCategory::findOrFail(decrypt($id))
                ->update([

                    'Status' => 'Active',
                    'updated_by' => Auth::id()

                ]);

            Alert::success(
                'Success',
                'Expense Category Activated Successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Error',
                $th->getMessage()
            );

            return back();
        }
    }

    public function inactiveexpensecategory($id)
    {
        try {

            ExpenseCategory::findOrFail(decrypt($id))
                ->update([

                    'Status' => 'Inactive',
                    'updated_by' => Auth::id()

                ]);

            Alert::success(
                'Success',
                'Expense Category Deactivated Successfully.'
            );

            return back();

        } catch (\Throwable $th) {

            Alert::error(
                'Error',
                $th->getMessage()
            );

            return back();
        }
    }



    /**
     * Display a listing of active expenses.
     */
    public function expenses(Request $request)
    {
        try {
            $query = Expense::where('Status', 'Active')
                ->with(['category', 'company', 'branch', 'user', 'creator', 'approver', 'payer', 'items.product']);

            if ($request->filled('expense_category_id')) {
                $query->where('expense_category_id', $request->expense_category_id);
            }

            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->filled('AppStatus')) {
                $query->where('AppStatus', $request->AppStatus);
            }

            if ($request->filled('PaymentStatus')) {
                $query->where('PaymentStatus', $request->PaymentStatus);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('expense_number', 'like', "%{$search}%")
                      ->orWhere('expense_title', 'like', "%{$search}%")
                      ->orWhere('reference_number', 'like', "%{$search}%");
                });
            }

            $data = $query->latest('id')->paginate(15)->withQueryString();
            $categories = ExpenseCategory::where('Status', 'Active')->orderBy('name')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.expenses.expenses.expenses', compact('data', 'categories', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Show form for creating a new expense.
     */
    public function createExpense()
    {
        // try {
            $categories = ExpenseCategory::where('Status', 'Active')->orderBy('name')->get();
            $products = Product::where('Status', 'Active')->orderBy('product_name')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.expenses.expenses.createExpense', compact('categories', 'products', 'branchies'));

        // } catch (\Throwable $th) {
        //     Alert::error(
        //         'Sorry! ' . Auth::user()->name,
        //         'Technical error exists, please contact Technical Support Tel:+255657856790'
        //     );
        //     return back();
        // }
    }

    /**
     * Store a newly created expense and its item lines in storage.
     */
    public function storeExpense(Request $request)
    {
        $request->validate([
            'branch_id'           => 'required|integer|exists:branchies,id',
            'expense_title'       => 'required|string|max:255',
            'expense_category_id' => 'required|integer|exists:expense_categories,id',
            'expense_date'        => 'required|date',
            'currency'            => 'required|string|max:10',
            'description'         => 'nullable|string',
            'attachment'          => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5048',

            // Line items validation
            'items'               => 'required|array|min:1',
            'items.*.item_name'   => 'required|string|max:255',
            'items.*.product_id'  => 'nullable|integer|exists:products,id',
            'items.*.quantity'    => 'required|numeric|min:0.001',
            'items.*.unit_cost'   => 'required|numeric|min:0',
            'items.*.supplier_name' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        // try {
            $user = Auth::user();
            $branch = Branch::findOrFail($request->branch_id);
            $companyId = $branch->company_id;
            $today = date('Ymd');

            // 1. Generate Expense Number (EXP-YYYYMMDD-0001)
            $lastExpense = Expense::whereDate('created_at', today())
                ->orderByDesc('id')
                ->first();

            $nextNumber = $lastExpense
                ? ((int) substr($lastExpense->expense_number, -4)) + 1
                : 1;

            $expenseNumber = 'IFLSG/EXP/' . $today . '/' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // 2. Handle File Attachment Upload
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachmentName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $attachmentPath = $file->storeAs('expenses/attachments', $attachmentName, 'public');
            }

            // 3. Calculate Total Expense Amount from items
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += ((float) $item['quantity']) * ((float) $item['unit_cost']);
            }

            // 4. Create Parent Expense
            $expense = Expense::create([
                'expense_number'      => $expenseNumber,
                'expense_title'       => strtoupper($request->expense_title),
                'description'         => $request->description,
                'expense_date'        => $request->expense_date,
                'total_amount'        => $totalAmount,
                'currency'            => strtoupper($request->currency),
                'amount_paid'         => 0.00,
                'expense_category_id' => $request->expense_category_id,
                'attachment'          => $attachmentPath,
                'company_id'          => $companyId,
                'branch_id'           => $branch->id,
                'User_id'             => $user->id,
                'AppStatus'           => 'Pending',
                'PaymentStatus'       => 'Unpaid',
                'created_by'          => $user->id,
                'updated_by'          => $user->id,
                'Status'              => 'Active',
                'AuditingStatus'      => 'Pending',
                'ReportStatus'        => 'Pending',
            ]);

            // 5. Create Expense Line Items
            foreach ($request->items as $itemData) {
                $qty = (float) $itemData['quantity'];
                $unitCost = (float) $itemData['unit_cost'];
                $lineTotal = $qty * $unitCost;

                ExpenseItem::create([
                    'expense_id'    => $expense->id,
                    'product_id'    => $itemData['product_id'] ?? null,
                    'company_id'    => $companyId,
                    'branch_id'     => $branch->id,
                    'User_id'       => $user->id,
                    'item_name'     => $itemData['item_name'],
                    'quantity'      => $qty,
                    'unit_cost'     => $unitCost,
                    'total_cost'    => $lineTotal,
                    'supplier_name' => $itemData['supplier_name'] ?? null,
                ]);
            }

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Expense Requisition Submitted Successfully (' . $expenseNumber . ')'
            );

            return redirect()->route('viewExpense', encrypt($expense->id));

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
     * Display details of a specific expense.
     */
    public function viewExpense($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $data = Expense::with([
                'category',
                'company',
                'branch',
                'user',
                'creator',
                'editor',
                'approver',
                'payer',
                'items.product',
                'payments.payer',
                'payments.company',
                'payments.branch'
            ])->findOrFail($id);

            return view('in.expenses.expenses.viewExpense', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Show form for editing an unapproved expense.
     */
    public function editExpense($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data = Expense::with('items')->findOrFail($id);

            if ($data->AppStatus === 'Approved' || $data->PaymentStatus === 'Paid') {
                Alert::warning('Action Denied', 'Approved or Paid expenses cannot be edited.');
                return back();
            }

            $categories = ExpenseCategory::where('Status', 'Active')->orderBy('name')->get();
            $products = Product::where('Status', 'Active')->orderBy('product_name')->get();
            $branchies = Branch::where('Status', 'Active')->with('company')->get();

            return view('in.expenses.expenses.editExpense', compact('data', 'categories', 'products', 'branchies'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Update the specified expense in storage.
     */
    public function updateExpense(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'branch_id'           => 'required|integer|exists:branchies,id',
            'expense_title'       => 'required|string|max:255',
            'expense_category_id' => 'required|integer|exists:expense_categories,id',
            'expense_date'        => 'required|date',
            'currency'            => 'required|string|max:10',
            'description'         => 'nullable|string',
            'attachment'          => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5048',

            'items'               => 'required|array|min:1',
            'items.*.item_name'   => 'required|string|max:255',
            'items.*.product_id'  => 'nullable|integer|exists:products,id',
            'items.*.quantity'    => 'required|numeric|min:0.001',
            'items.*.unit_cost'   => 'required|numeric|min:0',
            'items.*.supplier_name' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $expense = Expense::findOrFail($id);

            if ($expense->AppStatus === 'Approved' || $expense->PaymentStatus === 'Paid') {
                Alert::warning('Warning', 'Approved or Paid expenses cannot be updated.');
                return back();
            }

            $branch = Branch::findOrFail($request->branch_id);
            $companyId = $branch->company_id;

            // File Attachment Update
            $attachmentPath = $expense->attachment;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachmentName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $attachmentPath = $file->storeAs('expenses/attachments', $attachmentName, 'public');
            }

            // Recalculate Total
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += ((float) $item['quantity']) * ((float) $item['unit_cost']);
            }

            $expense->update([
                'branch_id'           => $branch->id,
                'company_id'          => $companyId,
                'expense_title'       => strtoupper($request->expense_title),
                'description'         => $request->description,
                'expense_date'        => $request->expense_date,
                'total_amount'        => $totalAmount,
                'currency'            => strtoupper($request->currency),
                'expense_category_id' => $request->expense_category_id,
                'attachment'          => $attachmentPath,
                'updated_by'          => $user->id,
            ]);

            // Sync Line Items
            $expense->items()->delete();

            foreach ($request->items as $itemData) {
                $qty = (float) $itemData['quantity'];
                $unitCost = (float) $itemData['unit_cost'];

                ExpenseItem::create([
                    'expense_id'    => $expense->id,
                    'product_id'    => $itemData['product_id'] ?? null,
                    'company_id'    => $companyId,
                    'branch_id'     => $branch->id,
                    'User_id'       => $user->id,
                    'item_name'     => $itemData['item_name'],
                    'quantity'      => $qty,
                    'unit_cost'     => $unitCost,
                    'total_cost'    => $qty * $unitCost,
                    'supplier_name' => $itemData['supplier_name'] ?? null,
                ]);
            }

            DB::commit();

            Alert::success('Success ' . $user->name, 'Expense Updated Successfully');
            return redirect()->route('viewExpense', encrypt($expense->id));

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
     * Approve or Reject an Expense Requisition.
     */
    public function approveExpense(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'status'       => 'required|in:Approved,Rejected',
            'descriptions' => 'nullable|string',
        ]);

        try {
            $user = Auth::user();
            $expense = Expense::findOrFail($id);

            $expense->update([
                'AppStatus'    => $request->status,
                'approved_at'  => now(),
                'approved_by'  => $user->id,
                'descriptions' => $request->descriptions,
                'updated_by'   => $user->id,
            ]);

            Alert::success(
                'Success ' . $user->name,
                'Expense Status Updated to (' . $request->status . ')'
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
     * Recalculate an expense's payment accumulators from its ACTIVE payments.
     * Single source of truth used by pay, delete and restore.
     */
    private function syncExpensePayments(Expense $expense, $userId): void
    {
        $active = ExpensePayment::where('expense_id', $expense->id)->where('Status', 'Active');

        $totalPaid = round((float) (clone $active)->sum('amount_paid'), 2);
        $total     = round((float) $expense->total_amount, 2);
        $latest    = (clone $active)->orderByDesc('payment_date')->orderByDesc('id')->first();

        if ($totalPaid <= 0) {
            $status = 'Unpaid';
        } elseif ($totalPaid >= $total) {
            $status = 'Paid';
        } else {
            $status = 'Partially Paid';
        }

        $expense->update([
            'amount_paid'      => $totalPaid,
            'PaymentStatus'    => $status,
            'paid_at'          => $latest->payment_date,
            'paid_by'          => $latest->paid_by,
            'payment_method'   => $latest->payment_method,
            'reference_number' => $latest->reference_number,
            'updated_by'       => $userId,
        ]);
    }

    /**
     * List all active payments (with filters and totals).
     */
    public function expensePayments(Request $request)
    {
        try {
            $query = ExpensePayment::where('Status', 'Active')
                ->with(['expense.category', 'payer', 'company', 'branch']);

            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('payment_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('payment_date', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('payment_number', 'like', "%{$search}%")
                      ->orWhere('reference_number', 'like', "%{$search}%")
                      ->orWhereHas('expense', function ($e) use ($search) {
                          $e->where('expense_number', 'like', "%{$search}%")
                            ->orWhere('expense_title', 'like', "%{$search}%");
                      });
                });
            }

            $totalAmount = (clone $query)->sum('amount_paid');
            $data        = $query->latest('id')->paginate(15)->withQueryString();
            $branchies   = Branch::where('Status', 'Active')->get();

            return view('in.expenses.payments.payments', compact('data', 'branchies', 'totalAmount'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Show the payment processing page for an approved expense.
     * The form on this page posts to processExpensePayment.
     */
    public function payExpense($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $data = Expense::with(['category', 'branch', 'items', 'payments.payer'])->findOrFail($id);

            if ($data->Status !== 'Active') {
                Alert::warning('Denied', 'This expense is not active.');
                return redirect()->route('expenses');
            }

            if ($data->AppStatus !== 'Approved') {
                Alert::warning('Denied', 'Only approved expenses can be paid.');
                return redirect()->route('viewExpense', encrypt($data->id));
            }

            if ($data->PaymentStatus === 'Paid') {
                Alert::info('Info', 'This expense is already fully paid.');
                return redirect()->route('viewExpense', encrypt($data->id));
            }

            return view('in.expenses.payments.processPayment', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * REVISED processExpensePayment: row lock to prevent double-paying,
     * active-status check, and redirect to the expense instead of back().
     * Replace your existing method with this one.
     */

    /**
     * Approved expenses awaiting payment (Unpaid / Partially Paid).
     */
    public function unpaidExpenses(Request $request)
    {
        try {
            $query = Expense::where('Status', 'Active')
                ->where('AppStatus', 'Approved')
                ->whereIn('PaymentStatus', ['Unpaid', 'Partially Paid', 'Pending'])
                ->with(['category', 'branch', 'creator', 'approver']);

            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->filled('expense_category_id')) {
                $query->where('expense_category_id', $request->expense_category_id);
            }

            if ($request->filled('PaymentStatus')) {
                $query->where('PaymentStatus', $request->PaymentStatus);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('expense_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('expense_date', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('expense_number', 'like', "%{$search}%")
                      ->orWhere('expense_title', 'like', "%{$search}%")
                      ->orWhere('reference_number', 'like', "%{$search}%");
                });
            }

            // Summary for the filtered set
            $summary = (clone $query)
                ->selectRaw('COUNT(*) as cnt, COALESCE(SUM(total_amount),0) as total, COALESCE(SUM(amount_paid),0) as paid')
                ->first();

            $data       = $query->orderBy('expense_date')->orderBy('id')->paginate(15)->withQueryString();
            $categories = ExpenseCategory::where('Status', 'Active')->orderBy('name')->get();
            $branchies  = Branch::where('Status', 'Active')->get();

            return view('in.expenses.payments.unpaidExpenses', compact('data', 'categories', 'branchies', 'summary'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    public function processExpensePayment(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $request->validate([
            'amount_paid'      => 'required|numeric|min:0.01',
            'payment_method'   => 'required|string|max:100',
            'reference_number' => 'nullable|string|max:100',
            'descriptions'     => 'nullable|string',
        ]);

        DB::beginTransaction();
        // try {
            $user    = Auth::user();
            $expense = Expense::lockForUpdate()->findOrFail($id);

            if ($expense->Status !== 'Active' || $expense->AppStatus !== 'Approved') {
                DB::rollBack();
                Alert::warning('Denied', 'Only active, approved expenses can be paid.');
                return redirect()->route('viewExpense', encrypt($expense->id));
            }

            $amountPaid   = round((float) $request->amount_paid, 2);
            $newTotalPaid = round((float) $expense->amount_paid + $amountPaid, 2);

            if ($newTotalPaid > round((float) $expense->total_amount, 2)) {
                DB::rollBack();
                Alert::error('Payment Error', 'Payment amount exceeds the total expense balance.');
                return back()->withInput();
            }

            // Payment number (PAY-YYYYMMDD-0001)
            $today       = date('Ymd');
            $lastPayment = ExpensePayment::whereDate('created_at', today())->orderByDesc('id')->first();
            $nextPayNo   = $lastPayment ? ((int) substr($lastPayment->payment_number, -4)) + 1 : 1;
            $paymentNo   = 'IFLSG/PAY/' . $today . '/' . str_pad($nextPayNo, 4, '0', STR_PAD_LEFT);

            $payment = ExpensePayment::create([
                'expense_id'       => $expense->id,
                'payment_number'   => $paymentNo,
                'payment_date'     => now(),
                'amount_paid'      => $amountPaid,
                'payment_method'   => $request->payment_method,
                'reference_number' => $request->reference_number,
                'descriptions'     => $request->descriptions,
                'paid_by'          => $user->id,
                'company_id'       => $expense->company_id,
                'branch_id'        => $expense->branch_id,
                'User_id'          => $user->id,
                'Status'           => 'Active',
                'AuditingStatus'   => 'Pending',
                'ReportStatus'     => 'Pending',
            ]);

            $this->syncExpensePayments($expense, $user->id);

            DB::commit();

            Alert::success(
                'Success ' . $user->name,
                'Expense Payment Disbursed Successfully (' . $paymentNo . ')'
            );

            return redirect()->route('viewExpensePayment', encrypt($payment->id));

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
     * View a single payment.
     */
    public function viewExpensePayment($id)
    {
        try {
            $id = Crypt::decrypt($id);

            $data = ExpensePayment::with(['expense.category', 'expense.branch', 'payer', 'company', 'branch'])
                ->findOrFail($id);

            return view('in.expenses.payments.viewPayment', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Soft delete a payment and roll the parent expense balance back.
     */
    public function destroyExpensePayment($id)
    {
        DB::beginTransaction();
        try {
            $id      = Crypt::decrypt($id);
            $payment = ExpensePayment::findOrFail($id);
            $expense = Expense::lockForUpdate()->findOrFail($payment->expense_id);

            if ($payment->Status === 'Deleted') {
                DB::rollBack();
                Alert::warning('Info', 'This payment is already deleted.');
                return back();
            }

            $payment->update(['Status' => 'Deleted']);

            // Recalculates amount_paid, PaymentStatus (Unpaid / Partially Paid / Paid), etc.
            $this->syncExpensePayments($expense, Auth::id());

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Payment removed and expense balance updated (' . $expense->fresh()->PaymentStatus . ')'
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
     * Display soft-deleted payments.
     */
    public function deletedExpensePayments()
    {
        try {
            $data = ExpensePayment::where('Status', 'Deleted')
                ->with(['expense.category', 'payer', 'branch'])
                ->latest('updated_at')
                ->get();

            return view('in.expenses.payments.deletedPayments', compact('data'));

        } catch (\Throwable $th) {
            Alert::error(
                'Sorry! ' . Auth::user()->name,
                'Technical error exists, please contact Technical Support Tel:+255657856790'
            );
            return back();
        }
    }

    /**
     * Restore a deleted payment and re-apply it to the parent expense.
     */
    public function restoreExpensePayment($id)
    {
        DB::beginTransaction();
        try {
            $id      = Crypt::decrypt($id);
            $payment = ExpensePayment::findOrFail($id);
            $expense = Expense::lockForUpdate()->findOrFail($payment->expense_id);

            if ($expense->Status !== 'Active') {
                DB::rollBack();
                Alert::warning('Denied', 'Restore the parent expense first.');
                return back();
            }

            $activeTotal = (float) ExpensePayment::where('expense_id', $expense->id)
                ->where('Status', 'Active')
                ->sum('amount_paid');

            if (round($activeTotal + (float) $payment->amount_paid, 2) > round((float) $expense->total_amount, 2)) {
                DB::rollBack();
                Alert::error('Restore Error', 'Restoring this payment would exceed the total expense amount.');
                return back();
            }

            $payment->update(['Status' => 'Active']);

            $this->syncExpensePayments($expense, Auth::id());

            DB::commit();

            Alert::success(
                'Success ' . Auth::user()->name,
                'Payment restored and expense balance updated (' . $expense->fresh()->PaymentStatus . ')'
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
}