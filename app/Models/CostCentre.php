<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCentre extends Model
{
    use HasFactory;

    protected $table = 'cost_centres';

    protected $fillable = [
        'cost_centre_code',
        'cost_centre_name',
        'department_id',
        'company_id',
        'branch_id',
        'reporting_segment',
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

    // Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
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
}