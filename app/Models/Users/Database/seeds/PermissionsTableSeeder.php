<?php

namespace App\Models\User\Database\seeds;

use Illuminate\Database\Seeder;
use App\Models\Users\Entities\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = [];
        $data = $this->getPermissions();
        foreach ($data as $row) {
            $values[] = ['permission' => $row, 'status'=> true];
        }
        Permission::insert($values);
        
    }

    /**
     * Get admin permissions.
     *
     * @return array
     */
    private function getPermissions()
    {
        $permissions = [];
        $permissions[] = ['admin.users.index', 'admin.users.create', 'admin.users.edit', 'admin.users.destroy'];
        $permissions[] = ['admin.roles.index', 'admin.roles.create', 'admin.roles.edit', 'admin.roles.destroy'];
        $permissions[] = ['admin.posts.index', 'admin.posts.create', 'admin.posts.edit', 'admin.posts.destroy'];
        $permissions[] = ['admin.categories.index', 'admin.categories.create', 'admin.categories.edit', 
                          'admin.categories.destroy'];
        $permissions[] = ['admin.tags.index', 'admin.tags.create', 'admin.tags.edit', 'admin.tags.destroy'];
        $permissions[] = ['admin.media.index', 'admin.music.create', 'admin.music.edit', 'admin.music.destroy'];
        $permissions[] = ['admin.sliders.index', 'admin.sliders.create', 'admin.sliders.edit', 'admin.sliders.destroy'];
        $permissions[] = ['admin.pages.index', 'admin.pages.create', 'admin.pages.edit', 'admin.pages.destroy'];
        $permissions[] = ['admin.themes.index','admin.themes.edit'];
        $permissions[] = ['admin.reports.index'];
        $permissions[] = ['admin.logs.index'];
        $permissions[] = ['admin.cache_managements.index'];
        $permissions[] = ['admin.app_settings.index'];
        
        return $permissions;

        // feel free to add any new permissions...
    }
}
