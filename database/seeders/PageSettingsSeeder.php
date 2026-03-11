<?php

namespace Database\Seeders;

use App\Models\PageSettings;
use Illuminate\Database\Seeder;

class PageSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settingsPages = ['home', 'about', 'contact'];
        foreach ($settingsPages as $page){
            PageSettings::create([
                'name' => $page
            ]);
        }
    }
}
