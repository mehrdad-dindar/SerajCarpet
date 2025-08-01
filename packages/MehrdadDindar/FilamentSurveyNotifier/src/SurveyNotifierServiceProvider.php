<?php

namespace MehrdadDindar\FilamentSurveyNotifier;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SurveyNotifierServiceProvider extends PackageServiceProvider
{

    /**
     * @param Package $package
     * @return void
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-survey-notifier')
            ->hasConfigFile()
            ->hasMigrations()
            ->discoversMigrations()
            /*->hasRoutes('web')*/;
    }

    public function bootingPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
