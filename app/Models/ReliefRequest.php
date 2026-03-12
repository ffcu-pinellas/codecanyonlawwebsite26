<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReliefRequest extends Model
{
    use HasFactory;

    protected $table = 'relief_requests';

    protected $fillable = ['user_id', 'name', 'phone', 'email', 'address', 'file_name', 'file', 'reason', 'details', 'offer', 'viewed'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
