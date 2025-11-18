<?php

namespace Syndicate\Taxonomist\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Syndicate\Taxonomist\Contracts\Taxonomy;
use Syndicate\Taxonomist\Models\Term;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class SeedTaxonomyCommand extends Command
{
    protected $signature = 'seed:taxonomy {taxonomy? : The name/fqn of the taxonomy}';

    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $nameArg = $this->argument('taxonomy');
        $name = is_string($nameArg) ? trim($nameArg) : '';

        if (empty($name)) {
            $options = $this->files->files(app_path('Syndicate/Taxonomist/Taxonomies'));
            $options = array_map(fn($file) => str($file)->afterLast('\\')->beforeLast('.php')->toString(), $options);


            $taxonomy = select(
                label: 'Which taxonomies would you like to seed?',
                options: array_merge(['All', 'Manual Input'], $options),
                default: 'All',
            );

            if ($taxonomy === 'All') {
                $taxonomy = $options;
            } elseif ($taxonomy === 'Manual Input') {
                $taxonomy = text(
                    label: 'What is the fqn of the taxonomy?',
                    placeholder: 'E.g. App\Enums\ProductTaxonomy',
                    required: true,
                    hint: 'The Taxonomy affix will be automatically added to the class name.',
                );
            }
        } else {
            $taxonomy = $name;
        }

        if (is_array($taxonomy)) {
            foreach ($taxonomy as $tax) {
                $this->handleTaxonomy($tax);
            }
        } else {
            $this->handleTaxonomy($taxonomy);
        }

        return self::SUCCESS;
    }

    public function handleTaxonomy(string $taxonomy): void
    {
        if (!class_exists($taxonomy)) {
            $taxonomy = str($taxonomy)->prepend('App\\Syndicate\\Taxonomist\\Taxonomies\\')->toString();

            if (!class_exists($taxonomy)) {
                error("$taxonomy does not exist.");
            }
        }

        if (!is_subclass_of($taxonomy, Taxonomy::class)) {
            error("$taxonomy must implement ".Taxonomy::class.'.');
        }

        $this->seedTerms($taxonomy);
        $this->seedParentRelations($taxonomy);

        /** @var class-string<Taxonomy> $taxonomy */
        info("Successfully seeded {$taxonomy::getName()}");
    }

    /**
     * @param  class-string<Taxonomy>  $taxonomy
     * @return void
     */
    public function seedTerms(string $taxonomy): void
    {
        foreach ($taxonomy::cases() as $case) {
            Term::updateOrCreate([
                'case' => $case->value,
                'taxonomy' => $taxonomy::getId(),
            ], [
                'name' => $case->getLabel(),
                'fqn' => get_class($case),
            ]);
        }
    }

    /**
     * @param  class-string<Taxonomy>  $taxonomy
     * @return void
     */
    public function seedParentRelations(string $taxonomy): void
    {
        $terms = Term::all();

        foreach ($taxonomy::cases() as $case) {
            if ($case->getParent() === null) {
                continue;
            }

            $parent = $terms
                ->where('taxonomy', $case->getParent()::getId())
                ->where('case', $case->getParent()->value)
                ->first();

            if ($parent === null) {
                continue;
            }

            $child = $terms
                ->where('taxonomy', $taxonomy::getId())
                ->where('case', $case->value)
                ->first();

            $child->update([
                'parent_id' => $parent->id,
            ]);
        }
    }
}
