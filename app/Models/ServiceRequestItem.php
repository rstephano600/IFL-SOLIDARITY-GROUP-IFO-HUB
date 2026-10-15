<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequestItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_request_items';

    protected $fillable = [
        'ReferenceNo',
        'service_request_id',
        'service_catalog_id',
        'product_id',
        'item_type',
        'item_description',
        'quantity',
        'unit_of_measure',
        'unit_price',
        'discount_amount',
        'line_total',
        'notes',
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
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(
            ServiceRequest::class,
            'service_request_id'
        );
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(
            ServiceCatalog::class,
            'service_catalog_id'
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}
