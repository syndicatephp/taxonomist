<?php

namespace Syndicate\Taxonomist;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Syndicate\Taxonomist\Commands\InstallTaxonomistCommand;
use Syndicate\Taxonomist\Commands\MakeTaxonomyCommand;
use Syndicate\Taxonomist\Commands\SeedTaxonomyCommand;

class TaxonomistServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('taxonomist')
            ->hasMigrations('create_terms_table', 'create_termables_table')
            ->hasCommands(SeedTaxonomyCommand::class, MakeTaxonomyCommand::class, InstallTaxonomistCommand::class);
    }
}
