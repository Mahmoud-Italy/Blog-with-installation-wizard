<?php

return [
    'admin.app_settings' => [
        'index' => 'user::permissions.app_settings.index',
        'create' => 'user::permissions.app_settings.create',
        'edit' => 'user::permissions.app_settings.edit',
        'destroy' => 'user::permissions.app_settings.destroy',
    ],

    'admin.app_settings_manufacture' => [
        'index' => 'user::permissions.app_setting_manufactures.index',
        'create' => 'user::permissions.app_setting_manufactures.create',
        'edit' => 'user::permissions.app_setting_manufactures.edit',
        'destroy' => 'user::permissions.app_setting_manufactures.destroy',
    ],
];
