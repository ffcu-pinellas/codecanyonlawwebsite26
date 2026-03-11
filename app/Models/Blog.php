<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','title', 'description', 'bg_image', 'feature_img', 'category_id','is_featured','is_popular'];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id','id');
    }

    public function blogTags()
    {
        return $this->hasMany(BlogTag::class, 'blog_id','id');
    }
    public function userId()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
}
