<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImprestCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'group_center_id',
        'amount_requested',
        'ofppt',
        'amount_declined',
        'loan_declined',
        'amount_advanced',
        'total_preclosure',
        'loans_appr',
        'amount_disbursed',
        'ppt_disbursed',
        'amount_refunded',
        'amount_paid',
        'fine_or_ppfees',
        'collected_preclosure',
        'preclosure_fees',
        'date_filled',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_filled' => 'date',
    ];

    // Relationships
    public function loan()
    {
        return $this->belongsTo(ClientLoan::class, 'loan_id');
    }

    public function groupCenter()
    {
        return $this->belongsTo(GroupCenter::class);
    }
}
