<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\DriverResource;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\UserResource;
use App\Filament\Widgets\InvoiceChart;
use App\Filament\Widgets\LatestCommentsWidget;
use Awcodes\Overlook\OverlookPlugin;
use Awcodes\Overlook\Widgets\OverlookWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->profile()
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
//            ->spa()
            ->font('iranSans')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverResources(
                in: base_path('packages/MehrdadDindar/FilamentPorsline/src/Filament/Resources'),
                for: 'MehrdadDindar\\FilamentPorsline\\Filament\\Resources'
            )
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                InvoiceChart::class,
                LatestCommentsWidget::class,
                OverlookWidget::class,
//                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                OverlookPlugin::make()->sort(1)
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 3,
                        'lg' => 4,
                        'xl' => 5,
                        '2xl' => null,
                    ])
                    ->includes([
                        UserResource::class,
                        CustomerResource::class,
                        DriverResource::class,
                        OrderResource::class,
                    ]),
            ])
            ->navigationGroups([
                'Management' => NavigationGroup::make()
                    ->label(fn () => __('Management')),

                'Services Setting' => NavigationGroup::make()
                    ->label(fn () => __('Services Setting')),

                'User Settings' => NavigationGroup::make()
                    ->label(fn () => __('User Settings')),

                'System Setting' => NavigationGroup::make()
                    ->label(fn () => __('System Setting')),
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render('@vite(["resources/js/echo.js"])')
            )
            // لود کپسول شناور تماس در تمام صفحات پنل
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => Blade::render('<livewire:voip.screen-pop-modal />')
            )
            ->brandName(function () {
                return __('Seraj');
            })
//            ->databaseNotifications()
//            ->databaseNotificationsPolling('2s')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->databaseNotifications();
    }
}
