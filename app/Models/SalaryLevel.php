<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'basic_amount',
        'insurance_amount',
        'nssf',
        'tax',
        'net_amount_due',
        'description',
        'currency',
        'created_by',
        'updated_by',
        'status',
    ];

    /**
     * Creator relationship
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Updater relationship
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
