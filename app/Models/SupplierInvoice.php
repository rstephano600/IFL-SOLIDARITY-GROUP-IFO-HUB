<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier_invoices';

    protected $fillable = [
        'invoice_no',
        'supplier_invoice_no',
        'supplier_id',
        'purchase_order_id',
        'goods_receipt_id',
        'invoice_date',
        'due_date',
        'currency_code',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'outstanding_amount',
        'invoice_status',
        'verification_status',
        'verified_by',
        'verified_at',
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
        'supplier_id' => 'integer',
        'purchase_order_id' => 'integer',
        'goods_receipt_id' => 'integer',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'outstanding_amount' => 'decimal:2',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'verified_by' => 'integer',
        'verified_at' => 'datetime',
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

    public function supplier()
    {
        return $this->belongsTo(
            Supplier::class,
            'supplier_id'
        );
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(
            PurchaseOrder::class,
            'purchase_order_id'
        );
    }

    public function goodsReceipt()
    {
        return $this->belongsTo(
            GoodsReceipt::class,
            'goods_receipt_id'
        );
    }

    public function items()
    {
        return $this->hasMany(
            SupplierInvoiceItem::class,
            'supplier_invoice_id'
        );
    }

    public function payments()
    {
        return $this->hasMany(
            SupplierPayment::class,
            'supplier_invoice_id'
        );
    }

    public function verifier()
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
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