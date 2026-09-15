<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BranchScope;

class ProductSubCategory extends Model
{
    use HasFactory;

    protected $table = 'product_subcategories';

    protected $fillable = [

        'company_id',
        'branch_id',
        'User_id',

        'SubCategoryRefNo',

        'category_id',

        'SubCategoryCode',
        'SubCategoryName',
        'Description',

        'Status',
        'AuditingStatus',
        'ReportStatus',

        'created_by',
        'updated_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

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

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}