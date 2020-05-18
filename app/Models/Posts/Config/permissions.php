<?php

return [
    'admin.posts' => [
        'index' => 'user::permissions.posts.index',
        'create' => 'user::permissions.posts.create',
        'edit' => 'user::permissions.posts.edit',
        'destroy' => 'user::permissions.posts.destroy',
    ],
];
