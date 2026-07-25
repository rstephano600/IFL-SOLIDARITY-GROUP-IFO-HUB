<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharePurchaseTransaction extends Model
{
    use HasFactory;

    protected $table = 'share_purchase_transactions';

    protected $fillable = [
        'TransactionRefNo',
        'member_id',
        'share_offering_id',
        'share_type_id',
        'TransactionType',
        'SharesQuantity',
        'PricePerShare',
        'TotalAmount',
        'TransactionDate',
        'PaymentMethod',
        'PaymentReference',
        'related_transfer_id',
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

        // Accounting Posting
        'posting_status',
        'posted_by',
        'posted_at',
        'posting_reference',
        'journal_no',
        'journal_id',

        // GL Account Codes
        'cash_account_id',
        'cash_account_code',
        'share_capital_account_id',
        'share_capital_account_code',
        'membership_fee_account_code',
        'social_contribution_account_code',

        // Share Certificate
        'certificate_status',
        'certificate_generated_at',
        'certificate_generated_by',
        'certificate_id',

        // Overall Transaction Status
        'transaction_status',
        'company_id',
        'branch_id',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
    ];

    protected $casts = [
        'SharesQuantity' => 'decimal:2',
        'PricePerShare' => 'decimal:2',
        'TransactionDate' => 'date',

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

    public function shareOffering()
    {
        return $this->belongsTo(ShareOffering::class, 'share_offering_id');
    }

    public function shareType()
    {
        return $this->belongsTo(ShareType::class, 'share_type_id');
    }

    public function transfer()
    {
        return $this->belongsTo(ShareTransfer::class, 'related_transfer_id');
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

    public function certificateGenerator()
    {
        return $this->belongsTo(User::class, 'certificate_generated_by');
    }

    public function certificate()
    {
        return $this->belongsTo(ShareCertificate::class, 'certificate_id');
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'journal_id');
    }
}