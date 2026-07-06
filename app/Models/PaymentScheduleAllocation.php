<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Note: You only need this model if you intend to query the pivot table directly.
// Otherwise, the BelongsToMany relationships in Loan and RepaymentSchedule are enough.
class PaymentScheduleAllocation extends Model
{
    use HasFactory;

    protected $table = 'payment_schedule_allocations';

    // Disable timestamps for the pivot table as they weren't defined in the migration
    public $timestamps = false;
    
    // The pivot table uses a composite key, so we disable auto-incrementing primary key
    public $incrementing = false;

    // Define the primary key as the composite key
    protected $primaryKey = ['payment_id', 'repayment_schedule_id'];

    protected $fillable = [
        'payment_id',
        'repayment_schedule_id',
        'amount_applied',
        'principal_applied',
        'interest_applied',
        'fees_applied',
        'penalty_applied',
    ];

    protected $casts = [
        'amount_applied' => 'decimal:2',
        // ... all other applied decimal fields
    ];
}