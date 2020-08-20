<?php

use Faker\Generator as Faker;

$factory->define(\App\SubProduct::class, function (Faker $faker) {
    return [
        'name'  =>  'Sub Product 1',
        'position'  =>  1
    ];
});
