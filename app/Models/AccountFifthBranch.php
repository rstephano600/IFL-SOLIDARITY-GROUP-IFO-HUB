<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AccountFifthBranch extends Model
{
    protected $table = 'account_fifth_branches';

    protected $fillable = [
        'FourthRoot_id',
        'FifthAccountCode',
        'FifthAccountName',
        'Category',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    public function fourthBranch()
    {
        return $this->belongsTo(AccountFourthBranch::class, 'FourthRoot_id');
    }

    public function sixthBranches()
    {
        return $this->hasMany(AccountSixthBranch::class, 'FifthRoot_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}
