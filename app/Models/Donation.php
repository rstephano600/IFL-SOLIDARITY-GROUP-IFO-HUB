<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_title',
        'description',
        'amount',
        'currency',
        'donation_date',
        'recipient_name',
        'recipient_type',
        'recipient_contact',
        'support_type',
        'attachment',
        'created_by',
        'updated_by',
        'status',
    ];

    // One Donation can have many donation items
    public function items()
    {
        return $this->hasMany(DonationItem::class);
    }

    // User who created this donation
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // User who last updated this donation
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Optional: total value from related items (virtual relationship)
    public function getTotalItemValueAttribute()
    {
        return $this->items->sum('total_value');
    }
}
