<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountCountry extends Model
{
    protected $table = 'account_countries';

    protected $fillable = [
        'CountryCode',
        'CountryName',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    public function businesses()
    {
        return $this->hasMany(AccountBusines::class, 'Country_id');
    }
}