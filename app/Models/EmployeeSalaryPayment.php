<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_salary_id',
        'employee_id',
        'payment_date',
        'amount_paid',
        'insurance_amount',
        'nssf',
        'tax',
        'currency',
        'payment_method',
        'reference_number',
        'attachment',
        'notes',
        'created_by',
        'updated_by',
        'status',
        'employee_acknowledged',
        'employee_signature',
        'employee_signed_at',
    ];
    /**
     * Relationships
     */
    public function employeeSalary()
    {
        return $this->belongsTo(EmployeeSalary::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
        public function salaryLevel()
    {
        return $this->belongsTo(SalaryLevel::class);
    }
    public function isSigned()
{
    return $this->employee_acknowledged && $this->employee_signature;
}

}
