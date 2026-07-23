<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    use HasFactory;

    protected $table = 'units_of_measure';

    protected $fillable = [
        'UnitRefNo',
        'UnitCode',
        'UnitName',
        'Description',
        'company_id',
        'branch_id',
        'User_id',
        'Status',
        'AuditingStatus',
        'ReportStatus',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Auto-generate UnitRefNo on creation if not provided.
     */
    protected static function booted()
    {
        static::creating(function ($unit) {
            if (empty($unit->UnitRefNo)) {
                $lastId = static::max('id') + 1;
                $unit->UnitRefNo = 'UOM-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /* -----------------------------------------------------------
     | Relationships
     | -----------------------------------------------------------
     */

    public function user()
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    // Uncomment if products reference a base unit
    // public function products()
    // {
    //     return $this->hasMany(Product::class, 'unit_id');
    // }

    /* -----------------------------------------------------------
     | Scopes
     | -----------------------------------------------------------
     */

    public function scopeActive($query)
    {
        return $query->where('Status', 'Active');
    }

    public function scopeApproved($query)
    {
        return $query->where('AuditingStatus', 'Approved');
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope to current session-authenticated user, matching
     * the session('auth_user') pattern used across the system.
     */
    public function scopeForSessionUser($query)
    {
        return $query->where('User_id', session('auth_user.id'));
    }
}