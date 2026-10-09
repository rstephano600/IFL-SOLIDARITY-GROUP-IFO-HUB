<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayrollBatch extends Model
{
    use HasFactory;
    protected $table = 'payroll_batches';

    protected $fillable = [
        'BatchCode', 'PayrollMonth', 'BatchStatus', 'SnapshotJson', 'User_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    public function tempSalaries()
    {
        return $this->hasMany(TempSalary::class, 'BatchCode', 'BatchCode');
    }
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'SubmittedBy',
            'id'
        );
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'ApprovedBy',
            'id'
        );
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'RejectedBy',
            'id'
        );
    }
}