<?php

namespace Syndicate\Taxonomist\Commands;

use Illuminate\Console\Command;

class SeedTaxonomyCommand extends Command
{
    protected $signature = 'seed:syndicate-taxonomy';

    private int $created = 0;
    private int $skipped = 0;
    private int $updated = 0;
    private int $parented = 0;

    public function handle(): int
    {
        foreach ($enumClasses as $enumClass) {
            if (!is_subclass_of($enumClass, CategoryEnum::class)) {
                $this->error("{$enumClass} not of type ".CategoryEnum::class);
                continue;
            }

            $this->resetCounters();
            $this->createOrUpdate($enumClass);
            $this->addHierarchy($enumClass);

            $this->info("Successfully seeded {$enumClass::getCategoryLabel()}");

            $this->printCounters();
            $this->flushCacheFor($enumClass);
        }

        return self::SUCCESS;
    }

    protected function resetCounters(): void
    {
        $this->created = 0;
        $this->skipped = 0;
        $this->updated = 0;
        $this->parented = 0;
    }

    /**
     * @param  class-string<CategoryEnum>  $enumClass
     * @return void
     */
    public function createOrUpdate(string $enumClass): void
    {
        $categories = Category::enum($enumClass)->get();

        foreach ($enumClass::cases() as $case) {
            if ($this->option('update')) {
                Category::updateOrCreate([
                    'enum' => $case->value,
                    'class_enum' => $enumClass::getId(),
                ], [
                    'name' => $case->getLabel(),
                ]);
                $this->updated++;
                continue;
            }

            if ($categories->where('enum', $case->value)->count() > 0) {
                $this->skipped++;
                continue;
            }

            Category::create([
                'name' => $case->getLabel(),
                'enum' => $case->value,
                'class_enum' => $enumClass::getId(),
            ]);
            $this->created++;
        }
    }

    /**
     * @param  class-string<CategoryEnum>  $enumClass
     * @return void
     */
    public function addHierarchy(string $enumClass): void
    {
        $categories = Category::all();

        foreach ($enumClass::cases() as $case) {
            if (!$case->getParent()) {
                continue;
            }

            $parent = $categories->where('enum', $case->getParent()->value)->where('class_enum',
                $case->getParent()::getId())->first();

            if (!$parent) {
                $this->error("Parent {$case->getParent()->value} not found for {$case->value}");
                continue;
            }

            $categories->where('enum', $case->value)->where('class_enum', $enumClass::getId())
                ->first()
                ->update([
                    'parent_id' => $parent->id,
                ]);

            $this->parented++;
        }
    }

    protected function printCounters(): void
    {
        $this->info("Created: {$this->created}\nSkipped: {$this->skipped}\nUpdated: {$this->updated}\nParented: {$this->parented}");
    }

    /**
     * @param  class-string<CategoryEnum>  $enumClass
     * @return void
     */
    protected function flushCacheFor(string $enumClass): void
    {
        $flushed = app(CategoryService::class)->flushCacheFor($enumClass);
        if ($flushed) {
            $this->info("Cache flushed for {$enumClass::getCategoryLabel()}");
        }
    }
}
