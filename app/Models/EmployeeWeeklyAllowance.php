<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeWeeklyAllowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'week_start',
        'week_end',
        'allowance_type',
        'amount',
        'currency',
        'description',
        'attachment',
        'status',
        'payment_date',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['week_start', 'week_end', 'payment_date'];

    // Relationships
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
}
