<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\Controller;

use App\Models\EmployeeSalary;
use App\Models\Employee;
use App\Models\SalaryLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = EmployeeSalary::with(['employee', 'salaryLevel'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('employee', function ($emp) use ($search) {
                    $emp->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('employee_number', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at');

        $salaries = $query->paginate(10);

        return view('in.salaries.employee_salaries.index', compact('salaries', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $employees = Employee::where('is_active', 1)->orderBy('first_name')->get();
        $salaryLevels = SalaryLevel::where('status', 'active')->orderBy('name')->get();

        return view('in.salaries.employee_salaries.create', compact('employees', 'salaryLevels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_level_id' => 'nullable|exists:salary_levels,id',
            'basic_amount' => 'required|numeric|min:0',
            'insurance_amount' => 'nullable|numeric|min:0',
            'nssf' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'status' => 'required|in:active,inactive',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Auto-calculate net amount
        $validated['net_amount_due'] = ($validated['basic_amount'] ?? 0) 
                                     + ($validated['bonus'] ?? 0)
                                     - (($validated['insurance_amount'] ?? 0)
                                     + ($validated['nssf'] ?? 0)
                                     + ($validated['tax'] ?? 0));

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('salary_attachments', 'public');
        }

        $validated['created_by'] = Auth::id();

        EmployeeSalary::create($validated);

        return redirect()->route('employee_salaries.index')->with('success', 'Employee salary record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeSalary $employeeSalary)
    {
        $employeeSalary->load(['employee', 'salaryLevel']);
        return view('in.salaries.employee_salaries.show', compact('employeeSalary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeSalary $employeeSalary)
    {
        $employees = Employee::orderBy('first_name')->get();
        $salaryLevels = SalaryLevel::where('status', 'active')->orderBy('name')->get();

        return view('in.salaries.employee_salaries.edit', compact('employeeSalary', 'employees', 'salaryLevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeSalary $employeeSalary)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_level_id' => 'nullable|exists:salary_levels,id',
            'basic_amount' => 'required|numeric|min:0',
            'insurance_amount' => 'nullable|numeric|min:0',
            'nssf' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'status' => 'required|in:active,inactive',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $validated['net_amount_due'] = ($validated['basic_amount'] ?? 0) 
                                     + ($validated['bonus'] ?? 0)
                                     - (($validated['insurance_amount'] ?? 0)
                                     + ($validated['nssf'] ?? 0)
                                     + ($validated['tax'] ?? 0));

        if ($request->hasFile('attachment')) {
            if ($employeeSalary->attachment) {
                Storage::disk('public')->delete($employeeSalary->attachment);
            }
            $validated['attachment'] = $request->file('attachment')->store('salary_attachments', 'public');
        }

        $validated['updated_by'] = Auth::id();

        $employeeSalary->update($validated);

        return redirect()->route('employee_salaries.index')->with('success', 'Employee salary updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function searchEmployees(Request $request)
{
    $term = $request->get('term', '');

    $employees = \App\Models\Employee::query()
        ->where('first_name', 'like', "%{$term}%")
        ->orWhere('last_name', 'like', "%{$term}%")
        ->orWhere('employee_number', 'like', "%{$term}%")
        ->limit(10)
        ->get(['id', 'first_name', 'last_name', 'employee_number']);

    $results = $employees->map(function ($e) {
        return [
            'id' => $e->id,
            'text' => "{$e->first_name} {$e->last_name} ({$e->employee_number})",
        ];
    });

    return response()->json(['results' => $results]);
}

    public function destroy(EmployeeSalary $employeeSalary)
    {
        if ($employeeSalary->attachment) {
            Storage::disk('public')->delete($employeeSalary->attachment);
        }
        $employeeSalary->delete();

        return redirect()->route('employee_salaries.index')->with('success', 'Employee salary deleted successfully.');
    }

    public function unsigned()
   {
    $payment = EmployeeSalaryPayment::findOrFail($id);

    // Ensure the logged-in user is the owner of the salary
    if (auth()->id() !== $payment->employee_id) {
        abort(403, 'You are not authorized to sign this salary.');
    }

    return view('in.employee_salary_payments.sign', compact('payment'));
}
    public function sign($id)
{
    $payment = EmployeeSalaryPayment::findOrFail($id);

    // Ensure the logged-in user is the owner of the salary
    if (auth()->id() !== $payment->employee_id) {
        abort(403, 'You are not authorized to sign this salary.');
    }

    return view('in.employee_salary_payments.sign', compact('payment'));
}

public function storeSignature(Request $request, $id)
{
    $payment = EmployeeSalaryPayment::findOrFail($id);

    if (auth()->id() !== $payment->employee_id) {
        abort(403);
    }

    $request->validate([
        'signature' => 'required|string', // base64 or file upload
    ]);

    // You can store base64 signature or handle file upload
    $payment->update([
        'employee_acknowledged' => true,
        'employee_signature' => $request->signature,
        'employee_signed_at' => now(),
    ]);

    return redirect()->route('employee_salary_payments.show', $payment->id)
        ->with('success', 'You have successfully signed your salary payment.');
}


}
