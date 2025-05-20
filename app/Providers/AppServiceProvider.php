<?php

namespace App\Providers;

use App\Livewire\Driver\Order\CreateWizard;
use App\Livewire\Driver\Order\Steps\AddItemsStepComponent;
use App\Livewire\Driver\Order\Steps\ConfirmStepComponent;
use App\Livewire\Driver\Order\Steps\CustomerInfoStepComponent;
use App\Services\ShiftSchedulerService;
use App\Settings\SystemSettings;
use Filament\Support\Facades\FilamentView;
use Hekmatinasser\Verta\Verta;
use Illuminate\Console\Scheduling\Schedule;
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
        $this->app->booted(function () {
            $settings = app(SystemSettings::class);
            config()->set('payment.drivers.zarinpal.merchantId', $settings->zarinpal[1]);
        });
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

        FilamentView::registerRenderHook(
            'panels::global-search.before',
            fn (): string => Verta::now()->format('l, d M Y'),
        );
    }
}
