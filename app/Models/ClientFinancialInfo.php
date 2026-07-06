<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientFinancialInfo extends Model
{
    use HasFactory;

    protected $table = 'client_financial_info';

    protected $fillable = [
        'client_id',
        'annual_revenue',
        'monthly_revenue',
        'revenue_currency',
        'monthly_expenses',
        'total_assets',
        'total_liabilities',
        'net_worth',
        'bank_name',
        'bank_account_number',
        'average_bank_balance',
        'existing_loans_count',
        'total_existing_debt',
        'monthly_debt_obligations',
        'debt_to_income_ratio',
        'debt_service_coverage_ratio',
        'verified',
        'verified_by_user_id',
        'verified_at',
        'financial_year',
        'as_of_date',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'verified_at' => 'datetime',
        'as_of_date' => 'date',
        'annual_revenue' => 'decimal:2',
        'monthly_revenue' => 'decimal:2',
        'monthly_expenses' => 'decimal:2',
        'total_assets' => 'decimal:2',
        'total_liabilities' => 'decimal:2',
        'net_worth' => 'decimal:2',
        'average_bank_balance' => 'decimal:2',
        'total_existing_debt' => 'decimal:2',
        'monthly_debt_obligations' => 'decimal:2',
        'debt_to_income_ratio' => 'decimal:2',
        'debt_service_coverage_ratio' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    /**
     * Derived Calculations
     */
    public function getDebtToIncomeRatioAttribute($value)
    {
        if (!$value && $this->monthly_revenue > 0) {
            return round(($this->monthly_debt_obligations / $this->monthly_revenue) * 100, 2);
        }
        return $value;
    }

    public function getDebtServiceCoverageRatioAttribute($value)
    {
        if (!$value && $this->monthly_expenses > 0) {
            return round(($this->monthly_revenue / $this->monthly_expenses), 2);
        }
        return $value;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }
}
