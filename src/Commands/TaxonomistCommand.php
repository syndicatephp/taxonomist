<?php

namespace Syndicate\Taxonomist\Commands;

use Illuminate\Console\Command;

class TaxonomistCommand extends Command
{
    public $signature = 'taxonomist';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
