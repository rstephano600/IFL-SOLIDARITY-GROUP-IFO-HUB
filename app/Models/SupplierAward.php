<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierAward extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier_awards';

    protected $fillable = [
        'award_no',
        'tender_id',
        'supplier_id',
        'supplier_quotation_id',
        'purchase_request_id',
        'award_date',
        'award_amount',
        'currency_code',
        'award_status',
        'award_comment',
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

    protected $casts = [
        'tender_id' => 'integer',
        'supplier_id' => 'integer',
        'supplier_quotation_id' => 'integer',
        'purchase_request_id' => 'integer',
        'award_amount' => 'decimal:2',
        'award_date' => 'date',
        'approved_by' => 'integer',
        'approved_at' => 'datetime',
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

    public function tender()
    {
        return $this->belongsTo(SupplierTender::class, 'tender_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function quotation()
    {
        return $this->belongsTo(
            SupplierQuotation::class,
            'supplier_quotation_id'
        );
    }

    public function purchaseRequest()
    {
        return $this->belongsTo(
            PurchaseRequest::class,
            'purchase_request_id'
        );
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
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

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_award_id');
    }
}