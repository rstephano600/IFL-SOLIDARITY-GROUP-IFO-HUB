<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCenter extends Model
{
    use HasFactory;
    protected $table = 'group_centers';

    protected $fillable = [
        'center_code',
        'center_name',
        'location',
        'area',
        'description',
        'collection_officer_id',
        'established_date',
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
        'established_date' => 'date',
    ];

    // Relationships
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function loanOfficer()
    {
        return $this->belongsTo(Employee::class, 'loan_officer_id');
    }


    public function groups()
    {
        return $this->hasMany(Group::class, 'group_center_id');
    }


    public function collectionOfficer()
    {
        return $this->belongsTo(Employee::class, 'collection_officer_id');
    }
        public function collection_officer()
    {
        return $this->belongsTo(Employee::class, 'collection_officer_id');
    }

    public function loanTopups()
    {
        return $this->hasMany(
            LoanTopup::class,
            'group_center_id'
        );
    }
}


