<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Models\LoanCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class LoanCategoryController extends Controller
{
    /**
     * Display a listing of the resource with search and filters.
     */
    public function index(Request $request)
    {
        $query = LoanCategory::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('conditions', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        if ($request->filled('currency')) {
            $query->where('currency', $request->input('currency'));
        }

        if ($request->filled('frequency')) {
            $query->where('repayment_frequency', $request->input('frequency'));
        }

        $loanCategories = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('in.loans.loan_categories.index', compact('loanCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('in.loans.loan_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'amount_disbursed' => 'required|numeric|min:0',
            'principal_due' => 'required|numeric|min:0',
            'insurance_fee' => 'nullable|numeric|min:0',
            'officer_visit_fee' => 'nullable|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0|max:100',

            'repayment_frequency' => 'required|in:daily,weekly,bi_weekly,monthly,quarterly',
            'max_term_days' => 'nullable|integer|min:0',
            'max_term_months' => 'nullable|integer|min:0',

            'currency' => 'nullable|string|max:10',
            'conditions' => 'nullable|string',
            'descriptions' => 'nullable|string',
            'is_active' => 'boolean',
            'is_new_client' => 'boolean',
        ]);

        // ✅ Retrieve actual numeric values from the request
        $amount_disbursed = (float) $request->input('amount_disbursed');
        $interest_rate = (float) $request->input('interest_rate', 20); // default 20 if null
        $principal_due = (float) $request->input('principal_due');

        // ✅ Calculate interest and dues safely
        $interest = ($amount_disbursed * $interest_rate) / 100;
        $repayableAmount = $interest + $amount_disbursed;

        // Avoid division by zero
        if ($principal_due > 0) {
            $totalDaysDue = $repayableAmount / $principal_due;
            $interestDue = ($principal_due * $interest_rate) / 100;
        } else {
            $totalDaysDue = 0;
            $interestDue = 0;
        }

        // ✅ Add calculated values
        $validated['interest_amount'] = $interest;
        $validated['total_days_due'] = $totalDaysDue;
        $validated['interest_due'] = $interestDue;
        $validated['created_by'] = Auth::id();
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_new_client'] = $request->boolean('is_new_client', true);

        LoanCategory::create($validated);
        return redirect()->route('loan_categories.index')->with('success', 'Loan category created successfully.');
            
    }

    /**
     * Display the specified resource.
     */
    public function show(LoanCategory $loanCategory)
    {
        return view('in.loans.loan_categories.show', compact('loanCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoanCategory $loanCategory)
    {
        return view('in.loans.loan_categories.edit', compact('loanCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoanCategory $loanCategory)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'amount_disbursed' => 'required|numeric|min:0',
            'principal_due' => 'required|numeric|min:0',
            'insurance_fee' => 'nullable|numeric|min:0',
            'officer_visit_fee' => 'nullable|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0|max:100',

            'repayment_frequency' => 'required|in:daily,weekly,bi_weekly,monthly,quarterly',
            'max_term_days' => 'nullable|integer|min:0',
            'max_term_months' => 'nullable|integer|min:0',

            'currency' => 'nullable|string|max:10',
            'conditions' => 'nullable|string',
            'descriptions' => 'nullable|string',
            'is_active' => 'boolean',
            'is_new_client' => 'boolean',
        ]);

        // ✅ Retrieve actual numeric values from the request
        $amount_disbursed = (float) $request->input('amount_disbursed');
        $interest_rate = (float) $request->input('interest_rate', 20); // default 20 if null
        $principal_due = (float) $request->input('principal_due');

        // ✅ Calculate interest and dues safely
        $interest = ($amount_disbursed * $interest_rate) / 100;

        $repayableAmount = $interest + $amount_disbursed;

        // Avoid division by zero
        if ($principal_due > 0) {
            $totalDaysDue = $repayableAmount / $principal_due;
            $interestDue = ($principal_due * $interest_rate) / 100;
        } else {
            $totalDaysDue = 0;
            $interestDue = 0;
        }

        // ✅ Add calculated values
        $validated['interest_amount'] = $interest;
        $validated['total_days_due'] = $totalDaysDue;
        $validated['interest_due'] = $interestDue;
        $validated['updated_by'] = Auth::id();

        $loanCategory->update($validated);

        return redirect()
            ->route('loan_categories.index')
            ->with('success', 'Loan category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanCategory $loanCategory)
    {
        $loanCategory->delete();

        return redirect()
            ->route('loan_categories.index')
            ->with('success', 'Loan category deleted successfully.');
    }

    /**
     * Toggle loan category active status.
     */
    public function toggleStatus(LoanCategory $loanCategory)
    {
        $loanCategory->is_active = !$loanCategory->is_active;
        $loanCategory->updated_by = Auth::id();
        $loanCategory->save();

        return redirect()
            ->route('loan_categories.index')
            ->with('success', 'Loan category status updated.');
    }
}
