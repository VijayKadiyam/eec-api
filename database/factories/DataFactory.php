<?php

use Faker\Generator as Faker;
use App\Data;

$factory->define(Data::class, function (Faker $faker) {
    return [
        'data'  =>  'Data 1'
    ];
});
