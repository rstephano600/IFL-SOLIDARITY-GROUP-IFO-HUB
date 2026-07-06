<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanGuarantor extends Model
{
    use HasFactory;

    protected $table = 'loan_guarantors';

    protected $fillable = [

        'loan_id',

        'client_id',

        'guarantor_id',

        'guarantee_amount',

        'relationship_type',

        'remarks',

        'User_id',

        'Status',
        'AuditingStatus',
        'ReportStatus'

    ];

    protected $casts = [

        'guarantee_amount' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function loan()
    {
        return $this->belongsTo(
            Loan::class,
            'loan_id'
        );
    }

    public function client()
    {
        return $this->belongsTo(
            Client::class,
            'client_id'
        );
    }

    public function guarantor()
    {
        return $this->belongsTo(
            Guarantor::class,
            'guarantor_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'User_id'
        );
    }
}