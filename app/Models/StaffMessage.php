<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffMessage extends Model
{
    use HasFactory;

    protected $table = 'staff_messages';

    protected $fillable = [
        'staff_user_id',
        'officer_user_id',
        'sender_id',
        'message',
        'read',
        'telegram_message_id',
        'is_telegram_reply',
        'file',
        'file_name',
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_user_id');
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_user_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
