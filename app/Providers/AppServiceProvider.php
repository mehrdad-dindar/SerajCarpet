<?php

namespace App\Providers;

use App\Livewire\Driver\Order\CreateWizard;
use App\Livewire\Driver\Order\Steps\AddItemsStepComponent;
use App\Livewire\Driver\Order\Steps\ConfirmStepComponent;
use App\Livewire\Driver\Order\Steps\CustomerInfoStepComponent;
use App\Services\SmsSenderBridge;
use App\Settings\SystemSettings;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MehrdadDindar\FilamentSurveyNotifier\Contracts\SmsSenderInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->booted(function () {
            $settings = app(SystemSettings::class);
            config()->set('payment.drivers.zarinpal.merchantId', $settings->zarinpal[1] ?? null);
        });
        $this->app->bind(SmsSenderInterface::class, SmsSenderBridge::class);
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
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => Verta::now()->format('l, d M Y'),
        );
    }
}
