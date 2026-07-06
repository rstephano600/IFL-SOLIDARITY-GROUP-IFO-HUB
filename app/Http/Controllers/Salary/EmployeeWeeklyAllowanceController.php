<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\Controller;

use App\Models\EmployeeWeeklyAllowance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EmployeeWeeklyAllowanceController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeWeeklyAllowance::with('employee');

        // Filters
        if ($request->filled('employee')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->employee}%")
                  ->orWhere('last_name', 'like', "%{$request->employee}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('week_start', [$request->from_date, $request->to_date]);
        }

        $allowances = $query->latest()->paginate(15);
        $total = $query->sum('amount');

        return view('in.employees.weekly_allowances.index', compact('allowances', 'total'));
    }

    public function create()
    {
        return view('in.employees.weekly_allowances.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'week_start' => 'required|date',
            'week_end' => 'required|date|after_or_equal:week_start',
            'allowance_type' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('allowances', 'public');
        }

        EmployeeWeeklyAllowance::create($data);

        return redirect()->route('employee_weekly_allowances.index')
            ->with('success', 'Weekly allowance recorded successfully.');
    }

    public function show(EmployeeWeeklyAllowance $employeeWeeklyAllowance)
    {
        return view('in.employees.weekly_allowances.show', compact('employeeWeeklyAllowance'));
    }

    public function edit(EmployeeWeeklyAllowance $employeeWeeklyAllowance)
    {
        return view('in.employees.weekly_allowances.edit', compact('employeeWeeklyAllowance'));
    }

    public function update(Request $request, EmployeeWeeklyAllowance $employeeWeeklyAllowance)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'week_start' => 'required|date',
            'week_end' => 'required|date|after_or_equal:week_start',
            'allowance_type' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            if ($employeeWeeklyAllowance->attachment) {
                Storage::disk('public')->delete($employeeWeeklyAllowance->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('allowances', 'public');
        }

        $employeeWeeklyAllowance->update($data);

        return redirect()->route('employee_weekly_allowances.index')
            ->with('success', 'Weekly allowance updated successfully.');
    }

    public function destroy(EmployeeWeeklyAllowance $employeeWeeklyAllowance)
    {
        if ($employeeWeeklyAllowance->attachment) {
            Storage::disk('public')->delete($employeeWeeklyAllowance->attachment);
        }

        $employeeWeeklyAllowance->delete();

        return back()->with('success', 'Weekly allowance deleted successfully.');
    }

    // AJAX employee search for Select2
    public function searchEmployees(Request $request)
    {
        $term = $request->get('term', '');
        $employees = Employee::where('first_name', 'like', "%$term%")
            ->orWhere('last_name', 'like', "%$term%")
            ->limit(10)
            ->get(['id', 'first_name', 'last_name']);

        $results = [];
        foreach ($employees as $emp) {
            $results[] = [
                'id' => $emp->id,
                'text' => $emp->first_name . ' ' . $emp->last_name
            ];
        }

        return response()->json(['results' => $results]);
    }
}
