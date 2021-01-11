<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'slug' => $faker->slug,
        'featured' => false,
        'details' => $faker->sentence(8),
        'description' => $faker->sentence(15),
        'price' => $faker->numberBetween(1000, 500000),
        'image' => 'products/laptop-1.png',
        'quantity' => 10
    ];
});
