<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales_types';

    protected $fillable = [
        'ReferenceNo',
        'type_code',
        'type_name',
        'description',
        'requires_customer',
        'requires_employee',
        'default_payment_terms',
        'allows_discount',
        'display_order',
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
        'requires_customer' => false,
        'requires_employee' => false,
        'default_payment_terms' => 'IMMEDIATE',
        'allows_discount' => true,
        'display_order' => 0,
        'Status' => 'Active',
        'AuditingStatus' => 'Pending',
        'ReportStatus' => 'Pending',
    ];

    protected $casts = [
        'requires_customer' => 'boolean',
        'requires_employee' => 'boolean',
        'allows_discount' => 'boolean',
        'display_order' => 'integer',
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
        return $this->hasMany(Sale::class, 'sales_type_id');
    }
}