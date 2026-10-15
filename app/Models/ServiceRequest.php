<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_requests';

    protected $fillable = [
        'ReferenceNo',
        'request_no',
        'request_date',
        'required_date',
        'requester_id',
        'committee_id',
        'service_category_id',
        'committee_name',
        'committee_chairman_id',
        'request_title',
        'purpose',
        'beneficiary_name',
        'priority',
        'estimated_total',
        'requested_currency',
        'request_status',
        'committee_chairman_approval_status',
        'committee_chairman_approved_by',
        'committee_chairman_approved_at',
        'committee_chairman_approval_comment',
        'group_chairman_approval_status',
        'group_chairman_approved_by',
        'group_chairman_approved_at',
        'group_chairman_approval_comment',
        'money_status',
        'money_requested_total',
        'money_approved_total',
        'money_processed_total',
        'completed_at',
        'cancellation_reason',
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
        'request_date' => 'date',
        'required_date' => 'date',
        'committee_chairman_approved_at' => 'datetime',
        'group_chairman_approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'estimated_total' => 'decimal:2',
        'money_requested_total' => 'decimal:2',
        'money_approved_total' => 'decimal:2',
        'money_processed_total' => 'decimal:2',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function committee(): BelongsTo
    {
        return $this->belongsTo(Committee::class, 'committee_id');
    }

    public function committeeChairman(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'committee_chairman_id'
        );
    }

    public function committeeApprover(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'committee_chairman_approved_by'
        );
    }

    public function groupChairmanApprover(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'group_chairman_approved_by'
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            ServiceRequestItem::class,
            'service_request_id'
        );
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(
            ServiceRequestAttachment::class,
            'service_request_id'
        );
    }

    public function moneyRequests(): HasMany
    {
        return $this->hasMany(
            ServiceMoneyRequest::class,
            'service_request_id'
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

    public function serviceCategory()
    {
        return $this->belongsTo(
            ServiceCategory::class,
            'service_category_id'
        );
    }
}
