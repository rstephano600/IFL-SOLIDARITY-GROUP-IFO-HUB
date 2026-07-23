<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareOffering extends Model
{
    use HasFactory;

    protected $table = 'share_offerings';

    protected $fillable = [
        'OfferingRefNo',
        'OfferingName',
        'share_type_id',
        'TotalShares',
        'PricePerShare',
        'MaxPercentPerMember',
        'OfferingStartDate',
        'OfferingEndDate',
        'OfferingStatus',
        'company_id',
        'branch_id',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
    ];

    protected $casts = [
        'TotalShares'          => 'decimal:2',
        'PricePerShare'        => 'decimal:2',
        'MaxPercentPerMember'  => 'decimal:2',
        'OfferingStartDate'    => 'date',
        'OfferingEndDate'      => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function shareType()
    {
        return $this->belongsTo(ShareType::class, 'share_type_id');
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
    
    public function purchaseTransactions()
    {
        return $this->hasMany(
            SharePurchaseTransaction::class,
            'share_offering_id'
        );
    }

    public function certificates()
    {
        return $this->hasMany(
            ShareCertificate::class,
            'share_offering_id'
        );
    }


    public function transfers()
    {
        return $this->hasMany(
            ShareTransfer::class,
            'share_offering_id'
        );
    }
}