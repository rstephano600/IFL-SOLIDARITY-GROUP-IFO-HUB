<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $table = 'groups';

    protected $fillable = [
        'group_center_id',
        'group_code',
        'group_name',
        'group_type',
        'location',
        'description',
        'credit_officer_id',
        'registration_date',
        'is_active',
        'created_by',
        'updated_by',

        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

        protected $casts = [
        'is_active' => 'boolean',
        'registration_date' => 'date',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function creditOfficer()
    {
        return $this->belongsTo(Employee::class, 'credit_officer_id');
    }

    // Relationships
    public function groupCenter()
    {
        return $this->belongsTo(GroupCenter::class, 'group_center_id');
    }


    public function members()
    {
    return $this->hasMany(GroupMember::class);
    }


    // Belongs to one loan officer (Employee)
    public function loanOfficer()
    {
        return $this->belongsTo(Employee::class, 'credit_officer_id');
    }

    // Has many clients

public function center()
    {
        return $this->belongsTo(GroupCenter::class, 'group_center_id');
    }
public function loanTopups()
{
    return $this->hasMany(
        LoanTopup::class,
        'group_id'
    );
}

}
