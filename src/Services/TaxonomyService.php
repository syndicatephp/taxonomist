<?php

namespace Syndicate\Taxonomist\Services;

use Illuminate\Support\Facades\Cache;
use Syndicate\Taxonomist\Contracts\Taxonomy;
use Syndicate\Taxonomist\Models\Term;

class TaxonomyService
{
    /**
     * @param  Taxonomy|class-string<Taxonomy>  $taxonomy
     * @return array
     */
    public function getTaxonomyOptions(Taxonomy|string $taxonomy): array
    {
        return Cache::rememberForever($this->getCacheKey($taxonomy), function () use ($taxonomy): array {
            if (method_exists($taxonomy, 'getOptions')) {
                return $taxonomy::getOptions();
            }

            return Term::query()
                ->where('taxonomy_name', $taxonomy::getId())
                ->get()->mapWithKeys(function (Term $term) {
                    return [$term->id => $term->taxonomy->getLabel()];
                })->toArray();
        });
    }

    /**
     * @param  Taxonomy|class-string<Taxonomy>  $taxonomy
     * @return string
     */
    protected function getCacheKey(Taxonomy|string $taxonomy): string
    {
        return 'syndicate.taxonomist.taxonomies.'.$taxonomy::getId();
    }

    /**
     * @param  Taxonomy|class-string<Taxonomy>  $taxonomy
     * @return bool
     */
    public function flushCacheFor(Taxonomy|string $taxonomy): bool
    {
        return Cache::forget($this->getCacheKey($taxonomy));
    }
}
