<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSectionSettings extends Model
{
    use HasFactory;

    protected $fillable = ['page_id', 'name', 'number_of_content','bg_img','title','sub_title','description','fnt_img','show','form_title','form_subtitle','line_one','line_two','case_won','total_attorney','total_client','total_case_dismissed'];

    public function page()
    {
        return $this->belongsTo(PageSettings::class, 'page_id','id');
    }
}
