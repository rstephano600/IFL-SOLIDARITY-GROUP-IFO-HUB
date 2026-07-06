<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanCategory extends Model
{
    use HasFactory;

    protected $table = 'loan_categories';

    protected $fillable = [
        'name',
        'amount_disbursed',
        'insurance_fee',
        'officer_visit_fee',
        'interest_rate',
        'interest_amount',
        'repayment_frequency',
        'total_days_due',
        'max_term_days',
        'max_term_months',
        'principal_due',
        'interest_due',
        'currency',
        'conditions',
        'descriptions',
        'is_active',
        'is_new_client',
        'created_by',
        'updated_by',

        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    protected $casts = [
        'amount_disbursed' => 'decimal:2',
        'insurance_fee' => 'decimal:2',
        'officer_visit_fee' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'interest_due' => 'decimal:2',
        'is_active' => 'boolean',
        'is_new_client' => 'boolean',
    ];

    protected $appends = ['repayable_amount', 'total_due'];

    public function getRepayableAmountAttribute()
    {
        return $this->amount_disbursed + $this->interest_amount;
    }

    public function getTotalDueAttribute()
    {
        return ($this->principal_due ?? 0) + ($this->interest_due ?? 0);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    public function creator()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    // If LoanCategory has many loans
    public function loans()
    {
        return $this->hasMany(Loan::class, 'loan_category_id');
    }
}

