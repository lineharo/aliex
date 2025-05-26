<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('aliex:parsing')->hourlyAt('7');
Schedule::command('aliex:ai-update')->everyTwoMinutes();
Schedule::command('aliex:loadPromo')->dailyAt('03:00');
