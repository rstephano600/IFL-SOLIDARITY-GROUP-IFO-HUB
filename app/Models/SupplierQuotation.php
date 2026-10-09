<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierQuotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier_quotations';

    protected $fillable = [
        'quotation_no',
        'tender_id',
        'purchase_request_id',
        'supplier_id',
        'supplier_quotation_no',
        'quotation_date',
        'valid_until',
        'currency_code',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'delivery_amount',
        'total_amount',
        'quotation_status',
        'payment_terms',
        'delivery_terms',
        'notes',
        'attachment_path',
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
        'purchase_request_id' => 'integer',
        'supplier_id' => 'integer',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'delivery_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'quotation_date' => 'date',
        'valid_until' => 'date',
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

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(SupplierQuotationItem::class, 'supplier_quotation_id');
    }

    public function evaluations()
    {
        return $this->hasMany(QuotationEvaluation::class, 'supplier_quotation_id');
    }

    public function awards()
    {
        return $this->hasMany(SupplierAward::class, 'supplier_quotation_id');
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