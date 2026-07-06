<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guarantor extends Model
{
    use HasFactory;

    protected $table = 'guarantors';

    protected $fillable = [

        'guarantor_number',

        'first_name',
        'middle_name',
        'last_name',

        'gender',

        'phone_number',
        'alternative_phone',

        'nida_number',

        'email',

        'occupation',

        'physical_address',

        'relationship_with_client',

        'remarks',

        'User_id',

        'Status',
        'AuditingStatus',
        'ReportStatus'

    ];

    protected $appends = [

        'full_name'

    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute()
    {
        return trim(
            ($this->first_name ?? '') . ' ' .
            ($this->middle_name ?? '') . ' ' .
            ($this->last_name ?? '')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'User_id'
        );
    }

    public function loanGuarantors()
    {
        return $this->hasMany(
            LoanGuarantor::class,
            'guarantor_id'
        );
    }
}