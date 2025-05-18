<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public bool $my_bool;

    public static function group(): string
    {
        return 'general';
    }
}
