<?php

use Faker\Generator;
use App\Modules\Users\Entities\User;

$factory->define(User::class, function (Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(123456)
    ];
});
