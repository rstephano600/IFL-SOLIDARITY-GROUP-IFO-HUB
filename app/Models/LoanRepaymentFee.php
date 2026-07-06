<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRepaymentFee extends Model
{
   use HasFactory;

    protected $table = 'loan_repayment_fees';

    protected $fillable = [

        'loan_id',
        'client_id',
        'payment_date',
        'reference_number',
        'membership_fee_paid',
        'officer_visit_fee_paid',
        'insurance_fee_paid',
        'preclosure_fee_paid',
        'penalty_fee_paid',
        'other_fee_paid',
        'payment_method',
        'remarks',

        'received_by',

        'User_id',

        'Status',
        'AuditingStatus',
        'ReportStatus'

    ];

    protected $casts = [

        'payment_date' => 'date',
        'membership_fee_paid' => 'decimal:2',
        'officer_visit_fee_paid' => 'decimal:2',
        'insurance_fee_paid' => 'decimal:2',
        'preclosure_fee_paid' => 'decimal:2',
        'penalty_fee_paid' => 'decimal:2',
        'other_fee_paid' => 'decimal:2',

    ];


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