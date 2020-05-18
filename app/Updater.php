<?php

namespace App;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class Updater
{
    public static function run()
    {
        self::migrate();
        self::checkHtaccessFile();
        self::clearViewCache();
        self::clearConfigCache();
        self::clearRouteCache();
        self::clearAppCache();

        //File::delete(storage_path('app/update'));
    }

    private static function migrate()
    {
        if (config('app.installed')) {
            Artisan::call('migrate', ['--force' => true]);
        }
    }

    private static function checkHtaccessFile()
    {
        if (! File::exists(base_path('.htaccess'))) {
            File::copy(base_path('.htaccess.example'), base_path('.htaccess'));
        }
    }

    private static function clearViewCache()
    {
        Artisan::call('view:clear');
    }

    private static function clearConfigCache()
    {
        Artisan::call('config:clear');
    }

    private static function clearRouteCache()
    {
        Artisan::call('route:trans:clear');
    }

    private static function clearAppCache()
    {
        Artisan::call('cache:clear');
    }
}
