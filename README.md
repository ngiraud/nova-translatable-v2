# Nova Translatable V2

This [Laravel Nova](https://nova.laravel.com) allows you to make any input field using the `spatie/laravel-translatable` package compatible and localisable.

This package is inspired by [optimistdigital/nova-translatable](https://github.com/optimistdigital/nova-translatable).

## Requirements

- `laravel/nova: ^2.9`
- `spatie/laravel-translatable: ^4.0`

You first need to set up the [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable) package.

## Installation

The package can be installed via composer:

```bash
composer require ngiraud/nova-translatable-v2
```

The package will automatically register the service provider.

You can publish the config file in order to use custom locales.

```bash
php artisan vendor:publish --tag="nova-translatable-config"
```

This is the contents of the published config file:

```php
return [

    /**
     * the locales which the `translatable` wrapper will use by default.
     *
     * can be a:
     *  - keyed array (['en' => 'english])
     *  - callable that returns a keyed array
     */

    'locales' => ['en' => 'English'],

];
```

## Usage

Simply call `->translatable()` on a Nova field:

```php
Text::make('Name')
  ->rules('required', 'min:2')
  ->translatable()
```

Optionally you can pass custom locales:

```php
Number::make('Population')
  ->translatable([
    'en' => 'English',
    'et' => 'Estonian',
  ])
```

## Validation

You can add validation rules for a single locale.

To do so, add the `->rulesFor()` on your field and the `HasTranslatable` trait to your Nova resource:

```php
class Product extends Resource
{
    use \NGiraud\NovaTranslatable\HasTranslatable;
    
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make(__('Name'), 'name')
                ->sortable()
                ->translatable()
                ->rules(['max:255'])
                ->rulesFor('fr', [
                    'required'
                ])
    }
}
```

In this example, the rule `max` will be applied for `name.*` and the rule `required` will be applied for `name.fr`.

## TODOS

- Adding tests to the package

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email contact@ngiraud.me instead of using the issue tracker.

## Credits

- [Nicolas Giraud](https://github.com/ngiraud)
- [All contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
