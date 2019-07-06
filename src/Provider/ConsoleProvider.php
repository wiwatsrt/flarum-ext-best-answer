<?php

namespace WiwatSrt\BestAnswer\Provider;

use FoF\Console\Providers\ConsoleProvider as Console;
use Flarum\Foundation\AbstractServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ConsoleProvider extends AbstractServiceProvider
{
    public function register()
    {
        if (!defined('ARTISAN_BINARY')) {
            define('ARTISAN_BINARY', 'flarum');
        }

        // Force registering the Schedule as singleton.

        $this->app->register(Console::class);

        $this->app->resolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('wiwatsrt-best-answer:notify')
                ->hourly()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/wiwatsrt-best-answer-notify.log'));
        });
    }
}
