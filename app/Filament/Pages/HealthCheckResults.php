<?php

namespace App\Filament\Pages;

use ShuvroRoy\FilamentSpatieLaravelHealth\Pages\HealthCheckResults as BaseHealthCheckResults;

class HealthCheckResults extends BaseHealthCheckResults
{
    public static function getNavigationGroup(): ?string
    {
        return 'System';
    }

    protected static ?int $navigationSort = 10;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('Super Admin');
    }

    public function mount(): void
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);
    }
}
