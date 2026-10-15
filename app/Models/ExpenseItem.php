<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseItem extends Model
{
    use HasFactory;

    protected $table = 'expense_items';

    protected $fillable = [
        'expense_id',
        'product_id',
        'company_id',
        'branch_id',
        'item_name',
        'quantity',
        'unit_cost',
        'total_cost',
        'supplier_name',
        'attachment',
        'User_id',
    ];

    protected $casts = [
        'expense_id' => 'integer',
        'product_id' => 'integer',
        'quantity'   => 'decimal:3',
        'unit_cost'  => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    /**
     * Get the parent expense.
     */
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

    /**
     * Get the optional connected product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'User_id'
        );
    }

}