<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;
    protected $table = 'group_members';

    protected $fillable = [
        'group_id',
        'client_id',
        'employee_id',
        'member_code',
        'role_in_group',
        'created_by',
        'updated_by',

        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

