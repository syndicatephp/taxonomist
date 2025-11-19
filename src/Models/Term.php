<?php

namespace Syndicate\Taxonomist\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Syndicate\Taxonomist\Contracts\Taxonomy;

class Term extends Model
{
    public $table = 'terms';

    protected $guarded = ['id'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    public function scopeTaxonomy($query, string|Taxonomy $taxonomy)
    {
        return $query->where('taxonomy_name', $taxonomy instanceof Taxonomy ? $taxonomy::getId() : $taxonomy);
    }

    public function scopeCase($query, string|Taxonomy $taxonomy)
    {
        return $query->where('slug', $taxonomy instanceof Taxonomy ? $taxonomy->value : $taxonomy);
    }

    public function scopeTaxonomyCase($query, string|Taxonomy $taxonomy)
    {
        return $query
            ->case($taxonomy)
            ->taxonomy($taxonomy);
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    public function siblings(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id', 'parent_id');
    }

    protected function getTaxonomyAttribute(): Taxonomy
    {
        if (!$this->taxonomy_fqn) {
            throw new Exception('Taxonomy FQN not set. Likely not a code driven term.');
        }

        return $this->taxonomy_fqn::from($this->slug);
    }

    protected function casts(): array
    {
        return [
            'meta' => 'array'
        ];
    }
}
