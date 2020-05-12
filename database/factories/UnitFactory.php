<?php

use Faker\Generator as Faker;
use App\Unit;

$factory->define(Unit::class, function (Faker $faker) {
    return [
        'first_name'  =>  'First 1',
        'imei_number' =>  'No'
    ];
});
