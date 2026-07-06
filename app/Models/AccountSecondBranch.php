<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountSecondBranch extends Model
{
    protected $table = 'account_second_branches';

    protected $fillable = [
        'FirstRoot_id',
        'SecondAccountCode',
        'SecondAccountName',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    public function firstBranch()
    {
        return $this->belongsTo(AccountFirstBranch::class, 'FirstRoot_id');
    }

    public function thirdBranches()
    {
        return $this->hasMany(AccountThirdBranch::class, 'SecondRoot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}