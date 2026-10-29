<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Auth\Http\Responses\LoginResponse;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\SimplePage;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Livewire\Attributes\Locked;

class Welcome extends SimplePage
{
    use WithRateLimiting;

    public User $user;

    #[Locked]
    public ?string $email = null;

    public ?string $password = '';

    public ?string $passwordConfirmation = '';

    public function mount(Request $request): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        if (! $request->hasValidSignature()) {
            abort(Response::HTTP_FORBIDDEN, __('auth.welcome.invalid_signature'));
        }

        if ($this->user->hasSetPassword()) {
            abort(Response::HTTP_FORBIDDEN, __('auth.welcome.already_used'));
        }

        $this->email = $this->user->email;

        $this->form->fill([
            'email' => $this->email,
        ]);
    }

    public function save(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::auth/pages/login.notifications.throttled.title'))
                ->body(__('filament-panels::auth/pages/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => $exception->minutesUntilAvailable,
                ]))
                ->danger()
                ->send();

            return null;
        }

        $this->user->setPassword($this->form->getState()['password']);

        $this->user->activateOrganisation();

        Filament::auth()->login($this->user);

        return app(LoginResponse::class);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::auth/pages/login.form.email.label'))
            ->disabled();
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::auth/pages/login.form.password.label'))
            ->password()
            ->autocomplete('new-password')
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->same('passwordConfirmation')
            ->validationAttribute(__('filament-panels::auth/pages/login.form.password.label'));
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label(__('validation.attributes.password_confirmation'))
            ->password()
            ->autocomplete('new-password')
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->dehydrated(false);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('save')
            ->footer([
                Actions::make([
                    Action::make('save')
                        ->label(__('auth.welcome.submit'))
                        ->submit('save'),
                ])
                    ->fullWidth(),
            ]);
    }

    public function getTitle(): string | Htmlable
    {
        return __('auth.welcome.submit');
    }

    public function getHeading(): string | Htmlable | null
    {
        return __('auth.welcome.submit');
    }
}
