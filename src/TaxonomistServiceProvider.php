<?php

namespace Syndicate\Taxonomist;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Syndicate\Taxonomist\Commands\TaxonomistCommand;

class TaxonomistServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('taxonomist')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_taxonomist_table')
            ->hasCommand(TaxonomistCommand::class);
    }
}
