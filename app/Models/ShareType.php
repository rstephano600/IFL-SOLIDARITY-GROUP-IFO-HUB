<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareType extends Model
{
    use HasFactory;

    protected $table = 'share_types';

    protected $fillable = [
        'TypeRefNo',
        'TypeCode',
        'TypeName',
        'Description',
        'NominalValue',
        'DividendEligible',
        'company_id',
        'branch_id',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
    ];

    protected $casts = [
        'NominalValue' => 'decimal:2',
        'DividendEligible' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

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
            'share_type_id'
        );
    }

    public function certificates()
    {
        return $this->hasMany(
            ShareCertificate::class,
            'share_type_id'
        );
    }


    public function transfers()
    {
        return $this->hasMany(
            ShareTransfer::class,
            'share_type_id'
        );
    }
}