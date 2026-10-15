<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales';

    protected $fillable = [
        'ReferenceNo',
        'sale_no',
        'sale_date',
        'sales_type_id',
        'customer_id',
        'billing_period_id',
        'cashier_id',
        'payment_terms',
        'payment_status',
        'is_invoiced',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'currency_code',
        'notes',
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

    protected $attributes = [
        'payment_terms' => 'IMMEDIATE',
        'payment_status' => 'UNPAID',
        'is_invoiced' => false,
        'subtotal' => 0.00,
        'discount_amount' => 0.00,
        'tax_amount' => 0.00,
        'total_amount' => 0.00,
        'paid_amount' => 0.00,
        'balance_amount' => 0.00,
        'currency_code' => 'TZS',
        'Status' => 'Draft',
        'AuditingStatus' => 'Pending',
        'ReportStatus' => 'Pending',
    ];

    protected $casts = [
        'is_invoiced' => 'boolean',
        'sale_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'sales_type_id' => 'integer',
        'customer_id' => 'integer',
        'billing_period_id' => 'integer',
        'cashier_id' => 'integer',
        'company_id' => 'integer',
        'branch_id' => 'integer',
        'User_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function salesType(): BelongsTo
    {
        return $this->belongsTo(SalesType::class, 'sales_type_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(SalesCustomer::class, 'customer_id');
    }

    public function billingPeriod(): BelongsTo
    {
        return $this->belongsTo(SalesBillingPeriod::class, 'billing_period_id');
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SalePayment::class, 'sale_id');
    }

    public function companyBillItems(): HasMany
    {
        return $this->hasMany(SalesCompanyBillItem::class, 'sale_id');
    }
}