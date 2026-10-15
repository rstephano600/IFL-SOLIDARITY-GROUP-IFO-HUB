<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommitteePosition extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'committee_positions';

    protected $fillable = [
        'ReferenceNo',
        'position_code',
        'position_name',
        'description',
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

    public function committeeMembers(): HasMany
    {
        return $this->hasMany(
            CommitteeMember::class,
            'committee_position_id'
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
