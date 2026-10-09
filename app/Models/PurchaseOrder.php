<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'purchase_order_no',
        'purchase_request_id',
        'supplier_award_id',
        'supplier_id',
        'order_date',
        'expected_delivery_date',
        'currency_code',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'order_status',
        'payment_terms',
        'delivery_terms',
        'notes',
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
        'purchase_request_id' => 'integer',
        'supplier_award_id' => 'integer',
        'supplier_id' => 'integer',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
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

    public function purchaseRequest()
    {
        return $this->belongsTo(
            PurchaseRequest::class,
            'purchase_request_id'
        );
    }

    public function supplierAward()
    {
        return $this->belongsTo(
            SupplierAward::class,
            'supplier_award_id'
        );
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(
            PurchaseOrderItem::class,
            'purchase_order_id'
        );
    }

    public function goodsReceipts()
    {
        return $this->hasMany(
            GoodsReceipt::class,
            'purchase_order_id'
        );
    }

    public function invoices()
    {
        return $this->hasMany(
            SupplierInvoice::class,
            'purchase_order_id'
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
}