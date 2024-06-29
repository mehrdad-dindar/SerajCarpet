<?php

use App\Http\Controllers\CustomerController;
use App\Models\Customer;
use Hashids\Hashids;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
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
