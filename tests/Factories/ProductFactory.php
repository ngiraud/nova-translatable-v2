<?php

use Faker\Generator as Faker;
use NGiraud\NovaTranslatable\Tests\Fixtures\Product;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => [
            'fr' => 'fr - ' . $faker->name,
            'en' => 'en - ' . $faker->name,
        ],
    ];
});
