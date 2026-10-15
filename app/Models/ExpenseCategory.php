<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_code',

        'name',
        'description',

        'company_id',
        'branch_id',
        'User_id',
        'created_by',
        'updated_by',

        'Status',
        'AuditingStatus',
        'ReportStatus',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    // Relationships
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

}
