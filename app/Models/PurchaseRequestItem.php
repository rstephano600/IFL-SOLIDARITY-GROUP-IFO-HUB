<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_request_items';

    protected $fillable = [
        'purchase_request_id',

        'product_id',
        'unit_of_measure_id',

        'description',

        'requested_quantity',

        'estimated_unit_price',
        'estimated_total',

        'approved_quantity',
        'approved_unit_price',
        'approved_total',

        'item_status',

        'remarks',
    ];

    protected $casts = [
        'purchase_request_id' => 'integer',

        'product_id' => 'integer',
        'unit_of_measure_id' => 'integer',

        'requested_quantity' => 'decimal:3',

        'estimated_unit_price' => 'decimal:2',
        'estimated_total' => 'decimal:2',

        'approved_quantity' => 'decimal:3',
        'approved_unit_price' => 'decimal:2',
        'approved_total' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function purchaseRequest()
    {
        return $this->belongsTo(
            PurchaseRequest::class,
            'purchase_request_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'product_id'
        );
    }

    public function unitOfMeasure()
    {
        return $this->belongsTo(
            UnitOfMeasure::class,
            'unit_of_measure_id'
        );
    }
}