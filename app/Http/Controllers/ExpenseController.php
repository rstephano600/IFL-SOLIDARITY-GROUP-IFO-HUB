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
        try {

            $data = ExpenseCategory::with([
                    'user',
                    'creator',
                    'updater'
                ])
                ->latest()
                ->get();

            return view(
                'in.expenses.categories.expensecategoryinformations',
                compact('data')
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! '.Auth()->user()->name,
                'Technical error exists, please contact Technical Support.'
            );

            return back();
        }
    }

    public function registerexpensecategory(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255|unique:expense_categories,name',
                'description' => 'nullable|string'
            ]);
            // Generate Expense Category Code
            $today = date('Ymd');

            $lastCategory = ExpenseCategory::whereDate('created_at', today())
                ->latest('id')
                ->first();

            $nextNumber = $lastCategory
                ? ((int) substr($lastCategory->expense_code, -4)) + 1
                : 1;

            $expenseCode = 'EXP-' . $today . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            ExpenseCategory::create([

                'expense_code' => $expenseCode,
                'name' => $request->name,
                'description' => $request->description,

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

        } catch (\Throwable $th) {

            Alert::error(
                'Error',
                $th->getMessage()
            );

            return back()->withInput();
        }
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
        try {
            // Eager load relevant user meta mapping records for the edit tracking view
            $data = ExpenseCategory::with([
                    'user',
                    'creator',
                    'updater'
                ])
                ->findOrFail(decrypt($id));

            return view(
                'in.expenses.categories.editexpensecategory',
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
    public function updateexpensecategory(Request $request, $id)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255|unique:expense_categories,name,' . decrypt($id),
                'description' => 'nullable|string',
            ]);

            $data = ExpenseCategory::findOrFail(decrypt($id));

            $data->update([
                'name' => $request->name,
                'description' => $request->description,

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

    // EXPENSES INFORMATIONS

    public function expenseinformations()
    {
        try {

            $data = Expense::with([
                    'category',
                    'items',
                    'creator',
                    'approver',
                    'payer'
                ])
                ->latest()
                ->get();

            $categories = ExpenseCategory::where('Status', 'Active')
                ->orderBy('name')
                ->get();

            return view(
                'in.expenses.expenseinformations',
                compact('data', 'categories')
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! '.Auth()->user()->name,
                'Technical error exists, please contact Technical Support.'
            );

            return back();
        }
    }

    public function registerexpense(Request $request)
    {
        // try {

            DB::beginTransaction();

            $request->validate([
                'expense_title' => 'required|max:255',
                'expense_category_id' => 'required',
                'expense_date' => 'required|date',
                'currency' => 'required',
                'total_amount' => 'required|numeric|min:0',
            ]);
            $today = date('Ymd');

            $lastCategory = Expense::whereDate('created_at', today())
                ->latest('id')
                ->first();

            $nextNumber = $lastCategory
                ? ((int) substr($lastCategory->expense_code, -4)) + 1
                : 1;

            $expenseCode = 'EXP-' . $today . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $expense = Expense::create([

                'expense_number' => $expenseCode,

                'expense_title' => $request->expense_title,
                'description' => $request->description,
                'expense_date' => $request->expense_date,
                'expense_category_id' => $request->expense_category_id,

                'total_amount' => $request->total_amount,

                'currency' => $request->currency,

                'PaymentStatus' => 'Pending',

                'User_id' => Auth::id(),
                'created_by' => Auth::id(),

                'Status' => 'Active',
                'AuditingStatus' => 'Pending',
                'ReportStatus' => 'Show',

            ]);

            if ($request->has('item_name')) {

                foreach ($request->item_name as $key => $item) {

                    ExpenseItem::create([

                        'expense_id' => $expense->id,

                        'item_name' => $item,

                        'description' => $request->description_item[$key] ?? null,

                        'quantity' => $request->quantity[$key],

                        'unit' => $request->unit[$key],

                        'unit_cost' => $request->unit_cost[$key],

                        'total_cost' => $request->total_cost[$key],

                        'supplier' => $request->supplier[$key] ?? null,

                        'User_id' => Auth::id(),

                        'Status' => 'Active',

                        'AuditingStatus' => 'Pending',

                        'ReportStatus' => 'Show',

                    ]);
                }

            }

            DB::commit();

            Alert::success(
                'Success',
                'Expense Registered Successfully.'
            );

            return back();

        // } catch (\Throwable $th) {

        //     DB::rollBack();

        //     Alert::error(
        //         'Error',
        //         $th->getMessage()
        //     );

        //     return back()->withInput();
        // }
    }

    public function deleteexpense($id)
    {
        try {

            $expense = Expense::findOrFail(decrypt($id));

            $expense->update([

                'Status' => 'Deleted',

                'updated_by' => Auth::id()

            ]);

            Alert::success(
                'Success',
                'Expense Deleted Successfully.'
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

    public function viewexpense($id)
    {
        // try {
            // Eager load relationships along with the expenses relation count
            $data = Expense::with([
                    'user',
                    'creator',
                    'updater',
                ])
                ->findOrFail(decrypt($id));

            return view(
                'in.expenses.viewexpense',
                compact('data')
            );

        // } catch (\Throwable $th) {

        //     Alert::error(
        //         'Sorry! ' . auth()->user()->name,
        //         'Technical error exists, please contact Technical Support.'
        //     );

        //     return back();
        // }
    }


    public function pendingexpense()
    {
        try {

            $data = Expense::with([
                    'category',
                    'items',
                    'creator'
                ])
                ->where('Status', 'Active')
                ->where('AppStatus', 'Pending')
                ->latest()
                ->get();

            return view(
                'in.expenses.pendingexpense',
                compact('data')
            );

        } catch (\Throwable $th) {

            Alert::error(
                'Sorry! '.Auth()->user()->name,
                'Technical error exists.'
            );

            return back();
        }
    }

// POST: Process approval status changes
    public function approveexpense($id)
    {
        try {
            $expense = Expense::findOrFail(decrypt($id));
            
            if ($expense->AppStatus == 'Approved') {

                Alert::warning(
                    'Warning',
                    'Expense already approved.'
                );

                return back();
            }
            if ($expense->AppStatus == 'Rejected') {

                Alert::warning(
                    'Warning',
                    'Expense already Rejected.'
                );

                return back();
            }

            $expense->update([
                'AppStatus' => 'Approved',
                'approved_at' => now(),
                'approved_by' => auth()->id()
            ]);

            Alert::success('Success', 'Expense voucher approved for disbursal.');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Error', 'Failed to update approval records.');
            return back();
        }
    }

    // POST: Process rejection status changes
    public function rejectexpense($id)
    {
        try {
            $expense = Expense::findOrFail(decrypt($id));
            if ($expense->AppStatus == 'Approved') {

                Alert::warning(
                    'Warning',
                    'Expense already approved.'
                );

                return back();
            }
            if ($expense->AppStatus == 'Rejected') {

                Alert::warning(
                    'Warning',
                    'Expense already Rejected.'
                );

                return back();
            }
            $expense->update([
                'AppStatus' => 'Rejected',
                'updated_by' => auth()->id()
            ]);

            Alert::success('Handled', 'Expense voucher marked as rejected.');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Error', 'Action routing failed.');
            return back();
        }
    }



    // GET: Unpaid authorized items queue
    // GET: Unpaid or Partially Paid authorized items queue
    public function unpayedexpense()
    {
        try {
            $data = Expense::with(['category', 'items', 'creator'])
                ->where('Status', 'Active')
                ->where('AppStatus', 'Approved')
                ->whereIn('PaymentStatus', ['Pending', 'Partially Paid']) // Include partially paid entries
                ->latest()
                ->get();

            return view('in.expenses.unpayedexpense', compact('data'));
        } catch (\Throwable $th) {
            return back();
        }
    }

    // POST: Final or Partial disbursement of cash asset lines
    public function payexpense(Request $request, $id)
    {
        $request->validate([
            'amount_to_pay' => 'required|numeric|min:0.01',
            'payment_method' => 'required',
            'reference_number'=> 'nullable',
            'descriptions' => 'nullable',
        ]);

        // try {
            $expense = Expense::findOrFail(decrypt($id));
            
            if ($expense->AppStatus != 'Approved') {
                Alert::error('Unauthorized', 'Voucher must be authorized before handling cash payouts.');
                return back();
            }

            // Fallback defaults if columns don't exist yet
            $currentPaid = (float)($expense->amount_paid ?? 0);
            $totalAmount = (float)$expense->total_amount;
            $newPayment = (float)$request->input('amount_to_pay');
            
            $aggregatePaid = $currentPaid + $newPayment;
            $remainingBalance = $totalAmount - $aggregatePaid;

            // Guard against overpaying
            if ($remainingBalance < -0.01) {
                Alert::error('Invalid Amount', 'The entered payment amount exceeds the remaining voucher balance.');
                return back();
            }

            // Determine status based on the remaining balance
            // Using a tiny buffer (0.01) to handle floating-point math rounding safely
            $status = ($remainingBalance <= 0.01) ? 'Paid' : 'Partially Paid';

            $today = date('Ymd');

            $lastCategory = ExpensePayment::whereDate('created_at', today())
                ->latest('id')
                ->first();

            $nextNumber = $lastCategory
                ? ((int) substr($lastCategory->expense_code, -4)) + 1
                : 1;

            $expenseCode = 'EXPPAY-' . $today . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            ExpensePayment::create([

                'expense_id'=>$expense->id,

                'payment_number'=>$expenseCode,

                'payment_date'=>today(),

                'amount_paid'=>$newPayment,

                'payment_method'=>$request->payment_method,

                'reference_number'=>$request->reference_number,

                'descriptions'=>$request->descriptions,

                'paid_by'=>Auth::id(),

                'User_id'=>Auth::id(),

                'Status'=>'Active',

                'AuditingStatus'=>'Pending',

                'ReportStatus'=>'Show'

            ]);

            $totalPaid = ExpensePayment::where(
                            'expense_id',
                            $expense->id
                        )->sum('amount_paid');
            $expense->update([
                        'amount_paid'=>$totalPaid,
                        'PaymentStatus'=>$status,
                        'paid_at'=>now(),
                        'paid_by'=>Auth::id(),
                    ]);

            Alert::success('Disbursed', 'Payment of ' . $expense->currency . ' ' . number_format($newPayment, 2) . ' successfully applied.');
            return back();
        // } catch (\Throwable $th) {
        //     Alert::error('Error', 'Disbursal log registration failure.');
        //     return back();
        // }
    }

    // GET: Paid historical archives logs lookup
    public function payedexpense()
    {
        try {
            $data = Expense::with(['category', 'items', 'creator', 'payer'])
                ->where('Status', 'Active')
                ->where('PaymentStatus', 'Paid')
                ->latest()
                ->get();

            return view('in.expenses.expenseinformations', compact('data'));
        } catch (\Throwable $th) {
            return back();
        }
    }

    // GET: Comprehensive auditing history summary logs
    public function expensehistory()
    {
        try {
            $data = Expense::with(['category', 'items', 'creator', 'approver', 'payer'])
                ->latest()
                ->get();

            return view('in.expenses.expenseinformations', compact('data'));
        } catch (\Throwable $th) {
            return back();
        }
    }


    // GET: Complete Payment Transaction Logs
    public function expensepayments()
    {
        try {
            $data = ExpensePayment::with([
                    'expense',
                    'payer',
                    'user'
                ])
                ->where('Status', 'Active')
                ->latest('payment_date')
                ->get();

            return view('in.expenses.expensepayments', compact('data'));
        } catch (\Throwable $th) {
            Alert::error('Error', 'Failed to retrieve transaction history maps.');
            return back();
        }
    }













public function index(Request $request)

{
    $query = Expense::with(['category', 'creator']);

    // 🔍 Search by title or category
    if ($request->filled('search')) {
        $query->where('expense_title', 'LIKE', '%'.$request->search.'%')
              ->orWhereHas('category', function ($q) use ($request) {
                  $q->where('name', 'LIKE', '%'.$request->search.'%');
              });
    }

    // 📅 Filter by date range
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('expense_date', [
            $request->start_date,
            $request->end_date
        ]);
    }

    // 🏷️ Filter by category
    if ($request->filled('category_id')) {
        $query->where('expense_category_id', $request->category_id);
    }

    // 🧾 Fetch filtered results
    $expenses = $query->latest()->paginate(20)->withQueryString();

    // 💰 Calculate total amount used in current filter
    $totalUsed = $query->sum('total_amount');

    // 🗂 Fetch categories for filter dropdown
    $categories = \App\Models\ExpenseCategory::all();

    return view('in.expenses.expenses.index', compact('expenses', 'totalUsed', 'categories'));
}


    /**
     * Show form for creating a new expense
     */
    public function create()
    {
        $categories = ExpenseCategory::where('status', 'active')->get();
        return view('in.expenses.expenses.create', compact('categories'));
    }

    /**
     * Store a new expense with items
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'expense_category_id' => 'nullable|exists:expense_categories,id',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'status' => 'nullable|in:pending,approved,rejected',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'nullable|numeric|min:0',
            'items.*.supplier_name' => 'nullable|string|max:255',
            'items.*.attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Handle main expense attachment
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('expense_attachments', 'public');
        }

        DB::transaction(function () use ($validated) {
            $expense = Expense::create($validated);

            foreach ($validated['items'] as $item) {
                $itemAttachment = null;
                if (!empty($item['attachment']) && is_file($item['attachment'])) {
                    $itemAttachment = $item['attachment']->store('expense_item_attachments', 'public');
                }
                $totalCost = ($item['quantity'] ?? 0) * ($item['unit_cost'] ?? 0);

                ExpenseItem::create([
                    'expense_id' => $expense->id,
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'] ?? 0,
                    'total_cost' => $totalCost,
                    'supplier_name' => $item['supplier_name'] ?? null,
                    'attachment' => $itemAttachment,
                ]);
            }
        });

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    /**
     * Show a single expense with items
     */
    public function show(Expense $expense)
    {
        $expense->load('items', 'category', 'creator', 'editor');
        return view('in.expenses.expenses.show', compact('expense'));
    }

    /**
     * Show form to edit an expense with items
     */
    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::where('status', 'active')->get();
        $expense->load('items');
        return view('in.expenses.expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update an expense and its items
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'expense_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'expense_category_id' => 'nullable|exists:expense_categories,id',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'status' => 'nullable|in:pending,approved,rejected',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'nullable|numeric|min:0',
            'items.*.supplier_name' => 'nullable|string|max:255',
            'items.*.attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Handle main attachment
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('expense_attachments', 'public');
        }

        DB::transaction(function () use ($validated, $expense) {
            $expense->update([
                'expense_title' => $validated['expense_title'],
                'description' => $validated['description'] ?? null,
                'expense_date' => $validated['expense_date'],
                'total_amount' => $validated['total_amount'],
                'currency' => $validated['currency'],
                'expense_category_id' => $validated['expense_category_id'] ?? null,
                'attachment' => $validated['attachment'] ?? $expense->attachment,
                'status' => $validated['status'] ?? 'approved',
                'updated_by' => Auth::id(),
            ]);

            // Remove old items and re-insert
            $expense->items()->delete();

            foreach ($validated['items'] as $item) {
                $itemAttachment = null;
                if (!empty($item['attachment']) && is_file($item['attachment'])) {
                    $itemAttachment = $item['attachment']->store('expense_item_attachments', 'public');
                }
                $totalCost = ($item['quantity'] ?? 0) * ($item['unit_cost'] ?? 0);

                ExpenseItem::create([
                    'expense_id' => $expense->id,
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'] ?? 0,
                    'total_cost' => $totalCost,
                    'supplier_name' => $item['supplier_name'] ?? null,
                    'attachment' => $itemAttachment,
                ]);
            }
        });

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }
public function export(Request $request)
{
    $query = Expense::with('category');

    if ($request->filled('category_id')) {
        $query->where('expense_category_id', $request->category_id);
    }

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
    }

    $expenses = $query->get(['expense_title', 'total_amount', 'expense_date']);

    $filename = 'expenses_export_' . now()->format('Y_m_d_His') . '.csv';

    $handle = fopen('php://temp', 'r+');
    fputcsv($handle, ['Title', 'Amount', 'Date']);

    foreach ($expenses as $expense) {
        fputcsv($handle, [$expense->expense_title, $expense->total_amount, $expense->expense_date]);
    }

    rewind($handle);
    return response(stream_get_contents($handle), 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=$filename",
    ]);
}

public function exportExcel()
    {
        return Excel::download(new ExpensesExport, 'expenses_by_category.xlsx');
    }

    public function exportPDF()
    {
        $expensesByCategory = Expense::with('items')->get()->groupBy('category');
        $pdf = Pdf::loadView('exports.expenses', compact('expensesByCategory'))
            ->setPaper('a4', 'portrait');
        return $pdf->download('expenses_by_category.pdf');
    }
    /**
     * Delete expense with items
     */
    public function destroy(Expense $expense)
    {
        // $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
