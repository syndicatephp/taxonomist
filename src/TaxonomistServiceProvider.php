<?php

namespace Syndicate\Taxonomist;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Syndicate\Taxonomist\Commands\SeedTaxonomyCommand;

class TaxonomistServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('taxonomist')
            ->hasViews()
            ->hasMigration('create_taxonomist_table')
            ->hasCommand(SeedTaxonomyCommand::class);
    }
}
