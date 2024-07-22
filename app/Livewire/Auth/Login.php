<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember_me = false;

    protected $rules = [
        'email' => 'required|email:rfc,dns',
        'password' => 'required',
    ];

    public function mount()
    {
        $this->fill(['email' => 'aSmin@softui.com', 'password' => 'secret']);
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
