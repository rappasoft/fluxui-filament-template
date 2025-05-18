<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Backups;
use App\Filament\Pages\HealthCheckResults;
use App\Filament\Widgets\UsersChart;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Mvenghaus\FilamentScheduleMonitor\FilamentPlugin;
use RickDBCN\FilamentEmail\FilamentEmail;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->colors([
                'primary' => Color::Amber,
                'gray' => Color::Slate,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->navigationItems([
                NavigationItem::make('Frontend')
                    ->url('/dashboard')
                    ->icon('heroicon-o-arrow-left')
                    ->sort(-3),
                NavigationItem::make('Horizon')
                    ->url('/admin/horizon')
                    ->group('System')
                    ->icon('heroicon-o-sun')
                    ->visible(fn () => auth()->user()->isSuperAdmin())
                    ->openUrlInNewTab()
                    ->sort(30),
                NavigationItem::make('Pulse')
                    ->url('/admin/pulse')
                    ->group('System')
                    ->icon('ri-pulse-line')
                    ->visible(fn () => auth()->user()->isSuperAdmin())
                    ->openUrlInNewTab()
                    ->sort(40),
                NavigationItem::make('Logs')
                    ->url('/admin/logs')
                    ->group('System')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->visible(fn () => auth()->user()->isSuperAdmin())
                    ->sort(999),
            ])
            ->navigationGroups([
                'Auth',
                'System',
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                UsersChart::class,
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
            ->plugin(FilamentSpatieLaravelHealthPlugin::make()->usingPage(HealthCheckResults::class))
            ->plugin(FilamentSpatieLaravelBackupPlugin::make()->usingPage(Backups::class))
            ->plugin(FilamentPlugin::make())
            ->plugin(FilamentEmail::make())
            ->globalSearchKeyBindings(['command+k', 'ctrl+k']);
    }
}
