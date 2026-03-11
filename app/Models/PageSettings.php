<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSettings extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'title', 'meta_keyword', 'meta_description'];

    public function sections()
    {
        return $this->hasMany(PageSectionSettings::class, 'page_id', 'id');
    }
}
