<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'product_code',
        'product_name',

        'product_category_id',
        'CompanyBusinessCode_id',

        'income_gl_account_id',
        'expense_gl_account_id',
        'inventory_gl_account_id',

        'unit_of_measure_id',

        'cost_price',
        'selling_price',
        'minimum_price',
        'maximum_price',
        'tax_rate',

        'requires_member',
        'requires_approval',
        'is_stock_item',
        'allow_discount',

        'description',

        'company_id',
        'branch_id',
        'User_id',

        'Status',
        'AuditingStatus',
        'ReportStatus',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'minimum_price' => 'decimal:2',
        'maximum_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',

        'requires_member' => 'boolean',
        'requires_approval' => 'boolean',
        'is_stock_item' => 'boolean',
        'allow_discount' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Product Category
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    // Business Code
    public function business()
    {
        return $this->belongsTo(CompanyBusinessCode::class, 'CompanyBusinessCode_id');
    }

    // Company
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    // Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    // Record Owner
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    // GL Accounts
    public function incomeAccount()
    {
        return $this->belongsTo(AccountThirdBranch::class, 'income_gl_account_id');
    }

    public function expenseAccount()
    {
        return $this->belongsTo(AccountThirdBranch::class, 'expense_gl_account_id');
    }

    public function inventoryAccount()
    {
        return $this->belongsTo(AccountThirdBranch::class, 'inventory_gl_account_id');
    }

    // Unit of Measure
    public function unit()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'unit_of_measure_id');
    }

    // Audit Users
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Child Relationships
    |--------------------------------------------------------------------------
    */

    public function stockRegistrations()
    {
        return $this->hasMany(StockRegistrationProduct::class, 'Product_id');
    }

    public function stockOuts()
    {
        return $this->hasMany(StockOutProduct::class, 'Product_id');
    }
}