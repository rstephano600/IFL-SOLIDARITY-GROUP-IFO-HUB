<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* ---------------------------------------------------------- */
/* app/Models/SalaryCalcl.php                                 */
/* ---------------------------------------------------------- */
class SalaryCalcl extends Model
{
    use HasFactory;
    protected $table = 'salary_calcls';

    protected $fillable = [
        'Paid_id', 'GrsAmount', 'Status', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'GrsAmount' => 'decimal:6',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Paid_id');
    }
}


