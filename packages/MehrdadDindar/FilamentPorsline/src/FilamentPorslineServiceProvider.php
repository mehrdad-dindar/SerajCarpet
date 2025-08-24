<?php

namespace MehrdadDindar\FilamentPorsline;

use Filament\FilamentManager;
use Illuminate\Support\ServiceProvider;
use MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResource;
use MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResponseResource;

class FilamentPorslineServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/filament-porsline.php',
            'filament-porsline'
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-porsline');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \MehrdadDindar\FilamentPorsline\Console\Commands\TestPorslineConnection::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../config/filament-porsline.php' => config_path('filament-porsline.php'),
        ], 'filament-porsline-config');

        $this->registerFilamentResources();
    }

    protected function registerFilamentResources(): void
    {
        // در Filament 3، Resources به صورت خودکار کشف می‌شوند
        // فقط باید مطمئن شویم که کلاس‌ها موجود هستند
        if (class_exists(SurveyResource::class) && class_exists(SurveyResponseResource::class)) {
            // Resources به صورت خودکار ثبت می‌شوند
        }
    }
}
