<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientGuarantor extends Model
{
    use HasFactory;

    protected $table = 'client_guarantors';

    protected $fillable = [
        'client_id',
        'first_name',
        'last_name',
        'national_id',
        'email',
        'phone_number',
        'address_line1',
        'city',
        'country',
        'occupation',
        'employer',
        'monthly_income',
        'relationship_to_client',
        'credit_score',
        'credit_checked',
        'status',
        'verified',
        'verified_by_user_id',
        'verified_at',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'credit_checked' => 'boolean',
        'verified_at' => 'datetime',
        'monthly_income' => 'decimal:2',
    ];


    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }


    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
