<?php

namespace Syndicate\Taxonomist\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\text;

class MakeTaxonomyCommand extends Command
{
    protected $signature = 'make:taxonomy {name? : The name of the taxonomy}';

    protected $description = 'Create a new Syndicate taxonomy class';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $nameArg = $this->argument('name');
        $name = is_string($nameArg) ? trim($nameArg) : '';

        if (empty($name)) {
            $taxonomy = text(
                label: 'What is the name of the taxonomy?',
                placeholder: 'E.g. ProductTaxonomy',
                required: true,
                hint: 'The Taxonomy affix will be automatically added to the class name.',
            );
        } else {
            $taxonomy = $name;
        }

        if (!str($taxonomy)->endsWith('Taxonomy')) {
            $taxonomy .= 'Taxonomy';
        }
        $pascal = str($taxonomy)->pascal();

        $targetDir = app_path('Syndicate/Taxonomist/Taxonomies');

        if (!$this->files->isDirectory($targetDir)) {
            $this->files->makeDirectory($targetDir, 0755, true);
        }

        $path = $targetDir.'/'.$pascal.'.php';

        if ($this->files->exists($path)) {
            error("$pascal already exists.");
            return static::FAILURE;
        }

        $stub = $this->buildClass(
            class: $pascal,
            id: str($pascal)->headline()->slug(),
            name: str($pascal)->headline(),
        );

        $this->files->put($path, $stub);

        info("Successfully created $pascal");

        return self::SUCCESS;
    }

    protected function buildClass(string $class, string $id, string $name): string
    {
        $stub = $this->files->get($this->stubPath());

        return str_replace(
            [
                '{{ class }}',
                '{{ id }}',
                '{{ name }}',
            ],
            [
                $class,
                $id,
                $name,
            ],
            $stub
        );
    }

    protected function stubPath(): string
    {
        $published = base_path('stubs/syndicate/taxonomist/taxonomy.stub');

        if (file_exists($published)) {
            return $published;
        }

        return __DIR__.'/../../stubs/taxonomy.stub';
    }
}
