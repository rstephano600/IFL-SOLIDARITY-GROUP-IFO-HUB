<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAdvncSlry extends Model
{
    use HasFactory;
    protected $table = 'staff_advnc_slries';

    protected $fillable = [
        'Emp_id', 'AdvanceAmount', 'PaidAmount', 'InstallAmount', 'AdvStatus', 'created_by',
    ];

    protected $casts = [
        'AdvanceAmount' => 'decimal:6',
        'PaidAmount'    => 'decimal:6',
        'InstallAmount' => 'decimal:6',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Emp_id');
    }
}
