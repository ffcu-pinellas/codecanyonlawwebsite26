<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserTableSeadder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
           'name' => 'admin',
            'email' => 'admin@bdcoder.com',
            'password' => Hash::make('admin123'),
        ]);

        $user->assignRole('admin');
    }
}
