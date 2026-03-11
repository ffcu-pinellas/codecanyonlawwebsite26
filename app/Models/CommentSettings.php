<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentSettings extends Model
{
    use HasFactory;

    protected $fillable = ['show', 'code', 'url'];
}
