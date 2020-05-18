<?php

namespace App\Install;

use App\Models\Users\Entities\Role;
use App\Models\Users\Entities\User;
use App\Models\Users\Entities\Activation;
use App\Models\Users\Entities\Permission;
use App\Models\Users\Entities\User_permission;

class AdminAccount
{
    public function setup($data)
    {   
        // Get Role Administrator
        $role = Role::where('role', 'Administrator')->first();

        // Create Admin
        $admin = User::create([
            'name' => $data['first_name'].' '.$data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role_id' => $role->id,
        ]);

        // Set Administrator Permissions to Admin
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $roles[] = [
                'user_id' => $admin->id,
                'permission_id' => $permission->id
            ]; 
        }
        User_permission::insert($roles);
        
        // Activiate The Admin
        Activation::create(['user_id'=>$admin->id, 'completed'=>true]);
    }
    
}
