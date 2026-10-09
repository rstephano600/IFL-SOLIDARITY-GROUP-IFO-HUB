<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_order_items';

    protected $fillable = [
        'purchase_order_id',
        'supplier_quotation_item_id',
        'product_id',
        'unit_of_measure_id',
        'description',
        'ordered_quantity',
        'unit_price',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'received_quantity',
        'pending_quantity',
        'item_status',
        'remarks',
    ];

    protected $casts = [
        'purchase_order_id' => 'integer',
        'supplier_quotation_item_id' => 'integer',
        'product_id' => 'integer',
        'unit_of_measure_id' => 'integer',
        'ordered_quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'received_quantity' => 'decimal:3',
        'pending_quantity' => 'decimal:3',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(
            PurchaseOrder::class,
            'purchase_order_id'
        );
    }

    public function quotationItem()
    {
        return $this->belongsTo(
            SupplierQuotationItem::class,
            'supplier_quotation_item_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function unitOfMeasure()
    {
        return $this->belongsTo(
            UnitOfMeasure::class,
            'unit_of_measure_id'
        );
    }

    public function goodsReceiptItems()
    {
        return $this->hasMany(
            GoodsReceiptItem::class,
            'purchase_order_item_id'
        );
    }

    public function invoiceItems()
    {
        return $this->hasMany(
            SupplierInvoiceItem::class,
            'purchase_order_item_id'
        );
    }
}