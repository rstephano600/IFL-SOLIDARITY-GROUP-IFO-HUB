<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'group_center_id',
        'payment_number',
        'payment_date',
        'payment_amount',
        'currency',
        'payment_method',
        'principal_amount', // Allocated Principal component of the payment
        'interest_amount',  // Allocated Interest component of the payment
        'fees_amount',      // Allocated Fees component of the payment
        'penalty_amount',   // Allocated Penalty component of the payment
        'transaction_reference',
        'bank_name',
        'account_number',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'payment_amount' => 'decimal:2',
        'principal_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        // ... all other decimal fields
    ];

    /**
     * Get the loan this payment was applied to.
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Get the group center context where the payment was recorded.
     */
    public function groupCenter(): BelongsTo
    {
        return $this->belongsTo(GroupCenter::class);
    }

    /**
     * Get the schedule items this payment covered (Many-to-Many).
     */
    public function scheduleItems(): BelongsToMany
    {
        return $this->belongsToMany(RepaymentSchedule::class, 'payment_schedule_allocations', 'payment_id', 'repayment_schedule_id')
                    ->withPivot(['amount_applied', 'principal_applied', 'interest_applied', 'fees_applied', 'penalty_applied']);
    }
}