<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    protected $fillable = [
        'member_code',
        'member_name',
        'member_category_id',
        'member_id',
        'company_id',
        'branch_id',
        'nida',
        'tin',
        'work_permit',
        'admission_date',
        'profile_picture',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'admission_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Member Category
    public function memberCategory()
    {
        return $this->belongsTo(MemberCategory::class, 'member_category_id');
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

    // Linked User Account
    public function memberUser()
    {
        return $this->belongsTo(User::class, 'member_id');
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