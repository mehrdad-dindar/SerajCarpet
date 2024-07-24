<?php

namespace App\Livewire\Auth;

use App\Http\Requests\AuthRequest;
use App\Models\Customer;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Layout;
use Livewire\Component;

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
        $customer = Customer::firstOrCreate([
            'phone' => $this->phone
        ]);

        $token = Token::create([
            'customer_id' => $customer->id
        ]);

        if ($token->sendCode()) {
            session()->put("code_id", $token->id);
            session()->put("customer_id", $customer->id);
            session()->put("remember", $this->remember_me);
//            $this->code = session("code");
            $this->codeSent = true;
            $this->startTimer();
        } else {
            $token->delete();
            $this->errors = "Unable to send verification code";
        }
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

        if (!session()->has('code_id') || !session()->has('customer_id'))
            redirect()->route('login');
        $token = Token::where('customer_id', session()->get('customer_id'))->find(session()->get('code_id'));

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

        $customer = Customer::find(session()->get('customer_id'));
        Auth::guard('customer')->login($customer, $this->remember_me);
        return redirect()->route('customer.panel.index');
    }

    #[Layout('customer.layouts.app')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
