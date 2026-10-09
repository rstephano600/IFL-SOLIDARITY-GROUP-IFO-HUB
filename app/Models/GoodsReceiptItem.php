<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceiptItem extends Model
{
    use HasFactory;

    protected $table = 'goods_receipt_items';

    protected $fillable = [
        'goods_receipt_id',
        'purchase_order_item_id',
        'product_id',
        'unit_of_measure_id',
        'ordered_quantity',
        'received_quantity',
        'accepted_quantity',
        'rejected_quantity',
        'rejection_reason',
        'item_status',
        'remarks',
    ];

    protected $casts = [
        'goods_receipt_id' => 'integer',
        'purchase_order_item_id' => 'integer',
        'product_id' => 'integer',
        'unit_of_measure_id' => 'integer',
        'ordered_quantity' => 'decimal:3',
        'received_quantity' => 'decimal:3',
        'accepted_quantity' => 'decimal:3',
        'rejected_quantity' => 'decimal:3',
    ];

    public function goodsReceipt()
    {
        return $this->belongsTo(
            GoodsReceipt::class,
            'goods_receipt_id'
        );
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(
            PurchaseOrderItem::class,
            'purchase_order_item_id'
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

    public function invoiceItems()
    {
        return $this->hasMany(
            SupplierInvoiceItem::class,
            'goods_receipt_item_id'
        );
    }
}