<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Logout extends Component
{
    public function logout()
    {
        if (auth('customer')->check()) {
            auth('customer')->logout();
        } elseif (auth('driver')->check()){
            auth('driver')->logout();
        }
        return redirect()->route('login');
    }
    public function render()
    {
        return view('livewire.auth.logout');
    }
}
