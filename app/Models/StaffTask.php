<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffTask extends Model
{
    use HasFactory;

    protected $table = 'staff_tasks';

    protected $fillable = [
        'staff_user_id',
        'title',
        'description',
        'due_date',
        'status',
        'attachment_path',
        'completion_notes',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'staff_user_id');
    }
}
