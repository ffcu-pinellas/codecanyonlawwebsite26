<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSettings extends Model
{
    use HasFactory;

    protected $fillable = ["show","column1_short_disc","show_social","column2_recent_post_title","column2_recent_post_number","column3_popular_post_title","column3_popular_post_title_number","column4_title","column4_description","footer_logo", "bottom_footer_show","footer_copy_right"];
}
