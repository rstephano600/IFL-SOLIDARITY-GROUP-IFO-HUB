<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'group_center_id',
        'loan_number',
        'amount_requested',
        'payable_frequency',
        'repayment_frequency',
        'amount_disbursed',
        'loan_fee',
        'other_fee',
        'interest_rate',
        'interest_amount',
        'amount_paid',
        'outstanding_balance',
        'total_preclosure',
        'penalty_fee',
        'start_date',
        'end_date',
        'days_left',
        'closed_at',
        'closure_reason',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

      protected $casts = [
        'amount_requested' => 'decimal:2',
        'payable_frequency' => 'decimal:2',
        'amount_disbursed' => 'decimal:2',
        'loan_fee' => 'decimal:2',
        'other_fee' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'total_preclosure' => 'decimal:2',
        'penalty_fee' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'closed_at' => 'datetime',
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function groupCenter()
    {
        return $this->belongsTo(GroupCenter::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function imprestCertificates()
    {
        return $this->hasMany(ImprestCertificate::class, 'loan_id');
    }

    public function dailyCollections()
    {
        return $this->hasMany(DailyCollection::class, 'client_loan_id');
    }

    // Status scope
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'completed');
    }

    public function isClosed(): bool
    {
        return $this->closed_at !== null;
    }

    public function collections()
    {
        return $this->hasMany(DailyCollection::class, 'client_loan_id');
    }
    public function photos()
{
    return $this->hasMany(ClientLoanPhoto::class);
}


    // Accessors for virtual columns
    public function getTotalPayableAttribute()
    {
        return $this->amount_disbursed + $this->interest_amount + $this->other_fee + $this->loan_fee;
    }

    public function getTotalAmountPaidAttribute()
    {
        return $this->penalty_fee + $this->amount_paid + $this->total_preclosure;
    }

    public function getProfitLossAmountAttribute()
    {
        return $this->getTotalAmountPaidAttribute() - $this->amount_disbursed;
    }

}
