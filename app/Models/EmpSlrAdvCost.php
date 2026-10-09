<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpSlrAdvCost extends Model
{
    use HasFactory;
    protected $table = 'emp_slr_adv_costs';

    protected $fillable = [
        'Paid_id', 'Amount', 'OutsAmount', 'PaidAmount', 'PaidMonth', 'Status', 'created_by',
    ];

    protected $casts = [
        'Amount'     => 'decimal:6',
        'OutsAmount' => 'decimal:6',
        'PaidAmount' => 'decimal:6',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Paid_id');
    }
}
