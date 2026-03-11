<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicPage extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'icon', 'title', 'sub_title', 'page_content', 'slug', 'status', 'layout_id', 'key_words', 'delete_able','meta_keyword','meta_description','breadcrumb_bg'];
}
