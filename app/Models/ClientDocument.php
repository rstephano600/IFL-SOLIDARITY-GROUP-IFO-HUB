<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDocument extends Model
{
    use HasFactory;

    protected $table = 'client_documents';

    protected $fillable = [
        'client_id',
        'document_type',
        'document_name',
        'document_url',
        'file_size',
        'mime_type',
        'verification_status',
        'verified_by_user_id',
        'verified_at',
        'rejection_reason',
        'expiry_date',
        'uploaded_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'uploaded_at' => 'datetime',
        'expiry_date' => 'date',
    ];

    /**
     * Relationships
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    /**
     * Accessors & Helpers
     */
    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function verificationLabel(): string
    {
        return match ($this->verification_status) {
            'pending' => 'text-warning',
            'verified' => 'text-success',
            'rejected' => 'text-danger',
            default => 'text-secondary',
        };
    }
}
