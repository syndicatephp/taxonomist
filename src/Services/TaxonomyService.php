<?php

namespace Syndicate\Taxonomist\Services;

use Exception;
use Illuminate\Support\Facades\Cache;

class TaxonomyService
{
    /**
     * @param  class-string<CategoryEnum>  $categoryEnum
     * @return array
     */
    public function getCategoryOptions(string $categoryEnum): array
    {
        return Cache::rememberForever($this->getCacheKey($categoryEnum), function () use ($categoryEnum) {
            if (method_exists($categoryEnum, 'getOptions')) {
                return $categoryEnum::getOptions();
            }

            $options = [];
            Category::enum($categoryEnum)->get()->sortBy('name')->each(function (Category $category) use (&$options) {
                $options[$category->id] = $category->enum->getLabel();
            })->toArray();

            return $options;
        });
    }

    /**
     * @param  class-string<CategoryEnum>  $categoryEnum
     * @return string
     */
    protected function getCacheKey(string $categoryEnum): string
    {
        return 'syndicate.librarian.categories.'.$categoryEnum;
    }

    /**
     * @param  class-string<CategoryEnum>  $categoryEnum
     * @return bool
     */
    public function flushCacheFor(string $categoryEnum): bool
    {
        return Cache::forget($this->getCacheKey($categoryEnum));
    }
}
