<?php

namespace App\Http\Controllers\Salary;

use App\Http\Controllers\Controller;

use App\Models\SalaryLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salaryLevels = SalaryLevel::orderBy('name')->paginate(10);
        return view('in.salaries.salary_levels.index', compact('salaryLevels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('in.salaries.salary_levels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:salary_levels,name',
            'basic_amount' => 'required|numeric|min:0',
            'insurance_amount' => 'nullable|numeric|min:0',
            'nssf' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'net_amount_due' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['created_by'] = Auth::id();

        SalaryLevel::create($validated);

        return redirect()->route('salary_levels.index')->with('success', 'Salary level created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SalaryLevel $salaryLevel)
    {
        return view('in.salaries.salary_levels.show', compact('salaryLevel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryLevel $salaryLevel)
    {
        return view('in.salaries.salary_levels.edit', compact('salaryLevel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalaryLevel $salaryLevel)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:salary_levels,name,' . $salaryLevel->id,
            'basic_amount' => 'required|numeric|min:0',
            'insurance_amount' => 'nullable|numeric|min:0',
            'nssf' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'net_amount_due' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['updated_by'] = Auth::id();

        $salaryLevel->update($validated);

        return redirect()->route('salary_levels.index')->with('success', 'Salary level updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryLevel $salaryLevel)
    {
        $salaryLevel->delete();
        return redirect()->route('salary_levels.index')->with('success', 'Salary level deleted successfully.');
    }
}
