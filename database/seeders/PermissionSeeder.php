<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Retrieve the 'editor' role
        $editorRole = Role::where('name', 'editor role')->first();

        if ($editorRole) {
            // Check if the 'edit-product' permission exists
            $permission = Permission::where('name', 'edit-product')->first();

            if (!$permission) {
                // If the permission doesn't exist, create it
                $permission = Permission::create(['name' => 'edit-product']);
            }

            // Assign the 'edit-product' permission to the 'editor' role
            $editorRole->givePermissionTo($permission);

        } 
    }
}
