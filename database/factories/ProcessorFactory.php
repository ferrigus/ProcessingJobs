<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Processor;
use Faker\Generator as Faker;

$factory->define(Processor::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});
