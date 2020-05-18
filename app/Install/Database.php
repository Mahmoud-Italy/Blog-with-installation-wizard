<?php

namespace App\Install;

use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use App\Models\Users\Entities\Permission;
use App\Models\Themes\Entities\Theme;
use App\Models\Users\Entities\Role;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Database
{
    public function setup($data)
    {
        $this->checkDatabaseConnection($data);
        $this->setEnvVariables($data);
        $this->migrateDatabase();
        $this->runRoleSeeds();
        $this->runThemeSeeds();
        $this->runPermissionSeeds();
    }

    private function checkDatabaseConnection($data)
    {
        $this->setupDatabaseConnectionConfig($data);

        DB::connection('mysql')->reconnect();
        DB::connection('mysql')->getPdo();
    }

    private function setupDatabaseConnectionConfig($data)
    {
        config([
            'database.default' => 'mysql',
            'database.connections.mysql.host' => $data['host'],
            'database.connections.mysql.port' => $data['port'],
            'database.connections.mysql.database' => $data['database'],
            'database.connections.mysql.username' => $data['username'],
            'database.connections.mysql.password' => $data['password'],
        ]);
    }

    private function setEnvVariables($data)
    {
        $env = DotenvEditor::load();

        $env->setKey('DB_HOST', $data['host']);
        $env->setKey('DB_PORT', $data['port']);
        $env->setKey('DB_DATABASE', $data['database']);
        $env->setKey('DB_USERNAME', $data['username']);
        $env->setKey('DB_PASSWORD', $data['password']);

        $env->save();
    }

    private function migrateDatabase()
    {
        // Migrate Default laravel Tables
        Artisan::call('migrate', ['--force' => true]);

        // Migrate Passport Tables
        Artisan::call('migrate', ['--path' => '/vendor/laravel/passport/database/migrations', '--force'=>true]);
        Artisan::call('passport:install', ['--force'=>true]);
        
        // Migrate Extrenal Tables..
        Artisan::call('migrate', ['--path'=>'/app/Models/Users/Database/migrations', '--force'=>true]);
        Artisan::call('migrate', ['--path'=>'/app/Models/AppSettings/Database/migrations', '--force'=>true]);
        Artisan::call('migrate', ['--path'=>'/app/Models/Categories/Database/migrations', '--force'=>true]);
        Artisan::call('migrate', ['--path'=>'/app/Models/Images/Database/migrations', '--force'=>true]);
        Artisan::call('migrate', ['--path'=>'/app/Models/Media/Database/migrations', '--force'=>true]);
        Artisan::call('migrate', ['--path'=>'/app/Models/Metas/Database/migrations', '--force'=>true]);
        Artisan::call('migrate', ['--path'=>'/app/Models/Pages/Database/migrations', '--force'=>true]);
        Artisan::call('migrate', ['--path'=>'/app/Models/Posts/Database/migrations', '--force'=>true]);
        Artisan::call('migrate', ['--path'=>'/app/Models/Sliders/Database/migrations', '--force'=>true]);
        Artisan::call('migrate', ['--path'=>'/app/Models/Logs/Database/migrations', '--force'=>true]);
        Artisan::call('migrate', ['--path'=>'/app/Models/Tags/Database/migrations', '--force'=>true]);
        Artisan::call('migrate', ['--path'=>'/app/Models/Themes/Database/migrations', '--force'=>true]);
        Artisan::call('migrate', ['--path'=>'/app/Models/Messages/Database/migrations', '--force'=>true]);
        Artisan::call('migrate', ['--path'=>'/app/Models/Visitors/Database/migrations', '--force'=>true]);
            // Feel free to add any new migrations
        
    }

    public function runRoleSeeds()
    {
        Role::create(['role' => 'Subscriber']);        
        Role::create(['role' => 'Editor']);
        Role::create(['role' => 'Author']);
        Role::create(['role' => 'Contributor']);
        Role::create(['role' => 'Administrator']);
            // feel free to assign any new roles
    }

    public function runThemeSeeds()
    {
        Theme::create(['name' => 'mitte', 'image' => '/themes/mitte/assets/preview.png', 'active'=>false]); 
        Theme::create(['name' => 'philosphy', 'image' => '/themes/philosphy/assets/preview.png', 'active'=>true]);        
            // feel free to add any new theme
    }


    public function runPermissionSeeds()
    {
        $values = [];
        $data = $this->getPermissions();
        foreach ($data as $row) {
            $values[] = ['permission' => $row, 'status'=> true];
        }
        Permission::insert($values);
    }

    private function getPermissions()
    {
        return [
            // Users
            'admin.users.index', 
            'admin.users.create', 
            'admin.users.edit', 
            'admin.users.destroy',
            // Roles
            'admin.roles.index', 
            'admin.roles.create', 
            'admin.roles.edit', 
            'admin.roles.destroy',
            // Posts
            'admin.posts.index', 
            'admin.posts.create', 
            'admin.posts.edit', 
            'admin.posts.destroy',
            // Categories
            'admin.categories.index', 
            'admin.categories.create', 
            'admin.categories.edit', 
            'admin.categories.destroy',
            // Tags
            'admin.tags.index', 
            'admin.tags.create', 
            'admin.tags.edit', 
            'admin.tags.destroy',
            // Media
            'admin.media.index', 
            'admin.media.create', 
            // Sliders
            'admin.sliders.index', 
            'admin.sliders.create', 
            'admin.sliders.edit', 
            'admin.sliders.destroy',
            // Pages
            'admin.pages.index', 
            'admin.pages.create', 
            'admin.pages.edit', 
            'admin.pages.destroy',
            // Messages
            'admin.messages.index', 
            'admin.messages.show', 
            'admin.messages.destroy',
            // Themes
            'admin.themes.index',
            'admin.themes.edit',
            // Logs
            'admin.logs.index',
            // Cache Management
            'admin.cache_management.index',
            // Settings
            'admin.app_settings.index',
            'admin.app_settings.edit',
            // Setting Manufcatures
            'admin.app_setting_manufactures.index',
            'admin.app_setting_manufactures.create',
            'admin.app_setting_manufactures.edit',
            'admin.app_setting_manufactures.destroy',

                // feel free to add any new permissions...
        ];        
        
    }


}
