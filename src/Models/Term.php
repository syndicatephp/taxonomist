<?php

namespace Syndicate\Taxonomist\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Syndicate\Taxonomist\Casts\TaxonomyCast;

class Term extends Model
{
    public $table = 'terms';

    protected $guarded = ['id'];

    public function termables(): HasMany
    {
        return $this->hasMany(Termable::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    public function siblings(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id', 'parent_id');
    }

    protected function casts(): array
    {
        return [
            'taxonomy' => TaxonomyCast::class,
        ];
    }
}
