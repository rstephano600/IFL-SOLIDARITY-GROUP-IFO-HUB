<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'group_center_id',
        'client_id',
        'client_code',
        'group_id',
        'client_type',
        'business_name',
        'business_capital',
        'business_income',
        'business_location',
        'partner_in_business',
        'business_registration_number',
        'tax_identification_number',
        'industry_sector',
        'years_in_business',
        'months_in_business',
        'number_of_employees',
        'alternative_phone',
        'address_line1',
        'address_line2',
        'city',
        'state_province',
        'postal_code',
        'country',
        'credit_score',
        'credit_rating',
        'risk_category',
        'status',
        'blacklist_reason',
        'credit_officer_id',
        'kyc_completed',
        'kyc_completed_at',

        // NEW FIELDS BELOW
        'national_id',
        'gender',
        'marital_status',
        'spouse_name',
        'other_name',
        'date_of_birth',
        'is_street_leader',
        'profile_picture', 
        'sign_image', 
    ];

        protected $casts = [
        'created_at' => 'date',
        'date_of_birth' => 'date',

    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    public function loanOfficer()
    {
        return $this->belongsTo(Employee::class, 'credit_officer_id');
    }
    // app/Models/Client.php
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function groupCenter()
    {
        return $this->belongsTo(GroupCenter::class, 'group_center_id');
    }

    // app/Models/Client.php
    public function assignedLoanOfficer()
    {
        return $this->belongsTo(Employee::class, 'credit_officer_id');
    }

    public function documents()
    {
        return $this->hasMany(ClientDocument::class, 'client_id');
    }

    public function financialInfo()
    {
        return $this->hasMany(ClientFinancialInfo::class, 'client_id');
    }
    public function guarantor()
    {
        return $this->hasOne(ClientGuarantor::class, 'client_id');
    }
    public function loanPhotos()
    {
        return $this->hasMany(ClientLoanPhoto::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'client_id');
    }

    public function penalties()
    {
        return $this->hasMany(
            LoanPenalty::class,
            'client_id'
        );
    }
    
    public function loanTopups()
    {
        return $this->hasMany(
            LoanTopup::class,
            'client_id'
        );
    }
}
