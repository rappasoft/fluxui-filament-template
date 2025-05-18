<?php

use Illuminate\Support\Facades\Schedule;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTaskLogItem;

Schedule::command('model:prune', ['--model' => MonitoredScheduledTaskLogItem::class])->daily()->monitorName('Prune Scheduled Task Log Item');
Schedule::command(RunHealthChecksCommand::class)->everyMinute()->monitorName('Run Health Checks');
Schedule::command('backup:clean')->daily()->at('01:00')->monitorName('Clean Backups');
Schedule::command('backup:run')->daily()->at('01:30')->monitorName('Run Backup');
