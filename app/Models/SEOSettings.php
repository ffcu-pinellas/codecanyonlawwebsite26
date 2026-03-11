<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEOSettings extends Model
{
    use HasFactory;

    protected $fillable = ["meta_keyword","meta_description"];
}
