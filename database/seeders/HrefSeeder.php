<?php

namespace Database\Seeders;

use App\Models\Href;
use Illuminate\Database\Seeder;

class HrefSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $urls = [
            (object)[
                'page_name' => 'Home',
                'href' => '/',
            ],
            (object)[
                'page_name' => 'About',
                'href' => '/about',
            ],
            (object)[
                'page_name' => 'Services',
                'href' => '/services',
            ],
            (object)[
                'page_name' => 'Cases',
                'href' => '/cases',
            ],
            (object)[
                'page_name' => 'Blogs',
                'href' => '/blogs',
            ],
            (object)[
                'page_name' => 'Teams',
                'href' => '/Teams',
            ],
            (object)[
                'page_name' => 'Faq',
                'href' => '/faq',
            ],
            (object)[
                'page_name' => 'Contacts',
                'href' => '/contacts',
            ],
            (object)[
                'page_name' => 'Login',
                'href' => '/login',
            ],
        ];

        foreach ($urls as $url){
            Href::create([
                'page_name' =>  $url->page_name,
                'href' =>  $url->href,
            ]);
        }

    }
}
