<?php

namespace Syndicate\Taxonomist\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Termable extends MorphPivot
{
    protected $table = 'termables';

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }
}
