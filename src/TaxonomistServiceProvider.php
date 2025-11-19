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
            ->hasMigration('create_terms_table')
            ->hasMigration('create_termables_table')
            ->hasCommands(SeedTaxonomyCommand::class, MakeTaxonomyCommand::class, InstallTaxonomistCommand::class);
    }

    public function bootingPackage(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs' => base_path('stubs/syndicate/taxonomist'),
            ], 'taxonomist-stubs');
        }
    }
}
