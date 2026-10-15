<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesCustomer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales_customers';

    protected $fillable = [
        'ReferenceNo',
        'customer_code',
        'customer_name',
        'customer_type',
        'billing_company_id',
        'contact_person',
        'phone',
        'email',
        'address',
        'credit_limit',
        'payment_terms_days',
        'company_id',
        'branch_id',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
        'created_by',   // Add this
        'updated_by',   // Add this
        'deleted_by',   // Add this
    ];

    protected $attributes = [
        'customer_type' => 'INDIVIDUAL',
        'credit_limit' => 0.00,
        'payment_terms_days' => 0,
        'Status' => 'Active',
        'AuditingStatus' => 'Pending',
        'ReportStatus' => 'Pending',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'payment_terms_days' => 'integer',
        'billing_company_id' => 'integer',
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

    public function billingCompany(): BelongsTo
    {
        return $this->belongsTo(BillingCompany::class, 'billing_company_id');
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

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }

    public function companyBills(): HasMany
    {
        return $this->hasMany(SalesCompanyBill::class, 'customer_id');
    }
}