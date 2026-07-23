<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialContribution extends Model
{
    use HasFactory;

    protected $table = 'social_contributions';

    protected $fillable = [
        'social_contribution_schedule_id',
        'ContributionRefNo',
        'member_id',
        'ContributionMonth',
        'ExpectedAmount',
        'AmountPaid',
        'PaymentDate',
        'PaymentMethod',
        'PaymentReference',
        'PaymentStatus',
        'Narration',
        'company_id',
        'branch_id',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
    ];

    protected $casts = [
        'ContributionMonth' => 'date',
        'PaymentDate' => 'date',
        'ExpectedAmount' => 'decimal:2',
        'AmountPaid' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function schedule()
    {
        return $this->belongsTo(
            SocialContributionSchedule::class,
            'social_contribution_schedule_id'
        );
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
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