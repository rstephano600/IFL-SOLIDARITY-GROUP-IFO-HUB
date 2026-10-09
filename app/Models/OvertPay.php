<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertPay extends Model
{
    use HasFactory;
    protected $table = 'overt_pays';

    protected $fillable = [
        'Ovrt_id', 'Emp_id', 'User_id', 'AmntPay', 'DatePaid', 'CashOutStatus', 'Condition',
    ];

    protected $casts = [
        'AmntPay'  => 'decimal:6',
        'DatePaid' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Emp_id');
    }

    public function overtime()
    {
        return $this->belongsTo(StaffOvertime::class, 'Ovrt_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}
