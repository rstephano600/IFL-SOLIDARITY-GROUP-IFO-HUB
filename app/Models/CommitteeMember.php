<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommitteeMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'committee_members';

    protected $fillable = [
        'ReferenceNo',
        'committee_id',
        'member_id',
        'committee_position_id',
        'appointment_date',
        'end_date',
        'membership_status',
        'appointment_notes',
        'company_id',
        'branch_id',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'end_date' => 'date',
    ];

    public function committee(): BelongsTo
    {
        return $this->belongsTo(
            Committee::class,
            'committee_id'
        );
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(
            Member::class,
            'member_id'
        );
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(
            CommitteePosition::class,
            'committee_position_id'
        );
    }

    public function responsibilities(): HasMany
    {
        return $this->hasMany(
            CommitteeMemberResponsibility::class,
            'committee_member_id'
        );
    }

    public function activeResponsibilities(): HasMany
    {
        return $this->responsibilities()
            ->where('Status', 'Active');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}
