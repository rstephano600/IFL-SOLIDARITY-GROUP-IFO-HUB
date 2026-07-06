<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'item_name',
        'quantity',
        'unit_value',
        'total_value',
        'currency',
        'attachment',
    ];

    // Each donation item belongs to one donation
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    protected static function booted()
{
    static::saving(function ($item) {
        if ($item->quantity && $item->unit_value) {
            $item->total_value = $item->quantity * $item->unit_value;
        }
    });
}

}
