<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Password ;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Reset Password')]
class ResetPasswordPage extends Component
{
    #[Url]
    public $email;
    
    public $token;
    public $password;
    public $password_confirmation;

    public function mount($token)
    {
        $this->token = $token;
    }

    public function save()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
            'password' => [PasswordRule::default(), 'confirmed'],
            'token' => 'required'
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'token' => $this->token
            ]
            , function (\App\Models\User $user, string $password) {
                $password = $this->password;

                $user->forceFill([
                    'password' => bcrypt($password)
                ])
                ->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET ? redirect('/') : Session::flash('error', 'Something went wrong');
    }

    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }
}
