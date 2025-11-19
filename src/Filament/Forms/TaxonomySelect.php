<?php

namespace Syndicate\Taxonomist\Filament\Forms;

use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Syndicate\Taxonomist\Contracts\Taxonomy;
use Syndicate\Taxonomist\Services\TaxonomyService;

class TaxonomySelect extends Select
{
    /**
     * @param  class-string<Taxonomy>  $taxonomy
     * @return $this
     */
    public function taxonomy(string $taxonomy): self
    {
        $options = resolve(TaxonomyService::class)->getTaxonomyOptions($taxonomy);

        $this->options($options);

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->multiple();

        $this->searchable();
        $this->preload();

        $this->loadStateFromRelationshipsUsing(function (TaxonomySelect $component, ?Model $record) {
            if (!$record) {
                return;
            }

            $ids = $record->{$component->getName()}()
                ->pluck('terms.id')
                ->toArray();

            $component->state($ids);
        });

        $this->saveRelationshipsUsing(function (TaxonomySelect $component, ?Model $record, $state) {
            if (!$record) {
                return;
            }

            $newState = collect($state ?? [])->map(fn($id) => (int) $id)->sort()->values()->toArray();

            $existingState = $record->{$component->getName()}()
                ->pluck('terms.id')
                ->map(fn($id) => (int) $id)
                ->sort()
                ->values()
                ->toArray();

            if ($newState !== $existingState) {
                $record->{$component->getName()}()->sync($newState);
            }
        });

        $this->dehydrated(false);
    }
}
