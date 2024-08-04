<?php

namespace App\Livewire\Auth;

use App\Http\Requests\AuthRequest;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Layout;
use Livewire\Component;
use function Symfony\Component\String\u;

class Login extends Component
{
    public $phone = '';
    public $code = '';
    public $remember_me = false;
    public $verificationCode;
    public $codeSent = false;

    public $errors;

    public $timer;


    protected $rules = [
        'phone' => 'required',
        'code' => 'required',
    ];

    public function mount()
    {
        if (auth('customer')->check()) {
            return redirect()->route('customer.panel.index');
        }
        return null;
    }

    public function sendCode()
    {
        $user = $this->getUser($this->phone);
        /*$customer = Customer::firstOrCreate([
            'phone' => $this->phone
        ]);*/
//dd(get_class($user));
        $token = Token::create([
            'tokenable_type' => get_class($user),
            'tokenable_id' => $user->id
        ]);

        if ($token->sendCode()) {
            session()->put("code_id", $token->id);
            session()->put("auth_id", $token->tokenable_id);
            session()->put("auth_type", $token->tokenable_type);
            session()->put("remember", $this->remember_me);
            $this->code = session("code");
            $this->codeSent = true;
            $this->startTimer();
        } else {
            $token->delete();
            $this->errors = "Unable to send verification code";
        }
    }

    private function getUser($phone)
    {
        $user = Driver::where('phone', $phone)->first();
        if (!$user) {
            $user = Customer::firstOrCreate([
                'phone' => $phone
            ]);
        }
        return $user;
    }

    public function startTimer()
    {
        $this->dispatch('startTimer');
        $this->timer = 120; // 2 دقیقه = 120 ثانیه
    }

    public function stopTimer()
    {
        $this->codeSent = false;
    }

    public function decrementTimer()
    {
        if ($this->timer > 0) {
            $this->timer--;
        } else {
            $this->codeSent = false;
        }
    }

    public function login()
    {
        $token = null;

        if (!session()->has('code_id') || !session()->has('customer_id'))
            redirect()->route('login');

        $token = Token::find(session()->get('code_id'));

        if (!$token || empty($token->id))
            redirect()->route('login');

        if (!$token->isValid())
            redirect()->back()->withErrors('The code is either expired or used.');

        if ($token->code !== $this->code) {
            $this->codeSent = false;
            $this->code = '';
            return redirect()->route('login');
        }
        $token->update([
            'used' => true
        ]);

        if (session()->get('auth_type') == "App\Models\Driver") {

            $driver = Driver::find(session()->get('auth_id'));
            Auth::guard('driver')->login($driver, $this->remember_me);
            return redirect()->route('driver.panel.index');

        } elseif (session()->get('auth_type') == "App\Models\Customer") {

            $customer = Customer::find(session()->get('auth_id'));
            Auth::guard('customer')->login($customer, $this->remember_me);
            return redirect()->route('customer.panel.index');

        }
        return redirect()->route('login');
    }

    #[Layout('customer.layouts.app')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
