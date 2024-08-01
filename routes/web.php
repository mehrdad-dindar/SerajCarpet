<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Livewire\Auth\Login;
use App\Livewire\Customer\Panel;
use App\Livewire\Customer\Profile;
use App\Models\Address;
use App\Models\Customer;
use Hashids\Hashids;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route("login");
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// TODO: Hashed link for submitting location
Route::get('test', function (){
    $hashids = new Hashids('',6);
    $hashedID = $hashids->encode(51);
    return '<a href="'.\route("set-location",$hashedID).'">hi</a>';
});
Route::get('/set-location/{id}', function ($id) {
    $hashid = new Hashids('',6);
    $customerID = $hashid->decode($id)[0];
    $customer = Customer::findOrFail($customerID);
    return view('set-location')
        ->with([
            'customer' => $customer,
            'hashid' => $id,
        ]);
})->name('set-location');

Route::post('neshan', [CustomerController::class, 'getFullAddress'])->name('getFullAddress');
Route::post('create_address', [CustomerController::class, 'createAddress'])->name('create.address');


Route::middleware(['guest'])->group(function () {
    Route::get('login-phone', [AuthController::class, 'loginPhone'])->name('login-phone');
    Route::post('login-phone', [AuthController::class, 'doLoginPhone'])->name('doLoginPhone');
    Route::get('verify', [AuthController::class, 'verify'])->name('verify');
    Route::post('doVerify', [AuthController::class, 'doVerify'])->name('doVerify');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});
Route::middleware(['auth:customer'])->prefix('panel')->group(function () {
    Route::get('/', Panel::class)->name('customer.panel.index');
    Route::get('/profile', Profile::class)->name('customer.panel.profile');
    Route::get('/test/{address}', function (Address $address){
        $lat = $address->latitude;
        $lng = $address->longitude;
        return redirect("");
    })->name("admin.users.edit");
});
