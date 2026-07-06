<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_loan_id',
        'group_center_id',
        'date_of_payment',
        'amount_paid',
        'total_preclosure',
        'penalty_fee',
        'first_date_pay',
        'last_date_pay',
        'payment_method',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_payment' => 'date',
        'first_date_pay' => 'boolean',
        'last_date_pay' => 'boolean',
    ];

    // Relationships
    public function clientLoan()
    {
        return $this->belongsTo(ClientLoan::class, 'client_loan_id');
    }

    public function groupCenter()
    {
        return $this->belongsTo(GroupCenter::class);
    }
    public function loan()
{
    return $this->belongsTo(ClientLoan::class, 'client_loan_id');
}

}
