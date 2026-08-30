<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareDividendAllocation extends Model
{
    use HasFactory;

    protected $table = 'share_dividend_allocations';

    protected $fillable = [
        'share_dividend_declaration_id',
        'member_id',
        'share_type_id',
        'shares_held',
        'share_value',
        'dividend_rate',
        'dividend_amount',
        'payment_status',
        'payment_date',
        'payment_reference',
        'accounting_transaction_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
        'User_id',
        'company_id',
        'branch_id',
    ];

    protected $casts = [
        'shares_held'     => 'decimal:4',
        'share_value'     => 'decimal:2',
        'dividend_rate'   => 'decimal:4',
        'dividend_amount' => 'decimal:2',
        'payment_date'    => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Dividend Declaration
    |--------------------------------------------------------------------------
    */

    public function declaration()
    {
        return $this->belongsTo(
            ShareDividendDeclaration::class,
            'share_dividend_declaration_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Member
    |--------------------------------------------------------------------------
    */

    public function member()
    {
        return $this->belongsTo(
            Member::class,
            'member_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Share Type
    |--------------------------------------------------------------------------
    */

    public function shareType()
    {
        return $this->belongsTo(
            ShareType::class,
            'share_type_id'
        );
    }
}