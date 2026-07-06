<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'item_name',
        'quantity',
        'unit_cost',
        'total_cost',
        'supplier_name',
        'attachment',
    ];

    // Relationship
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
