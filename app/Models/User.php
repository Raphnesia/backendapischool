<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
// use PragmaRX\Google2FA\Google2FA;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // 'google2fa_secret',
        // 'google2fa_enabled',
        // 'google2fa_enabled_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        // 'google2fa_secret',
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
            // 'google2fa_enabled' => 'boolean',
            // 'google2fa_enabled_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    // /**
    //  * Generate Google 2FA secret
    //  */
    // public function generateGoogle2FASecret(): string
    // {
    //     $google2fa = new Google2FA();
    //     return $google2fa->generateSecretKey();
    // }

    // /**
    //  * Enable Google 2FA for user
    //  */
    // public function enableGoogle2FA(string $secret): void
    // {
    //     $this->update([
    //         'google2fa_secret' => $secret,
    //         'google2fa_enabled' => true,
    //         'google2fa_enabled_at' => now(),
    //     ]);
    // }

    // /**
    //  * Disable Google 2FA for user
    //  */
    // public function disableGoogle2FA(): void
    // {
    //     $this->update([
    //         'google2fa_secret' => null,
    //         'google2fa_enabled' => false,
    //         'google2fa_enabled_at' => null,
    //     ]);
    // }

    // /**
    //  * Verify Google 2FA code
    //  */
    // public function verifyGoogle2FA(string $code): bool
    // {
    //     if (!$this->google2fa_enabled || !$this->google2fa_secret) {
    //         return false;
    //     }

    //         $google2fa = new Google2FA();
    //         return $google2fa->verifyKey($this->google2fa_secret, $code);
    // }

    // /**
    //  * Get QR Code URL for Google Authenticator
    //  */
    // public function getGoogle2FAQRCodeUrl(): string
    // {
    //         if (!$this->google2ha_secret) {
    //             return '';
    //         }

    //         $google2fa = new Google2FA();
    //         return $google2fa->getQRCodeUrl(
    //             config('app.name'),
    //             $this->email,
    //             $this->google2fa_secret
    //         );
    // }
}
