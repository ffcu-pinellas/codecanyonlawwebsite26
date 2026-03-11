<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseStudy extends Model
{
    use HasFactory;
    protected  $fillable = ['service_id', 'service_name','title','description','problem_title','problem_description','solution_title','solution_description','result_title','result_description','featured_image'];

    public function service(){
        return $this->belongsTo(Service::class,'service_id','id');
    }
}
