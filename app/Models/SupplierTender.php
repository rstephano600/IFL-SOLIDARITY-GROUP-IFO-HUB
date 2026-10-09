<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierTender extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier_tenders';

    protected $fillable = [
        'tender_no',
        'purchase_request_id',
        'tender_title',
        'description',
        'tender_date',
        'closing_date',
        'tender_status',
        'evaluation_method',
        'estimated_budget',
        'currency_code',
        'awarded_supplier_id',
        'awarded_at',
        'award_comment',
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
        'purchase_request_id' => 'integer',
        'awarded_supplier_id' => 'integer',
        'estimated_budget' => 'decimal:2',
        'tender_date' => 'date',
        'closing_date' => 'date',
        'awarded_at' => 'datetime',
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

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    public function awardedSupplier()
    {
        return $this->belongsTo(Supplier::class, 'awarded_supplier_id');
    }

    public function invitations()
    {
        return $this->hasMany(SupplierTenderInvitation::class, 'tender_id');
    }

    public function quotations()
    {
        return $this->hasMany(SupplierQuotation::class, 'tender_id');
    }

    public function awards()
    {
        return $this->hasMany(SupplierAward::class, 'tender_id');
    }

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
}