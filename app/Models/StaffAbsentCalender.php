<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAbsentCalender extends Model
{
    use HasFactory;
    protected $table = 'staff_absent_calenders';

    protected $fillable = [
        'Emp_id', 'AbsentDate', 'AbsentStatus', 'created_by',
    ];

    protected $casts = [
        'AbsentDate' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Emp_id');
    }
}
