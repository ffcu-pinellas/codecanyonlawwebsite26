<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attorney extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'designation_id','name','image','phone','email','fax','address','description','education','professional_exp','legal_exp','status'];

    public function designation(){
        return $this->belongsTo(Designation::class, 'designation_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
