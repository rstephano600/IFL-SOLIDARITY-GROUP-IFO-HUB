<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPaid extends Model
{
    use HasFactory;

    protected $table = 'salary_paids';

    protected $fillable = [

        'Employee_id',
        'User_id',

        'AmountPaid',
        'ActualGross',
        'NetPay',

        'Allowance',
        'Overtime',

        'Advance',
        'OvtmAdvn',
        'Heslb',
        'Absent',
        'Bcabd',

        'EmpNssf',
        'NssfPay',
        'Paye',
        'SdlPay',
        'WcfPay',

        'PayMode',

        'PaidMonth',
        'PayrollYear',
        'PaidDate',
        'NextPaidDate',

        'Status',
        'Conditions',
        'ActionPay',

        'ApprovalStatus',
        'PaymentStatus',

        'HrManager',
        'HrDirector',
        'FinManger',
        'DafComnt',
        'MdComnt',

        'HrManagerComnt',
        'HrDirectorComnt',
        'FinMangerComnt',
        'DafComntComnt',
        'MdComntComnt',

        'PayrollComment',

        'AuditingStatus',
        'ReportStatus',
    ];

    protected $casts = [
        'PaidDate' => 'date',
        'NextPaidDate' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}