<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    public $phone = '';
    public $password = '';
    public $remember_me = false;
    public $verificationCode;
    public $codeSent = false;

    protected $rules = [
        'email' => 'required|email:rfc,dns',
        'password' => 'required',
    ];

    public function mount()
    {
        if (auth('customer')->check()) {
            return redirect()->route('customer.panel.index');
        }
        return null;
    }

    public function login()
    {
        if (auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->remember_me)) {
            $user = User::where(["email" => $this->email])->first();
            auth()->login($user, $this->remember_me);
            return redirect()->intended('/dashboard');
        } else {
            return $this->addError('email', trans('auth.failed'));
        }
    }

    #[Layout('customer.layouts.app')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
