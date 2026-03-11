<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['title','description','icon','featured_image','presentation','presentation','brochures','status'];


    public function caseStudy(){
        return $this->hasMany(CaseStudy::class,'service_id','id');
    }

}
