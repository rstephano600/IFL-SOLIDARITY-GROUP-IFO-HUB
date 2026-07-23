<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareTransfer extends Model
{
    use HasFactory;

    protected $table = 'share_transfers';


    protected $fillable = [
        'TransferRefNo',

        'from_member_id',
        'to_member_id',

        'share_offering_id',
        'share_type_id',

        'SharesQuantity',
        'TransferPrice',

        'TransferDate',
        'Reason',

        'ApprovedBy',
        'ApprovalDate',

        'TransferStatus',

        'company_id',
        'branch_id',
        'User_id',

        'Status',
        'AuditingStatus',
        'ReportStatus',
    ];


    protected $casts = [
        'SharesQuantity' => 'decimal:2',
        'TransferPrice' => 'decimal:2',
        'TransferDate' => 'date',
        'ApprovalDate' => 'date',
    ];



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */


    public function fromMember()
    {
        return $this->belongsTo(
            Member::class,
            'from_member_id'
        );
    }


    public function toMember()
    {
        return $this->belongsTo(
            Member::class,
            'to_member_id'
        );
    }


    public function shareOffering()
    {
        return $this->belongsTo(
            ShareOffering::class,
            'share_offering_id'
        );
    }


    public function shareType()
    {
        return $this->belongsTo(
            ShareType::class,
            'share_type_id'
        );
    }


    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'ApprovedBy'
        );
    }


    public function user()
    {
        return $this->belongsTo(
            User::class,
            'User_id'
        );
    }


    public function company()
    {
        return $this->belongsTo(
            Company::class,
            'company_id'
        );
    }


    public function branch()
    {
        return $this->belongsTo(
            Branch::class,
            'branch_id'
        );
    }
}