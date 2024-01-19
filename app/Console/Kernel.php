<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\PingCommand;
use App\Console\Commands\ProcessProtocolsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(ProcessProtocolsCommand::class)
            ->daily()
            ->at('06:00')
            ->timezone('Europe/Bucharest')
            ->withoutOverlapping()
            ->sentryMonitor('process-protocols');

        $schedule->command(PingCommand::class)
            ->everyTenMinutes()
            ->withoutOverlapping()
            ->sentryMonitor('ping');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
    }
}
