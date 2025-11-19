# Syndicate || Taxonomist

Enum driven taxonomies for laravel // filament.

## Installation

### Composer

You can install the package via composer:

```bash
composer require syndicate/taxonomist
```

[//]: # (### Install)

[//]: # ()

[//]: # (Run the installation command:)

[//]: # ()

[//]: # (```bash)

[//]: # (php artisan install:taxonomist)

[//]: # (```)

### Migrate

Publish and run the migrations:

```bash
php artisan vendor:publish --tag=taxonomist-migrations
php artisan migrate
```

### Stubs

Optionally publish and customize the stubs:

```bash
php artisan vendor:publish --tag=taxonomist-stubs
```

## Usage

### Make

Make new taxonomies via the command line:

```php
php artisan make:taxonomy
```

### Seed

After configuring the cases of a taxonomy, seed the taxonomy via the command line:

```bash
php artisan seed:taxonomy
```

## Filament

There is a dedicated Select for Taxonomies.

```bash
use Syndicate\Taxonomist\Filament\TaxonomySelect;
TaxonomySelect::make('relationName')
    ->taxonomy(TechnologyTaxonomy::class)
```

## Misc

### Adding Relations to the Terms Model

You can add relations to the term model for easier querying from the direction of the Term.

```php
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\ServiceProvider;
use Syndicate\Taxonomist\Models\Term;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Term::resolveRelationUsing('products', function ($categoryModel): MorphToMany {
            return $categoryModel->morphedByMany(Product::class, 'model', 'termables');
        });
    }
}
```

### Using a scoped Term Model

You can extend the base Term Model and scope it to a specific taxonomy.

```php
<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Syndicate\Taxonomist\Taxonomies\ProductTaxonony;
 
class ProductTerm extends Term
{
    protected static function booted(): void
    {
        static::addGlobalScope('product_term', function (Builder $builder) {
            $builder->where('taxonomy', ProductTaxonony::getId());
        });
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
