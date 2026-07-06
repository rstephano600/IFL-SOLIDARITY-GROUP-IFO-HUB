<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountRoot extends Model
{
    protected $table = 'account_roots';

    protected $fillable = [
        'AccountCode',
        'AccountName',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    public function firstBranches()
    {
        return $this->hasMany(AccountFirstBranch::class, 'AccountRoot_id');
    }
}