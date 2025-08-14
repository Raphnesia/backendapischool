<?php

namespace App\Filament\Pages;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class CustomLogin extends BaseLogin
{
    public ?string $twoFactorCode = null;
    public bool $showTwoFactorForm = false;
    public ?User $user = null;

    public function mount(): void
    {
        parent::mount();
        
        if (session()->has('2fa_user_id')) {
            $this->user = User::find(session('2fa_user_id'));
            $this->showTwoFactorForm = true;
        }
    }

    public function authenticate(): ?LoginResponse
    {
        $this->validate([
            'data.email' => ['required', 'email'],
            'data.password' => ['required'],
        ]);

        $user = User::where('email', $this->data['email'])->first();

        if (!$user || !auth()->attempt([
            'email' => $this->data['email'],
            'password' => $this->data['password'],
        ])) {
            throw ValidationException::withMessages([
                'data.email' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }

        // Check if user has 2FA enabled
        if ($user->google2fa_enabled) {
            session(['2fa_user_id' => $user->id]);
            $this->showTwoFactorForm = true;
            $this->user = $user;
            return null;
        }

        // If no 2FA, proceed with normal login
        return app(LoginResponse::class);
    }

    public function verifyTwoFactor(): void
    {
        $this->validate([
            'twoFactorCode' => ['required', 'string'],
        ]);

        if (!$this->user || !$this->user->verifyGoogle2FA($this->twoFactorCode)) {
            throw ValidationException::withMessages([
                'twoFactorCode' => 'Invalid 2FA code.',
            ]);
        }

        // Clear session and login user
        session()->forget('2fa_user_id');
        auth()->login($this->user);
        
        $this->redirect(filament()->getHomeUrl());
    }

    public function cancelTwoFactor(): void
    {
        session()->forget('2fa_user_id');
        $this->showTwoFactorForm = false;
        $this->user = null;
        $this->twoFactorCode = null;
    }

    protected function getFormSchema(): array
    {
        if ($this->showTwoFactorForm) {
            return [
                TextInput::make('twoFactorCode')
                    ->label('2FA Code')
                    ->placeholder('Enter 6-digit code')
                    ->required()
                    ->maxLength(6)
                    ->numeric()
                    ->autofocus(),
            ];
        }

        return parent::getFormSchema();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        if ($this->showTwoFactorForm) {
            return [
                \Filament\Actions\Action::make('verify')
                    ->label('Verify')
                    ->submit('verifyTwoFactor')
                    ->color('primary'),
                \Filament\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->action('cancelTwoFactor')
                    ->color('gray'),
            ];
        }

        return parent::getFormActions();
    }

    public function getTitle(): string
    {
        if ($this->showTwoFactorForm) {
            return 'Two-Factor Authentication';
        }

        return parent::getTitle();
    }

    public function getHeading(): string
    {
        if ($this->showTwoFactorForm) {
            return 'Enter your 2FA code';
        }

        return parent::getHeading();
    }
} 