<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPaid extends Model
{
    use HasFactory;
    protected $table = 'salary_paids';

    protected $fillable = [
        'Employee_id', 'ActualGross', 'Allowance', 'Overtime', 'OvtmAdvn',
        'Heslb', 'Absent', 'Bcabd', 'Advance', 'AmountPaid', 'EmpNssf',
        'Paye', 'NetPay', 'SdlPay', 'NssfPay', 'WcfPay', 'PaidMonth','PayrollYear',
        'BatchCode', 'DatePaid', 'User_id', 'Condition', 'Status',
    ];

    protected $casts = [
        'ActualGross' => 'decimal:6',
        'Allowance'   => 'decimal:6',
        'Overtime'    => 'decimal:6',
        'OvtmAdvn'    => 'decimal:6',
        'Heslb'       => 'decimal:6',
        'Absent'      => 'decimal:6',
        'Bcabd'       => 'decimal:6',
        'Advance'     => 'decimal:6',
        'AmountPaid'  => 'decimal:6',
        'EmpNssf'     => 'decimal:6',
        'Paye'        => 'decimal:6',
        'NetPay'      => 'decimal:6',
        'SdlPay'      => 'decimal:6',
        'NssfPay'     => 'decimal:6',
        'WcfPay'      => 'decimal:6',
        'DatePaid'    => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}
