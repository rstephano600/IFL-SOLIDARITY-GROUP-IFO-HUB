<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionUser extends Model
{
    protected $table = 'permission_users';

    protected $fillable = [
        'User_id',
        'permission_id',
        'Creater_id',
        'Status',
        'duration',
        'start_date',
        'end_date'
    ];

    // 🔗 Permission
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    // 🔗 User who owns permission
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    // 🔗 Creator (who assigned permission)
    public function creator()
    {
        return $this->belongsTo(User::class, 'Creater_id');
    }

    // ✅ Check if permission is active
    public function isActive()
    {
        if ($this->Status !== 'Active') {
            return false;
        }

        if ($this->duration === 'Temporary') {
            $today = now()->toDateString();

            return (!$this->start_date || $today >= $this->start_date) &&
                   (!$this->end_date || $today <= $this->end_date);
        }

        return true;
    }
}