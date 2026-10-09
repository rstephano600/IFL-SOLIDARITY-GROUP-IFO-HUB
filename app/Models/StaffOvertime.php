<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffOvertime extends Model
{
    use HasFactory;
    protected $table = 'staff_overtimes';

    protected $fillable = [
        'Emp_id', 'PayAmnt', 'AppStatus', 'PayStatus',
        'AdvanceStatus', 'AdvanceCondition', 'PaidMonth', 'created_by', 'Status'
    ];

    protected $casts = [
        'PayAmnt' => 'decimal:6',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Emp_id');
    }

    public function overtPay()
    {
        return $this->hasOne(OvertPay::class, 'Ovrt_id');
    }
}
