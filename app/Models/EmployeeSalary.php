<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'salary_level_id',
        'basic_amount',
        'insurance_amount',
        'nssf',
        'tax',
        'net_amount_due',
        'bonus',
        'effective_from',
        'effective_to',
        'attachment',
        'created_by',
        'updated_by',
        'status',
    ];

    /**
     * =============================
     * Relationships
     * =============================
     */

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function salaryLevel()
    {
        return $this->belongsTo(SalaryLevel::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * =============================
     * Accessors / Helpers
     * =============================
     */

    // Auto-calculate total deductions
    public function getTotalDeductionsAttribute()
    {
        return $this->insurance_amount + $this->nssf + $this->tax;
    }

    // Auto-calculate gross (basic + bonus)
    public function getGrossAmountAttribute()
    {
        return $this->basic_amount + $this->bonus;
    }

    // Optionally compute the net amount if not directly stored
    public function getComputedNetAmountAttribute()
    {
        return $this->gross_amount - $this->total_deductions;
    }
}
