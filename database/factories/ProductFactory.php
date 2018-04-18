<?php

/*
|--------------------------------------------------------------------------
| model factories
|--------------------------------------------------------------------------
|
| here you may define all of your model factories. model factories give
| you a convenient way to create models for testing and seeding your
| database. just tell the factory how a default model should look.
|
*/

/** @var \illuminate\database\eloquent\factory $factory */

use App\Data\Models\Brand;
use App\Data\Models\Category;

$factory->define(App\Data\Models\Product::class, function (faker\generator $faker) {


    $brand = Brand::inrandomorder()->first();
    $category = Category::inrandomorder()->first();

    if (! $brand){
        $brand = factory(Brand::class)->create();
    }

    if (! $category){
        $category = factory(\App\Data\Models\Category::class)->create();
    }

    return [
        'item_code' => uniqid(),
        'name' => $faker->name,
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'size' => str_random(5),
        'unit' => str_random(5),
        'cost' => rand(100, 10000),
        'price' => rand(100, 10000),
        'damaged' => rand(0, 100),
        'stock' => rand(0, 1000),
    ];
});

