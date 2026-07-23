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

    public function branches()
    {
        return $this->hasMany(Branch::class, 'company_id');
    }

    // Departments
    public function departments()
    {
        return $this->hasMany(Department::class, 'company_id');
    }

    // Cost Centres
    public function costCentres()
    {
        return $this->hasMany(CostCentre::class, 'company_id');
    }

    // Business Codes
    public function businessCodes()
    {
        return $this->hasMany(CompanyBusinessCode::class, 'company_id');
    }

    // Member Categories
    public function memberCategories()
    {
        return $this->hasMany(MemberCategory::class, 'company_id');
    }

    // Members
    public function members()
    {
        return $this->hasMany(Member::class, 'company_id');
    }

    public function membershipFeeSchedules()
    {
        return $this->hasMany(MembershipFeeSchedule::class, 'company_id');
    }

    public function membershipFeePayments()
    {
        return $this->hasMany(MembershipFeePayment::class, 'company_id');
    }

    public function socialContributionSchedules()
    {
        return $this->hasMany(SocialContributionSchedule::class, 'company_id');
    }

    public function socialContributions()
    {
        return $this->hasMany(SocialContribution::class, 'company_id');
    }

    public function sharePurchaseTransactions()
    {
        return $this->hasMany(
            SharePurchaseTransaction::class,
            'company_id'
        );
    }
}