<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = ['contact', 'get_appointment', 'settings', 'page_settings', 'testimonial', 'slider_settings', 'services', 'partner', 'designation', 'attorney', 'faq', 'case_study', 'blog', 'dynamic_page'];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => strtolower($permission)
            ]);
        }

        $supperAdmin = Role::where('name', 'admin')->first();
        $supperAdmin->givePermissionTo(Permission::all());
    }
}
