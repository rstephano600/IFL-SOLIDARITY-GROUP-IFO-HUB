<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\Controller;

use App\Models\EmployeeSalaryPayment;
use App\Models\EmployeeSalary;
use App\Models\SalaryLevel;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeSalaryPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EmployeeSalaryPayment::with(['employee', 'employeeSalary']);

        // 🔍 Filtering by employee or date
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);
        $employees = Employee::select('id', 'first_name', 'last_name')->get();

        return view('in.salaries.employee_salary_payments.index', compact('payments', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salaryRecords = EmployeeSalary::with('employee')->get();
        return view('in.salaries.employee_salary_payments.create', compact('salaryRecords'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_salary_id' => 'required|exists:employee_salaries,id',
            'employee_id' => 'nullable|exists:employees,id',
            'payment_date' => 'required|date',
            'amount_paid' => 'required|numeric|min:0',
            'insurance_amount' => 'nullable|numeric|min:0',
            'nssf' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'payment_method' => 'nullable|string|max:100',
            'reference_number' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('salary_payments', 'public');
        }

        $employeeSalary = EmployeeSalary::findOrFail($validated['employee_salary_id']);

        $validated['employee_id'] = $employeeSalary->employee_id;

        $validated['created_by'] = Auth::id();

        EmployeeSalaryPayment::create($validated);

        return redirect()->route('employee_salary_payments.index')->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeSalaryPayment $employeeSalaryPayment)
    {
        $employeeSalaryPayment->load(['employee', 'salaryLevel']);
        return view('in.salaries.employee_salary_payments.show', compact('employeeSalaryPayment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeSalaryPayment $employeeSalaryPayment)
    {
        $salaryRecords = EmployeeSalary::with('employee')->get();
        return view('in.salaries.employee_salary_payments.edit', compact('employeeSalaryPayment', 'salaryRecords'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeSalaryPayment $employeeSalaryPayment)
    {
        $validated = $request->validate([
            'employee_salary_id' => 'required|exists:employee_salaries,id',
            'employee_id' => 'nullable|exists:employees,id',
            'payment_date' => 'required|date',
            'amount_paid' => 'required|numeric|min:0',
            'insurance_amount' => 'nullable|numeric|min:0',
            'nssf' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:100',
            'reference_number' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);


        if ($request->hasFile('attachment')) {
            if ($employeeSalaryPayment->attachment) {
                Storage::disk('public')->delete($employeeSalaryPayment->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('salary_payments', 'public');
        }

        $employeeSalary = EmployeeSalary::findOrFail($validated['employee_salary_id']);

        $validated['employee_id'] = $employeeSalary->employee_id;

        $validated['updated_by'] = Auth::id();

        $employeeSalaryPayment->update($validated);

        return redirect()->route('employee_salary_payments.index')->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeSalaryPayment $employeeSalaryPayment)
    {
        if ($employeeSalaryPayment->attachment) {
            Storage::disk('public')->delete($employeeSalaryPayment->attachment);
        }
        $employeeSalaryPayment->delete();

        return redirect()->route('employee_salary_payments.index')->with('success', 'Payment deleted successfully.');
    }
}
