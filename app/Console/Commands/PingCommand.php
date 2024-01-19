<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dummy command to test Sentry monitoring';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Pong!');

        return static::SUCCESS;
    }
}
