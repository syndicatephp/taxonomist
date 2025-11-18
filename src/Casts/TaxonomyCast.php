<?php

namespace Syndicate\Taxonomist\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Syndicate\Taxonomist\Contracts\Taxonomy;

class TaxonomyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): Taxonomy
    {
        return $attributes['fqn']::from($attributes['case']);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return $value instanceof Taxonomy ? $value->value : $value;
    }
}
