<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageGeneralSettings extends SettingsPage
{
    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'General Settings';

    protected static ?string $title = 'General Settings';

    protected static string $settings = GeneralSettings::class;

    protected static ?int $navigationSort = 10;

    public function mountCanAuthorizeAccess(): void
    {
        abort_unless(auth()->user()->can('Manage General Settings'), 403);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('Manage General Settings');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('my_bool'),
            ]);
    }
}
