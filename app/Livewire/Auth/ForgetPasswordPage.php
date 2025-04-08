<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Forget Password')]
class ForgetPasswordPage extends Component
{
    public $email;

    public function save()
    {
        $this->validate([
            'email' => 'required|email|max:255|exists:users,email',
        ]);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            Session::flash('success', 'Password reset link has been sent to your email');
            $this->email = '';
        }
    }

    public function render()
    {
        return view('livewire.auth.forget-password');
    }
}
