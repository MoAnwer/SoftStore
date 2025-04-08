<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Register')]
class RegisterPage extends Component
{
    public $name;
    public $email;
    public $password;

    // register user

    public function save()
    {
        $this->validate([
            'name'  => 'required|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:8|max:255'
        ]);

        // save to database

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password)
        ]);

        // login user
        auth()->login($user);

        // redirect to home page

        return redirect()->intended();
    }

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
