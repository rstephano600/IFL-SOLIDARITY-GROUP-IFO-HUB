<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesBillingPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales_billing_periods';

    protected $fillable = [
        'ReferenceNo',
        'period_name',
        'start_date',
        'end_date',
        'billing_frequency',
        'closed_at',
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
        'billing_frequency' => 'MONTHLY',
        'Status' => 'Open',
        'AuditingStatus' => 'Pending',
        'ReportStatus' => 'Pending',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'closed_at' => 'datetime',
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

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'billing_period_id');
    }

    public function mealShiftRecords(): HasMany
    {
        return $this->hasMany(StaffMealShiftRecord::class, 'billing_period_id');
    }

    public function companyBills(): HasMany
    {
        return $this->hasMany(SalesCompanyBill::class, 'billing_period_id');
    }
}