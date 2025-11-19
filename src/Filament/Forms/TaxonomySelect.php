<?php

namespace Syndicate\Taxonomist\Filament\Forms;

use Filament\Forms\Components\Select;

class TaxonomySelect extends Select
{
//    /**
//     * @param  class-string<Taxonomy>  $taxonomy
//     * @return $this
//     */
//    public function taxonomy(string $taxonomy): self
//    {
//        return $this->options(
//            Cache::remember('test-taxo2', 20, function () use ($taxonomy) {
//                return Term::whereTaxonomy($taxonomy::getId())->get()->sortBy('name')
//                    ->mapWithKeys(function (Term $category) {
//                        return [$category->id => $category->name];
//                    })->toArray();
//            })
//        );
//    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->preload()
            ->label(str($this->name)->headline())
            ->relationship($this->name, 'name')
            ->multiple();
    }
}

