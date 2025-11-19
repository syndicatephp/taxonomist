<?php

namespace Syndicate\Taxonomist\Facades;

use Illuminate\Support\Facades\Facade;
use Syndicate\Taxonomist\Services\TaxonomyService;

/**
 * @mixin TaxonomyService
 */
class Taxonomist extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TaxonomyService::class;
    }
}
