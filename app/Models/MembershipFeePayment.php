<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipFeePayment extends Model
{
    use HasFactory;

    protected $table = 'membership_fee_payments';

    protected $fillable = [
        'PaymentRefNo',
        'member_id',
        'fee_schedule_id',
        'AmountPaid',
        'PaymentDate',
        'PaymentMethod',
        'PaymentReference',
        'Narration',

        // Verification
        'verification_status',
        'verified_by',
        'verified_at',
        'verification_remarks',

        // Approval
        'approval_status',
        'approved_by',
        'approved_at',
        'approval_remarks',

        // Finance Posting
        'posting_status',
        'posted_by',
        'posted_at',
        'posting_reference',

        // Accounting Integration
        'journal_no',
        'journal_id',
        'cash_account_code',
        'social_contribution_account_code',

        // General Transaction Status
        'transaction_status',

        'company_id',
        'branch_id',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
    ];

    protected $casts = [
        'AmountPaid'  => 'decimal:2',
        'PaymentDate' => 'date',
        
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'posted_at' => 'datetime',
        'certificate_generated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function feeSchedule()
    {
        return $this->belongsTo(MembershipFeeSchedule::class, 'fee_schedule_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function poster()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'journal_id');
    }
}