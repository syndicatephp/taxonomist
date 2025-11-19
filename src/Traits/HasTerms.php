<?php

namespace Syndicate\Taxonomist\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Syndicate\Taxonomist\Contracts\Taxonomy;
use Syndicate\Taxonomist\Models\Term;

/**
 * @template TModel of Model
 * @mixin Model
 */
trait HasTerms
{
    public static function bootHasTerms(): void
    {
        static::deleting(function (Model $model) {
            if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()) {
                return;
            }
            $model->terms()->delete();
        });
    }

    public function terms(): MorphToMany
    {
        return $this->morphToMany(Term::class, 'model', 'termables');
    }

    /**
     * @param  class-string<Taxonomy>  $taxonomy
     * @return MorphToMany
     */
    protected function taxonomyRelation(string $taxonomy): MorphToMany
    {
        return $this->morphToMany(Term::class, 'model', 'termables')
            ->where('taxonomy_name', $taxonomy::getId())
            ->wherePivot('taxonomy', $taxonomy::getId())
            ->withPivotValue('taxonomy', $taxonomy::getId());
    }
}
