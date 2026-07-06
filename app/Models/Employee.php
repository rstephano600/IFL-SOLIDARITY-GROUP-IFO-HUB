<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';

    protected $fillable = [
        'Employee_id',
        'EmployeeID',
        'date_of_birth',
        'marital_status',
        'nida',
        'tribe',
        'religion',
        'address',
        'education_level',
        'position',
        'department',
        'date_of_hire',
        'is_active',
        'profile_picture',
        'cv',
        'other_information',
        'created_by',
        'updated_by',
        'basic_salary',
        'weekly_allowance_amount',

        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'date_of_birth' => 'date',
        'date_of_hire' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'Employee_id');
    }
     public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function nextOfKins()
    {
        return $this->hasMany(NextOfKin::class);
    }
    public function nextOfKin()
    {
        return $this->hasOne(NextOfKin::class);
    }
    public function referees()
    {
        return $this->hasMany(Referee::class);
    }


    // Helper: Full Name
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function groupCenters()
    {
        return $this->hasMany(GroupCenter::class, 'loan_officer_id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'loan_officer_id');
    }


    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    public function getYearsOfServiceAttribute()
    {
        return $this->date_of_hire ? $this->date_of_hire->diffInYears(now()) : 0;
    }

    public function getProfilePictureUrlAttribute()
    {
        return $this->profile_picture 
            ? asset('storage/' . $this->profile_picture) 
            : asset('images/default-avatar.png');
    }

    public function getCvUrlAttribute()
    {
        return $this->cv ? asset('storage/' . $this->cv) : null;
    }

    public function shareholder()
    {
    return $this->hasOne(Shareholder::class);
    }

        public function salaries()
    {
        return $this->hasMany(EmployeeSalary::class);
    }


}
