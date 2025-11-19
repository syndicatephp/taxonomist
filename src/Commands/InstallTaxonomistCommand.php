<?php

namespace Syndicate\Taxonomist\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;

class InstallTaxonomistCommand extends Command
{
    protected $signature = 'install:taxonomist';

    public function handle(): int
    {
        info('Welcome to the Syndicate Taxonomist installer.');

        // -----------------------------
        // 1. Handle Migrations
        // -----------------------------
        if (confirm('Do you wish to publish the migrations?')) {
            // Use glob to find files because migrations have timestamps (e.g., 2023_01_01_000000_create...)
            // REPLACE 'create_taxonomist_tables.php' with your actual migration file ending
            $existingMigrations = glob(database_path('migrations/*_create_terms_tables.php'));

            if (!empty($existingMigrations)) {
                if (confirm('Migrations already exist. Do you want to overwrite them?', false)) {
                    $this->publishMigrations(true);
                } else {
                    info('Skipped publishing migrations.');
                }
            } else {
                $this->publishMigrations(false);
            }
        }

        // -----------------------------
        // 2. Handle Stubs
        // -----------------------------
        if (confirm('Do you wish to publish the stubs?', false)) {
            if (File::exists(base_path('stubs/syndicate/taxonomist/taxonomy.stub'))) {
                if (confirm('Stubs already exist. Do you want to overwrite them?', false)) {
                    $this->publishStubs(true);
                } else {
                    info('Skipped publishing stubs.');
                }
            } else {
                $this->publishStubs(false);
            }
        }

        return self::SUCCESS;
    }

    private function publishMigrations(bool $force): void
    {
        $params = ['--tag' => 'taxonomist-migrations'];
        if ($force) {
            $params['--force'] = true;
        }

        $exitCode = Artisan::call('vendor:publish', $params);

        // Artisan returns 0 on success, anything else is an error
        if ($exitCode === 0) {
            info($force ? 'Migrations overridden successfully.' : 'Migrations published successfully.');
        } else {
            info('Failed to publish migrations.');
        }
    }

    private function publishStubs(bool $force): void
    {
        $params = ['--tag' => 'taxonomist-stubs'];
        if ($force) {
            $params['--force'] = true;
        }

        $exitCode = Artisan::call('vendor:publish', $params);

        if ($exitCode === 0) {
            info($force ? 'Stubs overridden successfully.' : 'Stubs published successfully.');
        } else {
            info('Failed to publish stubs.');
        }
    }
}
