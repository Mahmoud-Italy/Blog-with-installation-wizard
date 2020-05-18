<?php

use Faker\Generator;
use Modules\Posts\Entities\User;

// $factory->define(Post::class, function (Generator $faker) {
//     return [
//         'cat_id' => 1,
//         'user_id' => 1,
//         'status' => 1,
//     ];
// });

$factory->define(Post_translation::class, function (Generator $faker) use ($factory) {
    return [
        'post_id' => factory(Post::class)->create()->id,
        'locale' => 'en',
        'title' => $faker->paragraph,
        'body' => $faker->paragraph
    ];
});

