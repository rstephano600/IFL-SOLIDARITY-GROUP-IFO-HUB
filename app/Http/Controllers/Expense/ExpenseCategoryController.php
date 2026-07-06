<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    // Display a listing of categories with search and pagination
    public function index(Request $request)
    {
        $query = ExpenseCategory::query();

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%'.$request->search.'%')
                  ->orWhere('description', 'LIKE', '%'.$request->search.'%');
        }

        $categories = $query->latest()->paginate(10)->withQueryString();

        return view('in.expenses.categories.index', compact('categories'));
    }

    // Show form for creating new category
    public function create()
    {
        return view('in.expenses.categories.create');
    }

    // Store a new category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,innactive',
        ]);

        ExpenseCategory::create($validated);

        return redirect()->route('expense-categories.index')
                         ->with('success', 'Expense category created successfully.');
    }

    // Show a single category
    public function show(ExpenseCategory $expenseCategory)
    {
        return view('in.expenses.categories.show', compact('expenseCategory'));
    }

    // Show form for editing
    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('in.expenses.categories.edit', compact('expenseCategory'));
    }

    // Update an existing category
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name,'.$expenseCategory->id,
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,innactive',
        ]);

        $expenseCategory->update($validated);

        return redirect()->route('expense-categories.index')
                         ->with('success', 'Expense category updated successfully.');
    }

    // Delete category
    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();

        return redirect()->route('expense-categories.index')
                         ->with('success', 'Expense category deleted successfully.');
    }
}
