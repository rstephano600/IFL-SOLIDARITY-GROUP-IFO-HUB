<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'department_code',
        'department_name',
        'company_id',
        'branch_id',
        'function',
        'descriptions',
        'User_id',
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

    // User
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    // Creator
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Updater
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Cost Centres
    public function costCentres()
    {
        return $this->hasMany(CostCentre::class, 'department_id');
    }
}