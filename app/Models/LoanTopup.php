<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanTopup extends Model
{
    use HasFactory;

    protected $table = 'loan_topups';

    protected $fillable = [

        'old_loan_id',
        'new_loan_id',

        'client_id',
        'group_center_id',
        'group_id',

        'requested_amount',
        'approved_amount',
        'amount_disbursed',

        'outstanding_principal',
        'outstanding_interest',
        'outstanding_penalty',
        'outstanding_other_fee',

        'total_outstanding',

        'topup_fee',

        'total_deductions',

        'net_disbursed',

        'remaining_installments',

        'topup_reason',
        'remarks',

        'topup_date',

        'ApprovalStatus',
        'approved_by',
        'created_by',
        'updated_by',

        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    protected $casts = [

        'requested_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'amount_disbursed' => 'decimal:2',

        'outstanding_principal' => 'decimal:2',
        'outstanding_interest' => 'decimal:2',
        'outstanding_penalty' => 'decimal:2',
        'outstanding_other_fee' => 'decimal:2',

        'total_outstanding' => 'decimal:2',

        'topup_fee' => 'decimal:2',

        'total_deductions' => 'decimal:2',

        'net_disbursed' => 'decimal:2',

        'topup_date' => 'date'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function oldLoan()
    {
        return $this->belongsTo(Loan::class,'old_loan_id');
    }

    public function newLoan()
    {
        return $this->belongsTo(Loan::class,'new_loan_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function groupCenter()
    {
        return $this->belongsTo(GroupCenter::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'User_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class,'approved_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
}