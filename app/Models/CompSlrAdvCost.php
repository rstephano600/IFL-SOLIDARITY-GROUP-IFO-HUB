<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompSlrAdvCost extends Model
{
    use HasFactory;
    protected $table = 'comp_slr_adv_costs';

    protected $fillable = [
        'AdvName', 'Amount', 'Status', 'created_by',
    ];

    protected $casts = [
        'Amount' => 'decimal:6',
    ];
}