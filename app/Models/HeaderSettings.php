<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderSettings extends Model
{
    use HasFactory;

    protected $fillable = ["show","left_content","right_content"];
}
