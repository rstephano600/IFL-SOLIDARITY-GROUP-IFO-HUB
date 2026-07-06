<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountFirstBranch extends Model
{
    protected $table = 'account_first_branches';

    protected $fillable = [
        'AccountRoot_id',
        'FirstAccountCode',
        'FirstAccountName',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    public function root()
    {
        return $this->belongsTo(AccountRoot::class, 'AccountRoot_id');
    }

    public function secondBranches()
    {
        return $this->hasMany(AccountSecondBranch::class, 'FirstRoot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}