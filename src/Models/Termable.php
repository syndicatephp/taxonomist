<?php

namespace Syndicate\Taxonomist\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Termable extends Model
{
    protected $table = 'termables';

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
