<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'purchase_requests';

    protected $fillable = [
        'request_no',

        'procurement_type_id',
        'requester_id',

        'company_id',
        'branch_id',
        'User_id',

        'request_date',
        'required_date',

        'priority',

        'purpose',
        'justification',

        'budget_amount',
        'estimated_total',
        'approved_total',

        'currency_code',

        'current_step_id',

        'Status',
        'AuditingStatus',
        'ReportStatus',

        'submitted_at',
        'approved_at',
        'rejected_at',
        'completed_at',

        'approval_comment',
        'rejection_reason',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'procurement_type_id' => 'integer',
        'requester_id' => 'integer',

        'company_id' => 'integer',
        'branch_id' => 'integer',
        'User_id' => 'integer',

        'current_step_id' => 'integer',

        'request_date' => 'date',
        'required_date' => 'date',

        'budget_amount' => 'decimal:2',
        'estimated_total' => 'decimal:2',
        'approved_total' => 'decimal:2',

        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',

        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function procurementType()
    {
        return $this->belongsTo(
            ProcurementType::class,
            'procurement_type_id'
        );
    }

    public function requester()
    {
        return $this->belongsTo(
            User::class,
            'requester_id'
        );
    }

    public function currentStep()
    {
        return $this->belongsTo(
            ProcurementWorkflowStep::class,
            'current_step_id'
        );
    }

    public function items()
    {
        return $this->hasMany(
            PurchaseRequestItem::class,
            'purchase_request_id'
        );
    }

    public function company()
    {
        return $this->belongsTo(
            Company::class,
            'company_id'
        );
    }

    public function branch()
    {
        return $this->belongsTo(
            Branch::class,
            'branch_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'User_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updater()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function deleter()
    {
        return $this->belongsTo(
            User::class,
            'deleted_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeDraft($query)
    {
        return $query->where('Status', 'Draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('Status', 'Submitted');
    }

    public function scopeApproved($query)
    {
        return $query->where('Status', 'Approved');
    }

    public function scopePending($query)
    {
        return $query->whereIn('Status', [
            'Submitted',
            'In Progress',
            'Pending Approval'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->Status === 'Draft';
    }

    public function isApproved(): bool
    {
        return $this->Status === 'Approved';
    }

    public function isRejected(): bool
    {
        return $this->Status === 'Rejected';
    }

    public function isCompleted(): bool
    {
        return $this->Status === 'Completed';
    }

    public function calculateEstimatedTotal(): float
    {
        return (float) $this->items()->sum('estimated_total');
    }

    public function calculateApprovedTotal(): float
    {
        return (float) $this->items()->sum('approved_total');
    }
}