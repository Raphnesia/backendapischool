<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TwoFactorSetup extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $title = 'Two-Factor Authentication';
    protected static ?string $slug = 'two-factor-setup';
    protected static ?string $navigationGroup = 'Security';

    public ?string $verificationCode = null;
    public ?string $backupCode = null;
    public bool $showQRCode = false;
    public ?string $qrCodeUrl = null;
    public ?string $secretKey = null;

    public function mount(): void
    {
        $user = auth()->user();
        
        if ($user->google2fa_enabled) {
            $this->showQRCode = true;
            $this->qrCodeUrl = $user->getGoogle2FAQRCodeUrl();
            $this->secretKey = $user->google2fa_secret;
        }
    }

    public function enableTwoFactor(): void
    {
        $user = auth()->user();
        
        if (!$user->google2fa_secret) {
            $secret = $user->generateGoogle2FASecret();
            $user->update(['google2fa_secret' => $secret]);
        }
        
        $this->qrCodeUrl = $user->fresh()->getGoogle2FAQRCodeUrl();
        $this->secretKey = $user->fresh()->google2fa_secret;
        $this->showQRCode = true;
    }

    public function verifyAndEnable(): void
    {
        $this->validate([
            'verificationCode' => ['required', 'string', 'size:6'],
        ]);

        $user = auth()->user();
        
        if ($user->verifyGoogle2FA($this->verificationCode)) {
            $user->enableGoogle2FA($user->google2fa_secret);
            
            Notification::make()
                ->title('Two-Factor Authentication Enabled')
                ->success()
                ->send();
                
            $this->redirect(filament()->getHomeUrl());
        } else {
            Notification::make()
                ->title('Invalid Code')
                ->danger()
                ->body('The verification code you entered is invalid.')
                ->send();
        }
    }

    public function disableTwoFactor(): void
    {
        $user = auth()->user();
        $user->disableGoogle2FA();
        
        $this->showQRCode = false;
        $this->qrCodeUrl = null;
        $this->secretKey = null;
        
        Notification::make()
            ->title('Two-Factor Authentication Disabled')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        
        if (!$user->google2fa_enabled) {
            return [
                Action::make('enable')
                    ->label('Enable 2FA')
                    ->action('enableTwoFactor')
                    ->color('primary')
                    ->icon('heroicon-o-shield-check'),
            ];
        }
        
        return [
            Action::make('disable')
                ->label('Disable 2FA')
                ->action('disableTwoFactor')
                ->color('danger')
                ->icon('heroicon-o-shield-exclamation')
                ->requiresConfirmation(),
        ];
    }

    public function getContent(): string
    {
        $user = auth()->user();
        
        if ($user->google2fa_enabled) {
            return view('filament.pages.two-factor-enabled', [
                'user' => $user,
                'qrCodeUrl' => $this->qrCodeUrl,
                'secretKey' => $this->secretKey,
            ])->render();
        }
        
        if ($this->showQRCode) {
            return view('filament.pages.two-factor-setup', [
                'user' => $user,
                'qrCodeUrl' => $this->qrCodeUrl,
                'secretKey' => $this->secretKey,
            ])->render();
        }
        
        return view('filament.pages.two-factor-disabled', [
            'user' => $user,
        ])->render();
    }
} 