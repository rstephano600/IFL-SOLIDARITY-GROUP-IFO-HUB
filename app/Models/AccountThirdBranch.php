<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountThirdBranch extends Model
{
    protected $table = 'account_third_branches';

    protected $fillable = [
        'SecondRoot_id',
        'ThirdAccountCode',
        'ThirdAccountName',
        'Category',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    public function secondBranch()
    {
        return $this->belongsTo(AccountSecondBranch::class, 'SecondRoot_id');
    }

    public function fourthBranches()
    {
        return $this->hasMany(AccountFourthBranch::class, 'ThirdRoot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}