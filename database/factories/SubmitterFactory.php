<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\Submitter;
use Faker\Generator as Faker;

$factory->define(Submitter::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});
