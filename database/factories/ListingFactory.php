<?php

use Faker\Generator as Faker;

$factory->define(\App\Listing::class, function (Faker $faker) {
    return [
        'name'  =>  'Capricorn'
    ];
});
