<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffMealShiftRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'staff_meal_shift_records';

    protected $fillable = [
        'ReferenceNo',
        'record_no',
        'service_date',
        'shift_id',
        'product_id',
        'meal_type',
        'meal_count',
        'unit_price',
        'total_amount',
        'billing_period_id',
        'confirmed_by',
        'confirmed_at',
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
        'meal_count' => 0,
        'unit_price' => 0.00,
        'total_amount' => 0.00,
        'Status' => 'Draft',
        'AuditingStatus' => 'Pending',
        'ReportStatus' => 'Pending',
    ];

    protected $casts = [
        'service_date' => 'date',
        'meal_count' => 'integer',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'shift_id' => 'integer',
        'product_id' => 'integer',
        'billing_period_id' => 'integer',
        'confirmed_by' => 'integer',
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

    public function shift(): BelongsTo
    {
        return $this->belongsTo(StaffMealShift::class, 'shift_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function billingPeriod(): BelongsTo
    {
        return $this->belongsTo(SalesBillingPeriod::class, 'billing_period_id');
    }

    public function confirmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
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

    public function companyBillItems(): HasMany
    {
        return $this->hasMany(SalesCompanyBillItem::class, 'shift_record_id');
    }
}