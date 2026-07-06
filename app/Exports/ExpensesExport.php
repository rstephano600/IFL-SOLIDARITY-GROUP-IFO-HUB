<?php

namespace App\Exports;

use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExpensesExport implements FromView
{
    public function view(): View
    {
        // Group expenses by category
        $expensesByCategory = Expense::with('items')
            ->get()
            ->groupBy('category');

        return view('exports.expenses', compact('expensesByCategory'));
    }
}
