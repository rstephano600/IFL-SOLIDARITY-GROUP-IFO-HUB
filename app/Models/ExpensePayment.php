<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ExpensePayment extends Model
{
    use HasFactory;

    protected $table = 'expense_payments';

    protected $fillable = [

        'expense_id',

        'payment_number',

        'payment_date',

        'amount_paid',

        'payment_method',

        'reference_number',

        'descriptions',

        'paid_by',

        'User_id',

        'Status',

        'AuditingStatus',

        'ReportStatus'
    ];

    protected $casts = [

        'payment_date'=>'date',

        'amount_paid'=>'decimal:2'

    ];

    public function expense()
    {
        return $this->belongsTo(
            Expense::class,
            'expense_id'
        );
    }

    public function payer()
    {
        return $this->belongsTo(
            User::class,
            'paid_by'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'User_id'
        );
    }
}
