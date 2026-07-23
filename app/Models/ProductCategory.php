<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_categories';

    protected $fillable = [
        'CompanyBusinessCode_id',
        'category_code',
        'category_name',
        'description',
        'display_order',

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
        'display_order' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Business Code
    public function business()
    {
        return $this->belongsTo(CompanyBusinessCode::class, 'CompanyBusinessCode_id');
    }

    // Company
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    // Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    // User who owns the record
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    // Created By
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Updated By
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Deleted By
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }


}