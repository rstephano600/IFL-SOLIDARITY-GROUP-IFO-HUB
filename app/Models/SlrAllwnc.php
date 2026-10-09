<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlrAllwnc extends Model
{
    use HasFactory;
    protected $table = 'slr_allwncs';

    protected $fillable = [
        'Paid_id', 'AllwAmount', 'PayStaus', 'created_by',
    ];

    protected $casts = [
        'AllwAmount' => 'decimal:6',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'Paid_id');
    }
}