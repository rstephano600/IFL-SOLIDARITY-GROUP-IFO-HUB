<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_categories';

    protected $fillable = [
        'ReferenceNo',
        'category_code',
        'category_name',
        'description',
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

    public function services(): HasMany
    {
        return $this->hasMany(
            ServiceCatalog::class,
            'service_category_id'
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

    public function serviceRequests()
    {
        return $this->hasMany(
            ServiceRequest::class,
            'service_category_id'
        );
    }
}
