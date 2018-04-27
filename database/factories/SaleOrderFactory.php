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

    $user = \App\Data\Models\User::inRandomOrder()->first();
    if (! $user)
        $user = factory(\App\Data\Models\User::class)->create();
    $supplier = \App\Data\Models\Customer::inRandomOrder()->first();
    if (! $supplier)
        $supplier = factory(\App\Data\Models\Customer::class)->create();

    return [
        'user_id' => $user->id,
        'ref' => $faker->unique()->bankAccountNumber,
        'customer_id' => $supplier->id,
        'due' => rand(0, 100),
        'created_at' => $faker->dateTimeThisYear
   ];
});
