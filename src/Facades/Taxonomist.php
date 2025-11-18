<?php

namespace Syndicate\Taxonomist\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Syndicate\Taxonomist\Taxonomist
 */
class Taxonomist extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Syndicate\Taxonomist\Taxonomist::class;
    }
}
