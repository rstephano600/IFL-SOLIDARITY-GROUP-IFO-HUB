<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesCompanyBillItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales_company_bill_items';

    protected $fillable = [
        'ReferenceNo',
        'company_bill_id',
        'sale_id',
        'shift_record_id',
        'consumption_record_id',
        'description',
        'quantity',
        'unit_price',
        'amount',
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
        'quantity' => 1.000,
        'unit_price' => 0.00,
        'amount' => 0.00,
        'Status' => 'Active',
        'AuditingStatus' => 'Pending',
        'ReportStatus' => 'Pending',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'amount' => 'decimal:2',
        'company_bill_id' => 'integer',
        'sale_id' => 'integer',
        'shift_record_id' => 'integer',
        'consumption_record_id' => 'integer',
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

    public function companyBill(): BelongsTo
    {
        return $this->belongsTo(SalesCompanyBill::class, 'company_bill_id');
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function shiftRecord(): BelongsTo
    {
        return $this->belongsTo(StaffMealShiftRecord::class, 'shift_record_id');
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