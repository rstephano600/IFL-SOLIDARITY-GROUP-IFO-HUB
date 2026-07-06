<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    use HasFactory;

    protected $table = 'loan_repayments';

    protected $fillable = [

        'loan_id',
        'client_id',

        'payment_date',

        'amount_paid',

        'principal_paid',
        'interest_paid',

        'penalty_paid',

        'payment_method',
        'reference_number',

        'remarks',

        'received_by',

        'User_id',

        'Status',
        'AuditingStatus',
        'ReportStatus'

    ];

    protected $casts = [

        'payment_date' => 'date',

        'amount_paid' => 'decimal:2',

        'principal_paid' => 'decimal:2',

        'interest_paid' => 'decimal:2',

        'penalty_paid' => 'decimal:2',

    ];

    protected $appends = [

        'total_paid_amount'

    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getTotalPaidAmountAttribute()
    {
        return
            ($this->principal_paid ?? 0)
            +
            ($this->interest_paid ?? 0)
            +
            ($this->penalty_paid ?? 0);
    }

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

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'User_id'
        );
    }

    public function receiver()
    {
        return $this->belongsTo(
            User::class,
            'received_by'
        );
    }
}