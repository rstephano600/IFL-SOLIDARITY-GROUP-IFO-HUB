<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceMoneyRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_money_requests';

    protected $fillable = [
        'ReferenceNo',
        'money_request_no',
        'service_request_id',
        'money_request_date',
        'payee_name',
        'payment_purpose',
        'amount_requested',
        'amount_approved',
        'amount_processed',
        'currency_code',

        // Cashier preparation
        'prepared_by',
        'prepared_at',
        'cashier_comment',

        // Finance Committee Chairman approval
        'finance_chairman_approval_status',
        'finance_chairman_approved_by',
        'finance_chairman_approved_at',
        'finance_chairman_approval_comment',

        // Group Chairman approval
        'group_chairman_approval_status',
        'group_chairman_approved_by',
        'group_chairman_approved_at',
        'group_chairman_approval_comment',

        // Money processing
        'money_status',
        'payment_method',
        'payment_reference',
        'processed_by',
        'processed_at',

        // Financial posting
        'financial_posting_status',
        'financial_posted_by',
        'financial_posted_at',
        'financial_transaction_reference',

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
        'money_request_date' => 'date',
        'prepared_at' => 'datetime',
        'finance_chairman_approved_at' => 'datetime',
        'group_chairman_approved_at' => 'datetime',
        'processed_at' => 'datetime',
        'financial_posted_at' => 'datetime',
        'amount_requested' => 'decimal:2',
        'amount_approved' => 'decimal:2',
        'amount_processed' => 'decimal:2',
    ];

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(
            ServiceRequest::class,
            'service_request_id'
        );
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function financeChairmanApprover(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'finance_chairman_approved_by'
        );
    }

    public function groupChairmanApprover(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'group_chairman_approved_by'
        );
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function financialPostedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'financial_posted_by'
        );
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(
            ServiceVoucher::class,
            'service_money_request_id'
        );
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
