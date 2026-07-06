<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Loan extends Model
{
    use HasFactory;

    protected $table = 'loans';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'group_center_id',
        'group_id',
        'client_id',
        'collection_officer_id',
        'loan_category_id',

        // Core Loan Information
        'loan_number',
        'application_date',
        'amount_requested',
        'client_payable_frequency',

        // approval and fees
        'amount_disbursed',
        'membership_fee',
        'insurance_fee',
        'officer_visit_fee',
        'other_fee',
        'penalty_fee',
        'preclosure_fee',

        // Interest and Terms
        'interest_rate',
        'interest_amount',

        'repayment_frequency',
        'max_term_days',
        'max_term_months',
        'total_days_due',
        'principal_due',
        'interest_due',
        'disbursement_date',
        'total_due',

        // repayments
        'amount_paid',
        'membership_fee_paid',
        'officer_visit_fee_paid',
        'insurance_fee_paid',
        'preclosure_fee_paid',
        'penalty_fee_paid',
        'other_fee_paid',

       // Date tracking and outstanding balance
        'start_date',
        'end_date',
        'days_left',

        // closure
        'amount_with_preclosure',
        'closed_at',
        'closure_reason',

        // Refunding reason
        'amount_with_refund',
        'refunded_at',
        'refunding_reason',
        'refunded_by',
        'RefundStatus',

        // totals
        'total_fees',
        'total_repayable',
        'total_amount_paid',
        'outstanding_balance',
        'total_profit',

        // system fields
        'currency',
        'ApprovalRemarks',
        'ApprovalStatus',
        'CloseStatus',
        'RejectReasons',
        'RefundStatus',
        'created_by',
        'approved_at',
        'approved_by',
        'updated_by',
        'is_active',
        'is_new_client',
        'RepaymentStatus',
        'LoanStatus',

        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'amount_requested' => 'decimal:2',
        'amount_disbursed' => 'decimal:2',
        'client_payable_frequency' => 'decimal:2',
        'membership_fee' => 'decimal:2',
        'insurance_fee' => 'decimal:2',
        'officer_visit_fee' => 'decimal:2',
        'other_fee' => 'decimal:2',
        'penalty_fee' => 'decimal:2',
        'preclosure_fee' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'principal_due' => 'decimal:2',
        'interest_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'penalty_fee_paid' => 'decimal:2',
        'preclosure_fee_paid' => 'decimal:2',
        'other_fee_paid' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'is_active' => 'boolean',
        'is_new_client' => 'boolean',
        'disbursement_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'closed_at' => 'datetime',
        'created_at' => 'date',
        'refunded_at' => 'date',
    ];


    /**
     * Define relationships.
     */

    // 🔗 Client who took the loan
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    // 🔗 Loan category (product)
    public function loanCategory()
    {
        return $this->belongsTo(LoanCategory::class);
    }

    // 🔗 Group or center relationships
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function groupCenter()
    {
        return $this->belongsTo(GroupCenter::class, 'group_center_id');
    }
    public function collectionOfficer()
    {
        return $this->belongsTo(Employee::class, 'collection_officer_id');
    }

    // 🔗 Creator / approver users
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
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function updater()
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }
    public function repaymentSchedules()
    {
        return $this->hasMany(RepaymentSchedule::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function refundeddBy()
    {
        return $this->belongsTo(User::class, 'refunded_by');
    }
    public function penalties()
    {
        return $this->hasMany(
            LoanPenalty::class,
            'loan_id'
        );
    }

    /**
     * 🔢 Accessors & Calculations
     */

    // Automatically get total fees (even though it's virtual in DB)
    public function getTotalFeeAttribute()
    {
        return ($this->membership_fee ?? 0)
             + ($this->insurance_fee ?? 0)
             + ($this->officer_visit_fee ?? 0)
             + ($this->other_fee ?? 0)
             + ($this->penalty_fee ?? 0)
             + ($this->preclosure_fee ?? 0);
    }

    public function getTotalDueAttribute()
    {
        return ($this->principal_due ?? 0) + ($this->interest_due ?? 0);
    }

    public function getRepayableAmountAttribute()
    {
        return ($this->amount_disbursed ?? 0)
             + ($this->interest_amount ?? 0);
    }

    public function getTotalAmountPaidAttribute()
    {
        return ($this->membership_fee_paid ?? 0)
             + ($this->officer_visit_fee_paid ?? 0)
             + ($this->insurance_fee_paid ?? 0)
             + ($this->penalty_fee_paid ?? 0)
             + ($this->preclosure_fee_paid ?? 0)
             + ($this->amount_with_preclosure ?? 0)
             + ($this->amount_paid ?? 0)
             + ($this->other_fee_paid ?? 0);
    }

    public function getProfitLossAmountAttribute()
    {
        return $this->total_amount_paid
             - $this->amount_with_refund
             - $this->amount_disbursed;
    }

    public function getOutstandingBalanceAttribute()
    {
    return max(0, $this->repayable_amount - $this->total_amount_paid );
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    /**
     * Loan that originated from this loan.
     */
    public function topupHistory()
    {
        return $this->hasMany(
            LoanTopup::class,
            'old_loan_id'
        );
    }

    /**
     * If this loan was created from another loan.
     */
    public function topupSource()
    {
        return $this->hasOne(
            LoanTopup::class,
            'new_loan_id'
        );
    }
}