<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcurementType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'procurement_types';

    protected $fillable = [
        'procurement_code',
        'procurement_name',
        'description',

        'requires_request',
        'requires_quotation',
        'requires_quotation_evaluation',
        'requires_purchase_order',
        'requires_receiving',
        'requires_inspection',
        'requires_invoice',
        'requires_payment_approval',

        'creates_stock',
        'creates_expense',
        'creates_asset',

        'requires_budget',
        'requires_approval',

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

    protected $casts = [
        'requires_request' => 'boolean',
        'requires_quotation' => 'boolean',
        'requires_quotation_evaluation' => 'boolean',
        'requires_purchase_order' => 'boolean',
        'requires_receiving' => 'boolean',
        'requires_inspection' => 'boolean',
        'requires_invoice' => 'boolean',
        'requires_payment_approval' => 'boolean',

        'creates_stock' => 'boolean',
        'creates_expense' => 'boolean',
        'creates_asset' => 'boolean',

        'requires_budget' => 'boolean',
        'requires_approval' => 'boolean',

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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Product Relationship
    |--------------------------------------------------------------------------
    */

    public function products()
    {
        return $this->hasMany(
            Product::class,
            'procurement_type_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('Status', 'Active');
    }

    public function scopeInactive($query)
    {
        return $query->where('Status', '!=', 'Active');
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function requiresFullProcurement(): bool
    {
        return $this->requires_quotation
            || $this->requires_quotation_evaluation
            || $this->requires_purchase_order
            || $this->requires_inspection;
    }

    public function isQuickPurchase(): bool
    {
        return !$this->requires_quotation
            && !$this->requires_quotation_evaluation
            && !$this->requires_purchase_order;
    }

    public function createsInventory(): bool
    {
        return $this->creates_stock;
    }

    public function createsAsset(): bool
    {
        return $this->creates_asset;
    }

    public function createsExpense(): bool
    {
        return $this->creates_expense;
    }

    public function workflowSteps()
    {
        return $this->hasMany(
            ProcurementWorkflowStep::class,
            'procurement_type_id'
        )->orderBy('step_order');
    }
}