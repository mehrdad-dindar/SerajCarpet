<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\Customer;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginPhone()
    {
        if (!auth('customer')->check()) {
            return view('auth.phone-login');
        }
        return redirect()->route("customer.panel.index");
    }

    public function doLoginPhone(AuthRequest $request)
    {
        $data = $request->all();

        $customer = Customer::firstOrCreate([
            'phone' => $request->input('phone')
        ]);

        $token = Token::create([
            'customer_id' => $customer->id
        ]);

        $rememberMe = !empty($request->remember_me);
        if ($token->sendCode()) {
            session()->put("code_id", $token->id);
            session()->put("customer_id", $customer->id);
            session()->put("remember", $rememberMe);
            return redirect()->route('verify');
        }
        $token->delete();
        return redirect()->route('login')->withErrors([
            "Unable to send verification code"
        ]);
    }

    public function verify()
    {
        return view('auth.otp');
    }

    public function doVerify(Request $request)
    {
        $this->validator($request->all())->validate();

        if (!session()->has('code_id') || !session()->has('customer_id'))
            redirect()->route('login');

        $token = Token::where('customer_id', session()->get('customer_id'))->find(session()->get('code_id'));

        if (!$token || empty($token->id))
            redirect()->route('login');
        if (!$token->isValid())
            redirect()->back()->withErrors('The code is either expired or used.');

        if ($token->code !== $request->input('code'))
            redirect()->back()->withErrors('The code is wrong.');

        $token->update([
            'used' => true
        ]);

        $customer = Customer::find(session()->get('customer_id'));
        $rememberMe = session()->get('remember');
        Auth::guard('customer')->login($customer, $rememberMe);
        return redirect()->route('customer.panel.index');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'code' => ['required', 'numeric']
        ]);
    }
}
