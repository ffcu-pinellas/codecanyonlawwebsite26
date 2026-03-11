<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogoSettings extends Model
{
    use HasFactory;

    protected $fillable = ["logo","favicon","site_tag_image"];
}
