<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_number',
        'expense_title',
        'description',
        'expense_date',
        'total_amount',
        'currency',
        'amount_paid',
        'expense_category_id',
        'attachment',

        'AppStatus',
        'approved_at',
        'approved_by',
        'PaymentStatus',
        'paid_at',
        'paid_by',
        'payment_method',
        'reference_number',
        'descriptions',

        'created_by',
        'updated_by',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
    ];

    // Automatically set created_by and updated_by
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function items()
    {
        return $this->hasMany(
            ExpenseItem::class,
            'expense_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'User_id'
        );
    }

    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    public function updater()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function payer()
    {
        return $this->belongsTo(
            User::class,
            'paid_by'
        );
    }

    public function payments()
    {
        return $this->hasMany(
            ExpensePayment::class,
            'expense_id'
        );
    }
}
