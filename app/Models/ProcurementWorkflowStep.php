<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcurementWorkflowStep extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'procurement_workflow_steps';

    protected $fillable = [
        'procurement_type_id',

        'step_code',
        'step_name',
        'step_order',
        'description',

        'is_required',
        'is_active',
        'can_skip',
        'is_final_step',

        'requires_approval',
        'requires_attachment',
        'requires_comment',

        'assigned_role',

        'view_permission',
        'approval_permission',
        'rejection_permission',
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
        'procurement_type_id' => 'integer',
        'step_order'          => 'integer',

        'is_required'         => 'boolean',
        'is_active'           => 'boolean',
        'can_skip'            => 'boolean',
        'is_final_step'       => 'boolean',

        'requires_approval'   => 'boolean',
        'requires_attachment' => 'boolean',
        'requires_comment'    => 'boolean',

        'company_id'          => 'integer',
        'branch_id'           => 'integer',
        'User_id'             => 'integer',

        'created_by'          => 'integer',
        'updated_by'          => 'integer',
        'deleted_by'          => 'integer',

        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
        'deleted_at'          => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function procurementType(): BelongsTo
    {
        return $this->belongsTo(ProcurementType::class, 'procurement_type_id');
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

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('Status', 'Active')
                     ->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('step_order');
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function requiresApproval(): bool
    {
        return $this->requires_approval;
    }

    public function allowsSkip(): bool
    {
        return $this->can_skip;
    }

    public function isFinalStep(): bool
    {
        return $this->is_final_step;
    }
}