<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPenalty extends Model
{
    use HasFactory;

    protected $table = 'loan_penalties';

    protected $fillable = [

        'loan_id',
        'client_id',
        'penalty_id',

        'penalty_date',

        'overdue_days',

        'penalty_rate',

        'penalty_amount',

        'payment_status',

        'paid_at',

        'remarks',

        'User_id',

        'Status',
        'AuditingStatus',
        'ReportStatus'

    ];

    protected $casts = [

        'penalty_date' => 'date',

        'paid_at' => 'datetime',

        'penalty_rate' => 'decimal:2',

        'penalty_amount' => 'decimal:2',

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

    public function penaltyCategory()
    {
        return $this->belongsTo(
            LoanPenaltyCategory::class,
            'penalty_id'
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