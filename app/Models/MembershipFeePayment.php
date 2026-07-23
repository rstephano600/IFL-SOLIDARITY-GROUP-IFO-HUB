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
}