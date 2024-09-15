<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PropertyController;
use App\Livewire\Auth\Login;
use App\Livewire\Customer\Panel as CustomerPanel;
use App\Livewire\Driver\Order\CreateWizard;
use App\Livewire\Driver\Orders as DriverOrders;
use App\Livewire\Driver\Panel as DriverPanel;
use App\Livewire\Customer\Profile as CustomerProfile;
use App\Livewire\Driver\Profile as DriverProfile;
use App\Livewire\Driver\Tasks;
use App\Livewire\SetLocation;
use App\Models\Address;
use App\Models\Customer;
use App\Livewire\Customer\OrderComponent as CustomerOrder;
use App\Livewire\Customer\Order\Show as CustomerOrderShow;
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
Route::get('test', function () {
    ////    return view('welcome');
    //    $recipient = auth()->user();
    //
    //    \Filament\Notifications\Notification::make()
    //        ->title('Saved successfully')
    //        ->sendToDatabase($recipient);
    //    dd("sent");
    $hashids = new Hashids('', 6);
    $hashedID = $hashids->encode(51);
    return '<a href="'.\route("set-location", $hashedID).'">hi</a>';
});
Route::get('/set-location/{id}', SetLocation::class)->name('set-location');

Route::post('neshan', [CustomerController::class, 'getFullAddress'])->name('getFullAddress');
Route::post('create_address', [CustomerController::class, 'createAddress'])->name('create.address');


Route::middleware(['guest'])->group(function () {
    //    Route::get('login-phone', [AuthController::class, 'loginPhone'])->name('login-phone');
    //    Route::post('login-phone', [AuthController::class, 'doLoginPhone'])->name('doLoginPhone');
    //    Route::get('verify', [AuthController::class, 'verify'])->name('verify');
    //    Route::post('doVerify', [AuthController::class, 'doVerify'])->name('doVerify');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});
Route::middleware(['auth:customer'])->prefix('panel')->group(function () {
    Route::get('/', CustomerPanel::class)->name('customer.panel.index');
    Route::get('/profile', CustomerProfile::class)->name('customer.panel.profile');
    Route::get('/test/{address}', function (Address $address) {
        $lat = $address->latitude;
        $lng = $address->longitude;
        return redirect("");
    })->name("admin.users.edit");
    Route::get('/orders', CustomerOrder::class)->name('customer.panel.orders');
    Route::get('/orders/{order}', CustomerOrderShow::class)->name('customer.panel.orders.show');
});
Route::middleware(['auth:driver'])->prefix('dashboard')->group(function () {
    Route::get('/', DriverPanel::class)->name('driver.panel.index');
    Route::get('/orders', DriverOrders::class)->name('driver.panel.orders');
    Route::get('/profile', DriverProfile::class)->name('driver.panel.profile');
    Route::get('/order/wizard', CreateWizard::class)->name('driver.order.wizard');
    Route::get('/customers', CustomerController::class)->name('customer.index');
    Route::get('/properties', PropertyController::class)->name('property.index');
    Route::get('/properties/dimensions/{property}', [PropertyController::class,'getDimensions'])
        ->name('property.dimensions');
    Route::get('/tasks/{status_id}', Tasks::class)->name('driver.tasks');
});
