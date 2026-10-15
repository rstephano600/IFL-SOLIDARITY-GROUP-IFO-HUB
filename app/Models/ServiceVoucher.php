<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceVoucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_vouchers';

    protected $fillable = [
        'ReferenceNo',
        'voucher_no',
        'service_request_id',
        'service_money_request_id',
        'voucher_type',
        'voucher_date',
        'payee_name',
        'description',
        'voucher_amount',
        'currency_code',
        'voucher_status',
        'prepared_by',
        'approved_by',
        'approved_at',
        'financial_posting_status',
        'financial_transaction_reference',
        'posted_by',
        'posted_at',
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

    protected $casts = [
        'voucher_date' => 'date',
        'approved_at' => 'datetime',
        'posted_at' => 'datetime',
        'voucher_amount' => 'decimal:2',
    ];

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(
            ServiceRequest::class,
            'service_request_id'
        );
    }

    public function moneyRequest(): BelongsTo
    {
        return $this->belongsTo(
            ServiceMoneyRequest::class,
            'service_money_request_id'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            ServiceVoucherItem::class,
            'service_voucher_id'
        );
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
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
}
