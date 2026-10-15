<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCatalog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_catalog';

    protected $fillable = [
        'ReferenceNo',
        'service_category_id',
        'service_code',
        'service_name',
        'description',
        'default_unit',
        'default_unit_price',
        'requires_product',
        'requires_attachment',
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
        'default_unit_price' => 'decimal:2',
        'requires_product' => 'boolean',
        'requires_attachment' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            ServiceCategory::class,
            'service_category_id'
        );
    }

    public function requestItems(): HasMany
    {
        return $this->hasMany(
            ServiceRequestItem::class,
            'service_catalog_id'
        );
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
