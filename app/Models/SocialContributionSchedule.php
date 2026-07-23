<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialContributionSchedule extends Model
{
    use HasFactory;

    protected $table = 'social_contributions_schedules';

    protected $fillable = [
        'ScheduleRefNo',
        'FeeAmount',
        'EffectiveFrom',
        'EffectiveTo',
        'Description',
        'company_id',
        'branch_id',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
    ];

    protected $casts = [
        'FeeAmount' => 'decimal:2',
        'EffectiveFrom' => 'date',
        'EffectiveTo' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function contributions()
    {
        return $this->hasMany(SocialContribution::class, 'social_contribution_schedule_id');
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