<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'text', 'icon', 'href', 'target', 'title', 'parent_id', 'position'];

    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class, 'category_id','id');
    }

    public function childs() {
        return $this->hasMany(Menu::class,'parent_id','id') ;
    }

    public function parent(){
        return $this->belongsTo(Menu::class, 'parent_id','id');
    }
}
