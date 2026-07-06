<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeeklyAllowance extends Model
{
    use HasFactory;

    protected $table = 'weekly_allowances';

    protected $fillable = [

        'Employee_id',
        'User_id',

        'AllowanceAmount',
        'AmountPaid',

        'WeekNumber',
        'AllowanceMonth',
        'AllowanceYear',

        'GeneratedDate',
        'PaidDate',
        'NextAllowanceDate',

        'PayMode',

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

        'AllowanceComment',

        'AuditingStatus',
        'ReportStatus',
    ];

    protected $casts = [
        'GeneratedDate'     => 'date',
        'PaidDate'          => 'date',
        'NextAllowanceDate' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Employee receiving the weekly allowance.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Employee_id');
    }

    /**
     * User who generated the allowance.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    /**
     * Creator of the record.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    /**
     * Prepared by user.
     */
    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}