<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffMealRate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'staff_meal_rates';

    protected $fillable = [
        'ReferenceNo',
        'meal_type',
        'shift_id',
        'unit_price',
        'effective_from',
        'effective_until',
        'approved_by',
        'approved_at',
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
        'unit_price' => 0.00,
        'Status' => 'Active',
        'AuditingStatus' => 'Pending',
        'ReportStatus' => 'Pending',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'effective_from' => 'date',
        'effective_until' => 'date',
        'approved_at' => 'datetime',
        'shift_id' => 'integer',
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

    public function shift(): BelongsTo
    {
        return $this->belongsTo(StaffMealShift::class, 'shift_id');
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
}