<?php

namespace Database\Seeders;

use App\Models\SocialMediaSettings;
use Illuminate\Database\Seeder;

class SocialMediaSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $socialSites = ["facebook","twitter","linkedin","google_plus","pinterest","youtube","instagram","tumblr","flicker","reddit","snapchat","whatsapp","stumble_upon","quora","delicious","digg"];
        foreach ($socialSites as $socialSite){
            SocialMediaSettings::create([
                'name' => $socialSite
            ]);
        }
    }
}
