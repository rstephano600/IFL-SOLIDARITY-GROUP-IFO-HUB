<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationEvaluation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'quotation_evaluations';

    protected $fillable = [
        'supplier_quotation_id',
        'evaluation_no',
        'technical_score',
        'price_score',
        'delivery_score',
        'quality_score',
        'total_score',
        'evaluation_status',
        'recommendation',
        'evaluation_comment',
        'evaluated_by',
        'evaluated_at',
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
        'supplier_quotation_id' => 'integer',
        'technical_score' => 'decimal:2',
        'price_score' => 'decimal:2',
        'delivery_score' => 'decimal:2',
        'quality_score' => 'decimal:2',
        'total_score' => 'decimal:2',
        'evaluated_by' => 'integer',
        'evaluated_at' => 'datetime',
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

    public function quotation()
    {
        return $this->belongsTo(
            SupplierQuotation::class,
            'supplier_quotation_id'
        );
    }

    public function evaluator()
    {
        return $this->belongsTo(
            User::class,
            'evaluated_by'
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