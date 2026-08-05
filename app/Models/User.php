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

    // Onboarding steps for agencies
    const ONBOARDING_PAYMENT_REQUIRED = 1;
    const ONBOARDING_PAYMENT_CONFIRMED = 2;
    const ONBOARDING_AI_SERVER_SETUP = 3;
    const ONBOARDING_DOMAIN_CONNECTION = 4;
    const ONBOARDING_NAMESERVER_PENDING = 5;
    const ONBOARDING_COMPLETED = 6;

    public static array $onboardingSteps = [
        1 => ['key' => 'payment_required', 'label' => 'Payment Required', 'description' => 'Waiting for payment confirmation'],
        2 => ['key' => 'payment_confirmed', 'label' => 'Payment Confirmed', 'description' => 'Payment received, AI server setup begins'],
        3 => ['key' => 'ai_server_setup', 'label' => 'AI Server Setup', 'description' => 'Server being configured by Villa Bit team'],
        4 => ['key' => 'domain_connection', 'label' => 'Domain Connection', 'description' => 'Enter your domain for Villa Bit AI Server'],
        5 => ['key' => 'nameserver_pending', 'label' => 'Nameserver Changes', 'description' => 'Waiting for DNS propagation'],
        6 => ['key' => 'completed', 'label' => 'Full Access', 'description' => 'All features unlocked'],
    ];

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
        'agency_server_type',
        'agency_server_price',
        'role',
        'status',
        'has_villabit_access',
        'has_est8ads_access',
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
        'email_verified_at',
        'onboarding_step',
        'onboarding_step_updated_at',
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
            'has_villabit_access' => 'boolean',
            'has_est8ads_access' => 'boolean',
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

    /**
     * True for accounts registered directly on EST8ADS that have no Villa
     * Bit access at all — these get EST8ADS-branded verification, welcome
     * and payment emails instead of Villa Bit's.
     */
    public function isEst8adsOnly(): bool
    {
        return (bool) $this->has_est8ads_access && ! $this->has_villabit_access;
    }

    public function canAccessPlatform(string $platform): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return match ($platform) {
            'villabit' => (bool) $this->has_villabit_access,
            'est8ads' => (bool) $this->has_est8ads_access,
            default => false,
        };
    }

    public function getOnboardingStepLabel(): string
    {
        return self::$onboardingSteps[$this->onboarding_step]['label'] ?? 'Unknown';
    }

    public function getOnboardingStepDescription(): string
    {
        return self::$onboardingSteps[$this->onboarding_step]['description'] ?? '';
    }

    public function isOnboardingComplete(): bool
    {
        return $this->onboarding_step >= self::ONBOARDING_COMPLETED;
    }

    public function advanceOnboardingStep(): void
    {
        if ($this->onboarding_step < self::ONBOARDING_COMPLETED) {
            $this->update([
                'onboarding_step' => $this->onboarding_step + 1,
                'onboarding_step_updated_at' => now(),
            ]);
        }
    }

    public function setOnboardingStep(int $step): void
    {
        $this->update([
            'onboarding_step' => $step,
            'onboarding_step_updated_at' => now(),
        ]);
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

    /**
     * Get the effective agency profile for the current user.
     * For view-only managers, returns the first active agency profile.
     */
    public function getEffectiveAgencyProfile(): ?AgencyProfile
    {
        if ($this->agencyProfile) {
            return $this->agencyProfile;
        }

        if ($this->role === 'manager' && $this->managerProfile?->can_view_agency_readonly) {
            if ($this->managerProfile->view_agency_user_id) {
                return AgencyProfile::where('user_id', $this->managerProfile->view_agency_user_id)->first();
            }
            return AgencyProfile::whereHas('user', fn ($q) => $q->where('status', 'active'))
                ->first();
        }

        return null;
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function sendEmailVerificationNotification(): void
    {
        if ($this->isEst8adsOnly()) {
            $this->notify(new \App\Notifications\Est8ads\VerifyEmailNotification());
            return;
        }

        $this->notify(new VerifyEmailNotification());
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
