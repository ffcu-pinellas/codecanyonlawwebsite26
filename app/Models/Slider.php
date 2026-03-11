<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected  $fillable= ['title','sub_title','description','button_name','button_url','bg_image','status'];

}
