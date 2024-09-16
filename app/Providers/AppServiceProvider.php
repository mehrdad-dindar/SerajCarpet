<?php

namespace App\Providers;

use App\Livewire\Driver\Order\CreateWizard;
use App\Livewire\Driver\Order\Steps\AddItemsStepComponent;
use App\Livewire\Driver\Order\Steps\ConfirmStepComponent;
use App\Livewire\Driver\Order\Steps\CustomerInfoStepComponent;
use App\Livewire\Driver\Order\Steps\SelectCustomerStepComponent;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Livewire::component('create-order-wizard', CreateWizard::class);

        Livewire::component('customer-info', CustomerInfoStepComponent::class);
        Livewire::component('select-items', AddItemsStepComponent::class);
        Livewire::component('confirm-order', ConfirmStepComponent::class);
    }
}
