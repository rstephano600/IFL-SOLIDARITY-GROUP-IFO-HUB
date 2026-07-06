<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRefund extends Model
{
    use HasFactory;

    protected $table = 'loan_refunds';

    protected $fillable = [

        // References
        'loan_id',
        'client_id',
        'group_center_id',
        'group_id',

        // Refund Information
        'refund_number',
        'refund_date',

        'requested_refund',
        'approved_refund',
        'refunded_amount',

        // Refund Components
        'membership_fee_refund',
        'insurance_fee_refund',
        'officer_visit_fee_refund',
        'other_fee_refund',
        'penalty_fee_refund',
        'preclosure_fee_refund',

        'total_refund',

        'refund_reason',
        'remarks',

        'ApprovalStatus',

        'approved_by',
        'created_by',
        'updated_by',

        'User_id',

        'Status',
        'AuditingStatus',
        'ReportStatus',
    ];

    protected $casts = [

        'refund_date' => 'date',

        'requested_refund' => 'decimal:2',
        'approved_refund' => 'decimal:2',
        'refunded_amount' => 'decimal:2',

        'membership_fee_refund' => 'decimal:2',
        'insurance_fee_refund' => 'decimal:2',
        'officer_visit_fee_refund' => 'decimal:2',
        'other_fee_refund' => 'decimal:2',
        'penalty_fee_refund' => 'decimal:2',
        'preclosure_fee_refund' => 'decimal:2',

        'total_refund' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function loan()
    {
        return $this->belongsTo(Loan::class);
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
        return $this->belongsTo(User::class, 'User_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}