<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'Status'
    ];

    // 🔗 Users who have this permission
    public function users()
    {
        return $this->belongsToMany(User::class, 'permission_users', 'permission_id', 'User_id')
            ->withPivot([
                'id',
                'Creater_id',
                'Status',
                'duration',
                'start_date',
                'end_date'
            ])
            ->withTimestamps();
    }
}