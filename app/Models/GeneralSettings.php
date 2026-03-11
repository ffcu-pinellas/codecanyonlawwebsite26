<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    use HasFactory;

    protected $fillable = ["site_name","site_tag_line","site_sub_tag_line","author_name","og_meta_title","og_meta_description","og_meta_image"];
}
