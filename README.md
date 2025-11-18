# Enum driven taxonomies for laravel/filament.

## Installation

You can install the package via composer:

```bash
composer require syndicatephp/taxonomist
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="taxonomist-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="taxonomist-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="taxonomist-views"
```

## Usage

```php
$taxonomist = new Syndicate\Taxonomist();
echo $taxonomist->echoPhrase('Hello, Syndicate!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
