<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'branch_id',
        'User_id',
        'supplier_id',
        'SupplierRefNo',
        'SupplierCode',
        'SupplierName',
        'SupplierCategory_id',
        'Member_id',
        'SupplierType',
        'ContactPerson',
        'Phone',
        'AlternativePhone',
        'Email',
        'Address',
        'City',
        'Region',
        'Country',
        'TinNumber',
        'VatNumber',
        'CreditLimit',
        'OpeningBalance',
        'PaymentTerms',
        'Status',
        'AuditingStatus',
        'ReportStatus',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'Country' => 'Tanzania',
        'CreditLimit' => 0.00,
        'OpeningBalance' => 0.00,
        'Status' => 'Active',
        'AuditingStatus' => 'Pending',
        'ReportStatus' => 'Pending',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'CreditLimit' => 'decimal:2',
        'OpeningBalance' => 'decimal:2',
    ];

    /**
     * Get the company that owns the supplier.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the branch associated with the supplier.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the user who created/registered the supplier.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'User_id');
    }

    /**
     * Get the user account representing the supplier (if applicable).
     */
    public function supplierUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    /**
     * Get the category assigned to the supplier.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(SupplierCategory::class, 'SupplierCategory_id');
    }

    /**
     * Get the member associated with the supplier (if applicable).
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'Member_id');
    }
}