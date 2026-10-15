<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceVoucherItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_voucher_items';

    protected $fillable = [
        'ReferenceNo',
        'service_voucher_id',
        'service_request_item_id',
        'product_id',
        'description',
        'quantity',
        'unit_price',
        'amount',
        'debit_account_id',
        'credit_account_id',
        'notes',
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
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(
            ServiceVoucher::class,
            'service_voucher_id'
        );
    }

    public function serviceRequestItem(): BelongsTo
    {
        return $this->belongsTo(
            ServiceRequestItem::class,
            'service_request_item_id'
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'User_id');
    }
}
