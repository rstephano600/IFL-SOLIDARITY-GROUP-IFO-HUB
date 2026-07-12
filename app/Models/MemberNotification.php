<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class MemberNotification extends DatabaseNotification
{
    protected $table = 'member_notifications';

    /**
     * Only notifications belonging to the currently logged-in member/user.
     * Uses the custom session-based auth id, matching how this app authenticates.
     */
    public function scopeForCurrentUser($query)
    {
        return $query
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', session('auth_user.id'));
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Convenience accessor — data is stored as JSON in the `data` column.
     * Access e.g. $notification->payload['title']
     */
    public function getPayloadAttribute(): array
    {
        return is_array($this->data) ? $this->data : json_decode($this->data, true) ?? [];
    }
}