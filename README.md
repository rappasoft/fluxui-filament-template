## Laravel 12, Livewire/Flux, Filament 3 Backend Template

## Demo Credentials

**Admin:** admin@admin.com  
**Password:** password

![Frontend](https://imgur.com/rf0jBEN.png)
![Backend](https://imgur.com/BJGFH7V.png)

## Installation:

- cp .env.example .env
- composer install
- php artisan key:generate
- Update .env values
- php artisan migrate:fresh --seed
- yarn install
- yarn run build
- php artisan serve
- php artisan schedule-monitor:sync

## Preinstalled & Configured Packages:

- Livewire Starter Kit
- Spatie Permissions
- Spatie Activity Log
- Filament Activity Log
- Spatie Health
- Filamant Spatie Health
- Spatie Settings
- Spatie Backup
- Spatie Schedule Monitor
- Filamant Spatie Backup
- Filament Laravel Settings
- Filament Spatie Schedule Monitor
- Laravel Timezone
- Laravel Trends
- Laravel Debugbar
- Larabug
- Laravel Impersonate
- Laravel Horizon
- Laravel Pulse
- Laravel Log Viewer
- Filament E-mail Log

## Other Features

- User Resource with history
- Frontend/Backend by role
- Role based middleware included
- Filament loaded and ready on the client side
- Users created by month dashboard widget
- Useful composer scripts
- Permission based policy example
- Cluster example
- Global Search Hotkey
- Example admin theme override
