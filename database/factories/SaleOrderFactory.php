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


$factory->define(\App\Data\Models\SaleOrder::class, function (Faker\Generator $faker) {

    return [
        'user_id' => \App\Data\Models\User::inRandomOrder()->first()->id,
        'customer_id' => \App\Data\Models\Customer::inRandomOrder()->first()->id,
        'due' => rand(0, 100)
   ];
});
