<?php

namespace App\Livewire\Auth;

use App\Http\Requests\AuthRequest;
use App\Models\Customer;
use App\Models\Token;
use App\Models\User;
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
            $this->code = session("code");
            $this->codeSent = true;
            $this->startTimer();
        }
        $token->delete();
        $this->errors = "Unable to send verification code";
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


    public function verifyCode()
    {
        if ($this->verificationCode == '1234') {
            // ورود کاربر
            Auth::guard('customer')->loginUsingId(1, $this->remember); // فرض کنیم کاربر با آیدی 1 وجود دارد
            return redirect()->route('customer.panel.index'); // تغییر مسیر به صفحه پنل
        } else {
            $this->addError('verificationCode', 'کد تایید اشتباه است.');
        }
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
