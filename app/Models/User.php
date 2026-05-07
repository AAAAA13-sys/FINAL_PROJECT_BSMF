<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Contracts\Auth\MustVerifyEmail;

final class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        // 'role', // REMOVED: Prevent mass assignment of user roles
        'phone',
        'region',
        'city',
        'default_shipping_address',
        'newsletter_subscribed',
        'otp',
        'otp_expires_at',
        'reset_otp',
        'reset_otp_expires_at',
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
            'password' => 'hashed',
            'newsletter_subscribed' => 'boolean',
            'otp_expires_at' => 'datetime',
            'reset_otp_expires_at' => 'datetime',
        ];
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is staff.
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Check if user is administrative (admin or staff).
     */
    public function isAdministrative(): bool
    {
        return in_array($this->role, ['admin', 'staff']);
    }

    /**
     * Get the orders for the user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the cart for the user.
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }


    /**
     * Get the restock requests for the user.
     */
    public function restockRequests(): HasMany
    {
        return $this->hasMany(RestockRequest::class);
    }

    /**
     * Determine if the user has verified their email address.
     * Administrative accounts bypass this during development.
     *
     * @return bool
     */
    public function hasVerifiedEmail(): bool
    {
        return $this->isAdministrative() || ! is_null($this->email_verified_at);
    }

    /**
     * Generate and send a new OTP.
     */
    public function sendVerificationOtp(): void
    {
        $otp = (string) random_int(100000, 999999);
        
        $this->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        defer(fn () => $this->notify(new \App\Notifications\VerifyEmailOtp($otp)));
    }
}
