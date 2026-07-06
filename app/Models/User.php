<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * ===========================================
     * ROLE DEFINITIONS
     * ===========================================
     */
    public const ROLE_ADMIN           = 'admin';
    public const ROLE_DIRECTOR        = 'director';
    public const ROLE_CEO             = 'ceo';
    public const ROLE_SHAREHOLDERS    = 'shareholders';
    public const ROLE_MANAGER         = 'manager';
    public const ROLE_MARKETING_OFFICER = 'marketingofficer';
    public const ROLE_HR              = 'hr';
    public const ROLE_ACCOUNTANT      = 'accountant';
    public const ROLE_SECRETARY       = 'secretary';
    public const ROLE_LOAN_OFFICER    = 'loanofficer';
    public const ROLE_CLIENT          = 'client';
    public const ROLE_USER            = 'user';

    /**
     * Return all available roles.
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_DIRECTOR,
            self::ROLE_CEO,
            self::ROLE_SHAREHOLDERS,
            self::ROLE_MANAGER,
            self::ROLE_MARKETING_OFFICER,
            self::ROLE_HR,
            self::ROLE_ACCOUNTANT,
            self::ROLE_SECRETARY,
            self::ROLE_LOAN_OFFICER,
            self::ROLE_CLIENT,
            self::ROLE_USER,
        ];
    }

    protected $fillable = [
        'username',
        'name',
        'FirstName',
        'MiddleName',
        'LastName',
        'email',
        'phone',
        'password',
        'Role',
        'Status',
        'created_by',
        'updated_by',
        'is_loged',
        'failed_login_attempts',
        'locked_until',
        'email_verified_at',
        'phone_verified_at',
        'User_id',
        'Dob',
        'gender',
        'Status',
        'AuditingStatus',
        'ReportStatus'
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'locked_until' => 'datetime',
        'is_loged' => 'boolean',
        'password' => 'hashed',
    ];


    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }


    public function hasRole($role): bool
    {
        return in_array($this->role, (array) $role);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_DIRECTOR,
            self::ROLE_CEO,
        ]);
    }

    public function isManagement(): bool
    {
        return in_array($this->role, [
            self::ROLE_MANAGER,
            self::ROLE_DIRECTOR,
            self::ROLE_CEO,
        ]);
    }

    public function isHR(): bool
    {
        return $this->role === self::ROLE_HR;
    }

    public function isFinance(): bool
    {
        return in_array($this->role, [
            self::ROLE_ACCOUNTANT,
            self::ROLE_SHAREHOLDERS,
        ]);
    }

    public function isLoanOfficer(): bool
    {
        return $this->role === self::ROLE_LOAN_OFFICER;
    }

    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    public function isEmployee(): bool
    {
        return in_array($this->role, [
            self::ROLE_HR,
            self::ROLE_MANAGER,
            self::ROLE_ACCOUNTANT,
            self::ROLE_MARKETING_OFFICER,
            self::ROLE_SECRETARY,
            self::ROLE_LOAN_OFFICER,
        ]);
    }

    /**
     * ===========================================
     * ADDITIONAL LOGIC
     * ===========================================
     */
    public function lockAccount(int $minutes = 15)
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes),
            'status' => 'suspended',
        ]);
    }

    public function unlockAccount()
    {
        $this->update([
            'locked_until' => null,
            'failed_login_attempts' => 0,
            'status' => 'active',
        ]);
    }

    public function shareholder()
    {
        return $this->hasOne(Shareholder::class);
    }

    public function permissionUsers()
    {
        return $this->hasMany(PermissionUser::class, 'User_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_users', 'User_id', 'permission_id')
            ->withPivot(['id', 'Creater_id', 'Status', 'duration', 'start_date', 'end_date'])
            ->withTimestamps();
    }

    /**
     * Get all ACTIVE permission slugs for this user (cached per request)
     */
    public function activePermissionSlugs(): array
    {
        static $cache = [];

        $userId = $this->id;

        if (!isset($cache[$userId])) {
            $cache[$userId] = $this->permissionUsers()
                ->where('Status', 'Active')
                ->with('permission')
                ->get()
                ->filter(fn($pu) => $pu->isActive())        // handles Temporary date check
                ->map(fn($pu) => $pu->permission?->slug)
                ->filter()
                ->values()
                ->toArray();
        }

        return $cache[$userId];
    }

    public function hasPermissionSlug(string $slug): bool
    {
        return in_array($slug, $this->activePermissionSlugs());
    }
}
