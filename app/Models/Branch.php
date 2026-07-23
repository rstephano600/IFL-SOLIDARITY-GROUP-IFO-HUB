<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branchies';

    protected $fillable = [
        'branch_code',
        'branch_name',
        'company_id',
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

    // Branch belongs to a company
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    // User responsible for the branch
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
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

    // Departments
    public function departments()
    {
        return $this->hasMany(Department::class, 'branch_id');
    }

    // Cost Centres
    public function costCentres()
    {
        return $this->hasMany(CostCentre::class, 'branch_id');
    }

    // Business Codes
    public function businessCodes()
    {
        return $this->hasMany(CompanyBusinessCode::class, 'branch_id');
    }

    // Member Categories
    public function memberCategories()
    {
        return $this->hasMany(MemberCategory::class, 'branch_id');
    }

    // Members
    public function members()
    {
        return $this->hasMany(Member::class, 'branch_id');
    }

    public function membershipFeeSchedules()
    {
        return $this->hasMany(MembershipFeeSchedule::class, 'branch_id');
    }

    public function membershipFeePayments()
    {
        return $this->hasMany(MembershipFeePayment::class, 'branch_id');
    }

    public function socialContributionSchedules()
    {
        return $this->hasMany(SocialContributionSchedule::class, 'branch_id');
    }

    public function socialContributions()
    {
        return $this->hasMany(SocialContribution::class, 'branch_id');
    }

    public function sharePurchaseTransactions()
    {
        return $this->hasMany(
            SharePurchaseTransaction::class,
            'branch_id'
        );
    }
}