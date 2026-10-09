<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAbsent extends Model
{
    use HasFactory;
    protected $table = 'staff_absents';

    protected $fillable = [
        'Emp_id', 'DedactAmount', 'AbsentStatus', 'created_by',
    ];

    protected $casts = [
        'DedactAmount' => 'decimal:6',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Emp_id');
    }
}

