<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier_payments';

    protected $fillable = [
        'payment_no',
        'supplier_id',
        'supplier_invoice_id',
        'payment_date',
        'payment_amount',
        'currency_code',
        'payment_method',
        'payment_reference',
        'bank_account',
        'cheque_no',
        'payment_status',
        'approval_status',
        'requested_by',
        'approved_by',
        'approved_at',
        'paid_by',
        'paid_at',
        'comment',
        'attachment_path',
        'company_id',
        'branch_id',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'supplier_id' => 'integer',
        'supplier_invoice_id' => 'integer',
        'payment_amount' => 'decimal:2',
        'payment_date' => 'date',
        'requested_by' => 'integer',
        'approved_by' => 'integer',
        'approved_at' => 'datetime',
        'paid_by' => 'integer',
        'paid_at' => 'datetime',
        'company_id' => 'integer',
        'branch_id' => 'integer',
        'User_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(
            Supplier::class,
            'supplier_id'
        );
    }

    public function supplierInvoice()
    {
        return $this->belongsTo(
            SupplierInvoice::class,
            'supplier_invoice_id'
        );
    }

    public function requestedBy()
    {
        return $this->belongsTo(
            User::class,
            'requested_by'
        );
    }

    public function approvedBy()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    public function paidBy()
    {
        return $this->belongsTo(
            User::class,
            'paid_by'
        );
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

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

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}