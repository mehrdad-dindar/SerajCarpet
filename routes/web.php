<?php

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
//Route::get('test', function (){
//    $hashids = new Hashids('',6);
//    $hashedID = $hashids->encode(1);
//    return '<a href="'.\route("set-location",$hashedID).'">hi</a>';
//});
//Route::get('/set-location/{customer}', function ($customer) {
//    $hashids = new Hashids('',6);
//    dd($hashids->decode($customer));
//    $hashedUrl = Hash::make($customer->id);
//
//    return response()->json([
//        'hashed_url' => $hashedUrl
//    ]);
//})->name('set-location');
