<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'supplier_invoice_items';

    protected $fillable = [
        'supplier_invoice_id',
        'purchase_order_item_id',
        'goods_receipt_item_id',
        'product_id',
        'unit_of_measure_id',
        'description',
        'quantity',
        'unit_price',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'total_amount',
    ];

    protected $casts = [
        'supplier_invoice_id' => 'integer',
        'purchase_order_item_id' => 'integer',
        'goods_receipt_item_id' => 'integer',
        'product_id' => 'integer',
        'unit_of_measure_id' => 'integer',
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function supplierInvoice()
    {
        return $this->belongsTo(
            SupplierInvoice::class,
            'supplier_invoice_id'
        );
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(
            PurchaseOrderItem::class,
            'purchase_order_item_id'
        );
    }

    public function goodsReceiptItem()
    {
        return $this->belongsTo(
            GoodsReceiptItem::class,
            'goods_receipt_item_id'
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
}