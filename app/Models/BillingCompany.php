<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillingCompany extends Model
{
    use SoftDeletes;

    protected $table = 'billing_companies';

    protected $fillable = [
        'company_code', 'company_name', 'tin_number', 'vrn_number',
        'registration_number', 'contact_person', 'phone', 'email',
        'city', 'region', 'country', 'address',
        'credit_limit', 'payment_terms_days', 'Status',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'credit_limit'       => 'decimal:2',
        'payment_terms_days' => 'integer',
    ];

    public function customers(): HasMany
    {
        return $this->hasMany(SalesCustomer::class, 'billing_company_id');
    }

    public function scopeActive($query)
    {
        return $query->where('Status', 'Active');
    }
}