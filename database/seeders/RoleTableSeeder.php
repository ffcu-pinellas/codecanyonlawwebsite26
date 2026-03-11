<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin', 'attorney', 'client'];

        foreach ($roles as $role){
            Role::create([
                'name'=> strtolower($role)
            ]);
        }

        $supperAbmin = Role::findByName('admin');
        $supperAbmin->syncPermissions(Permission::all());
    }
}
