<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountBusiness extends Model
{
    protected $table = 'account_businesses';

    protected $fillable = [
        'Country_id',
        'BusinessCode',
        'BusinessName',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    public function country()
    {
        return $this->belongsTo(AccountCountry::class, 'Country_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}