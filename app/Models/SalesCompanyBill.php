<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesCompanyBill extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales_company_bills';

    protected $fillable = [
        'ReferenceNo',
        'bill_no',
        'customer_id',
        'billing_period_id',
        'bill_date',
        'due_date',
        'subtotal',
        'adjustments',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'approved_by',
        'approved_at',
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
        'subtotal' => 0.00,
        'adjustments' => 0.00,
        'total_amount' => 0.00,
        'paid_amount' => 0.00,
        'balance_amount' => 0.00,
        'Status' => 'Draft',
        'AuditingStatus' => 'Pending',
        'ReportStatus' => 'Pending',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'adjustments' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'customer_id' => 'integer',
        'billing_period_id' => 'integer',
        'approved_by' => 'integer',
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(SalesCustomer::class, 'customer_id');
    }

    public function billingPeriod(): BelongsTo
    {
        return $this->belongsTo(SalesBillingPeriod::class, 'billing_period_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
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
        return $this->hasMany(SalesCompanyBillItem::class, 'company_bill_id');
    }
}