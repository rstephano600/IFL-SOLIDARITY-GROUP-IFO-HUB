<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequestWorkflow extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'purchase_request_workflows';

    protected $fillable = [

        // Purchase Request
        'purchase_request_id',

        // Workflow Step
        'workflow_step_id',

        // Workflow Status / Action
        'purchase_status',
        'action',

        // Users
        'assigned_to',
        'acted_by',

        // Workflow Dates
        'started_at',
        'acted_at',
        'completed_at',

        // Workflow Movement
        'previous_step_id',
        'next_step_id',

        // Comments / Decisions
        'comment',
        'rejection_reason',

        // Attachment
        'attachment_path',

        // Organization
        'company_id',
        'branch_id',
        'User_id',

        // System Status
        'Status',
        'AuditingStatus',
        'ReportStatus',

        // Audit Users
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [

        'purchase_request_id' => 'integer',
        'workflow_step_id' => 'integer',

        'assigned_to' => 'integer',
        'acted_by' => 'integer',

        'previous_step_id' => 'integer',
        'next_step_id' => 'integer',

        'company_id' => 'integer',
        'branch_id' => 'integer',
        'User_id' => 'integer',

        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',

        'started_at' => 'datetime',
        'acted_at' => 'datetime',
        'completed_at' => 'datetime',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];


    // =========================================================
    // PURCHASE REQUEST
    // =========================================================

    public function purchaseRequest()
    {
        return $this->belongsTo(
            PurchaseRequest::class,
            'purchase_request_id'
        );
    }


    // =========================================================
    // CURRENT WORKFLOW STEP
    // =========================================================

    public function workflowStep()
    {
        return $this->belongsTo(
            ProcurementWorkflowStep::class,
            'workflow_step_id'
        );
    }


    // =========================================================
    // PREVIOUS WORKFLOW STEP
    // =========================================================

    public function previousStep()
    {
        return $this->belongsTo(
            ProcurementWorkflowStep::class,
            'previous_step_id'
        );
    }


    // =========================================================
    // NEXT WORKFLOW STEP
    // =========================================================

    public function nextStep()
    {
        return $this->belongsTo(
            ProcurementWorkflowStep::class,
            'next_step_id'
        );
    }


    // =========================================================
    // ASSIGNED USER
    // =========================================================

    public function assignedTo()
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }


    // =========================================================
    // ACTED BY
    // =========================================================

    public function actedBy()
    {
        return $this->belongsTo(
            User::class,
            'acted_by'
        );
    }


    // =========================================================
    // COMPANY
    // =========================================================

    public function company()
    {
        return $this->belongsTo(
            Company::class,
            'company_id'
        );
    }


    // =========================================================
    // BRANCH
    // =========================================================

    public function branch()
    {
        return $this->belongsTo(
            Branch::class,
            'branch_id'
        );
    }


    // =========================================================
    // USER
    // =========================================================

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'User_id'
        );
    }


    // =========================================================
    // CREATED BY
    // =========================================================

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    // =========================================================
    // UPDATED BY
    // =========================================================

    public function updater()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }


    // =========================================================
    // DELETED BY
    // =========================================================

    public function deleter()
    {
        return $this->belongsTo(
            User::class,
            'deleted_by'
        );
    }


    // =========================================================
    // SCOPES
    // =========================================================

    public function scopeActive($query)
    {
        return $query->where('Status', 'Active');
    }


    public function scopePending($query)
    {
        return $query->where('purchase_status', 'Pending');
    }


    public function scopeInProgress($query)
    {
        return $query->where('purchase_status', 'In Progress');
    }


    public function scopeCompleted($query)
    {
        return $query->where('purchase_status', 'Completed');
    }


    public function scopeRejected($query)
    {
        return $query->where('purchase_status', 'Rejected');
    }


    // =========================================================
    // STATUS HELPERS
    // =========================================================

    public function isPending(): bool
    {
        return $this->purchase_status === 'Pending';
    }


    public function isInProgress(): bool
    {
        return $this->purchase_status === 'In Progress';
    }


    public function isCompleted(): bool
    {
        return $this->purchase_status === 'Completed';
    }


    public function isRejected(): bool
    {
        return $this->purchase_status === 'Rejected';
    }


    public function isActive(): bool
    {
        return $this->Status === 'Active';
    }


    public function hasBeenActedOn(): bool
    {
        return !is_null($this->acted_by) && !is_null($this->acted_at);
    }


    public function hasNextStep(): bool
    {
        return !is_null($this->next_step_id);
    }


    public function hasPreviousStep(): bool
    {
        return !is_null($this->previous_step_id);
    }
}