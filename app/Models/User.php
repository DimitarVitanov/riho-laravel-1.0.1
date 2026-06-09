<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\VerifyEmailNotification;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements HasMedia, MustVerifyEmail
{
    use HasFactory, SoftDeletes, Notifiable, InteractsWithMedia, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'company_name',
        'country',
        'account_type',
        'role',
        'status',
        'timezone',
        'preferred_language',
        'notes_internal',
        'assigned_manager_id',
        'created_by_admin_id',
        'avatar_path',
        'last_login_at',
        'is_reseller_enabled',
        'is_affiliate_enabled',
        'is_investor_enabled',
        'is_agency_enabled',
        'referral_code',
        'privacy_accepted_at',
        'terms_accepted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_reseller_enabled' => 'boolean',
            'is_affiliate_enabled' => 'boolean',
            'is_investor_enabled' => 'boolean',
            'is_agency_enabled' => 'boolean',
            'privacy_accepted_at' => 'datetime',
            'terms_accepted_at' => 'datetime',
        ];
    }

    protected $with = [
        'media'
    ];

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isAgency(): bool
    {
        return $this->role === 'real_estate_agency';
    }

    public function isInvestor(): bool
    {
        return $this->role === 'investor';
    }

    public function isOnWaitlist(): bool
    {
        return $this->status === 'waitlist';
    }

    public function assignedManager()
    {
        return $this->belongsTo(User::class, 'assigned_manager_id');
    }

    public function managerProfile()
    {
        return $this->hasOne(ManagerProfile::class);
    }

    public function agencyProfile()
    {
        return $this->hasOne(AgencyProfile::class);
    }

    public function investorProfile()
    {
        return $this->hasOne(InvestorProfile::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function managedAgencyProfiles()
    {
        return AgencyProfile::where('assigned_manager_id', $this->id);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification());
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
