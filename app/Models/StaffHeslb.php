<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffHeslb extends Model
{
    use HasFactory;
    protected $table = 'staff_heslbs';

    protected $fillable = [
        'Emp_id', 'LoanAmount', 'PaidAmount', 'InstallAmount', 'LoanStatus', 'created_by',
    ];

    protected $casts = [
        'LoanAmount'    => 'decimal:6',
        'PaidAmount'    => 'decimal:6',
        'InstallAmount' => 'decimal:6',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Emp_id');
    }
}
