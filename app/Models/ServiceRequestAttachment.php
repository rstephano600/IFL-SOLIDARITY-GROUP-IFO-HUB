<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequestAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_request_attachments';

    protected $fillable = [
        'ReferenceNo',
        'service_request_id',
        'attachment_type',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'description',
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
        'file_size' => 'integer',
    ];

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(
            ServiceRequest::class,
            'service_request_id'
        );
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
