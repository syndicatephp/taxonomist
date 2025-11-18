<?php

namespace Syndicate\Taxonomist\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallTaxonomistCommand extends Command
{
    protected $signature = 'install:taxonomist';

    public function handle(): int
    {
        Artisan::call('vendor:publish', ['--tag' => 'taxonomist-migrations']);

        return self::SUCCESS;
    }
}
