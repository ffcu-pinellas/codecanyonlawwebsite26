<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function user()
    {
        return $this->belongsToMany(User::class, 'conversations_user','conversation_id','user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id')->where(['read'=>false]);
    }
}
