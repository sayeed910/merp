<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain\Customer;
use App\Data\Models\User;

$factory->define(App\Data\Models\SaleOrderItem::class, function (Faker\Generator $faker) {

    $product = \App\Data\Models\Product::inRandomOrder()->first();
    $order = \App\Data\Models\SaleOrder::inRandomOrder()->first();
    return [
        'product_item_code' => $product->item_code,
        'sale_order_id' => $order->id,
        'qty' => rand(0, 30),
        'price' => $product->price,
        'created_at' => $order->created_at
   ];
});
