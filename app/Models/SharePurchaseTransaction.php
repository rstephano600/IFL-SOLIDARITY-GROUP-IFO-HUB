<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharePurchaseTransaction extends Model
{
    use HasFactory;

    protected $table = 'share_purchase_transactions';

    protected $fillable = [
        'TransactionRefNo',
        'member_id',
        'share_offering_id',
        'share_type_id',
        'TransactionType',
        'SharesQuantity',
        'PricePerShare',
        'TransactionDate',
        'PaymentMethod',
        'PaymentReference',
        'related_transfer_id',
        'Narration',
        'company_id',
        'branch_id',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
    ];

    protected $casts = [
        'SharesQuantity' => 'decimal:2',
        'PricePerShare' => 'decimal:2',
        'TransactionDate' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function shareOffering()
    {
        return $this->belongsTo(ShareOffering::class, 'share_offering_id');
    }

    public function shareType()
    {
        return $this->belongsTo(ShareType::class, 'share_type_id');
    }

    public function transfer()
    {
        return $this->belongsTo(ShareTransfer::class, 'related_transfer_id');
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
}