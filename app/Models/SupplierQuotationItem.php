<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierQuotationItem extends Model
{

    use HasFactory;

    protected $table = 'supplier_quotation_items';

    protected $fillable = [
        'supplier_quotation_id',
        'purchase_request_item_id',
        'product_id',
        'unit_of_measure_id',
        'description',
        'quantity',
        'unit_price',
        'discount_amount',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'delivery_date',
        'item_status',
        'remarks',
    ];

    protected $casts = [
        'supplier_quotation_id' => 'integer',
        'purchase_request_item_id' => 'integer',
        'product_id' => 'integer',
        'unit_of_measure_id' => 'integer',
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'delivery_date' => 'date',
    ];

    public function quotation()
    {
        return $this->belongsTo(
            SupplierQuotation::class,
            'supplier_quotation_id'
        );
    }

    public function purchaseRequestItem()
    {
        return $this->belongsTo(
            PurchaseRequestItem::class,
            'purchase_request_item_id'
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