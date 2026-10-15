<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Committee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'committees';

    protected $fillable = [
        'ReferenceNo',
        'committee_code',
        'committee_name',
        'description',
        'chairman_user_id',
        'secretary_user_id',
        'start_date',
        'end_date',
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
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function chairman(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'chairman_user_id'
        );
    }

    public function secretary(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'secretary_user_id'
        );
    }

    public function members(): HasMany
    {
        return $this->hasMany(
            CommitteeMember::class,
            'committee_id'
        );
    }

    public function activeMembers(): HasMany
    {
        return $this->members()
            ->where('membership_status', 'Active')
            ->where('Status', 'Active');
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(
            ServiceRequest::class,
            'committee_id'
        );
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
