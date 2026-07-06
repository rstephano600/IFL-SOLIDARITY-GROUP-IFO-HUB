<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'company_code',
        'company_name',
        'company_type',
        'parent_company_id',
        'description',
        'address',
        'region',
        'district',
        'ward',
        'village',
        'phone',
        'email',
        'established_date',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'established_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // User who owns the company
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    // Parent company
    public function parentCompany()
    {
        return $this->belongsTo(Company::class, 'parent_company_id');
    }

    // Child companies
    public function childCompanies()
    {
        return $this->hasMany(Company::class, 'parent_company_id');
    }

    // Creator
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Last updater
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}