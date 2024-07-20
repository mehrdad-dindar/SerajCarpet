<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Models\Customer;
use Hashids\Hashids;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect("/admin");
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




Route::get('/login-phone', [\App\Http\Controllers\AuthController::class, 'loginPhone'])->name('loginPhone');
Route::post('/login-phone', [\App\Http\Controllers\AuthController::class, 'doLoginPhone'])->name('doLoginPhone');
Route::get('/verify', [\App\Http\Controllers\AuthController::class, 'verify'])->name('verify');
Route::post('/doVerify', [\App\Http\Controllers\AuthController::class, 'doVerify'])->name('doVerify');
Route::middleware(['auth:customer'])->prefix('panel')->group(function (){
    Route::get('/', [CustomerDashboardController::class, 'index'])->name('customer.panel.index');
});
