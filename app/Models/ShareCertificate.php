<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareCertificate extends Model
{
    use HasFactory;

    protected $table = 'share_certificates';

    protected $fillable = [
        'CertificateRefNo',
        'CertificateNumber',
        'member_id',
        'share_offering_id',
        'share_type_id',
        'SharesQuantity',
        'IssueDate',
        'RevocationDate',
        'RevocationReason',
        'replaced_by_certificate_id',
        'CertificateStatus',

        'company_id',
        'branch_id',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
    ];


    protected $casts = [
        'SharesQuantity' => 'decimal:2',
        'IssueDate' => 'date',
        'RevocationDate' => 'date',
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


    // Previous certificate
    public function replacedCertificate()
    {
        return $this->belongsTo(
            ShareCertificate::class,
            'replaced_by_certificate_id'
        );
    }


    // New certificate replacing this one
    public function replacement()
    {
        return $this->hasOne(
            ShareCertificate::class,
            'replaced_by_certificate_id'
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


    public function user()
    {
        return $this->belongsTo(
            User::class,
            'User_id'
        );
    }
}