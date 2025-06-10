<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Organisation;
use Illuminate\Console\Command;

class MoveOrganisationLogoToPublicBucket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:move-organisation-logo-to-public-bucket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move organisation logo to public bucket';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Organisation::query()
            ->whereHas('media')
            ->each(function (Organisation $organisation) {
                $logo = $organisation->getFirstMedia('default');
                $logo->move($organisation, 'default', 's3-public');
                $this->info("Moved logo for organisation: {$organisation->name}");
            });
    }
}
