<?php

namespace App\Filament\Pages;

use pxlrbt\FilamentActivityLog\Pages\ListActivities;

abstract class ListModelActivities extends ListActivities
{
    protected static string $view = 'livewire.auth.partials.history';
}
