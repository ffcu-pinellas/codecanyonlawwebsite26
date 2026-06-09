<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffTimeLog extends Model
{
    use HasFactory;

    protected $table = 'staff_time_logs';

    protected $fillable = [
        'user_id',
        'clocked_in_at',
        'clocked_out_at',
        'duration_seconds',
        'hourly_rate_at_time',
        'earned_amount',
    ];

    protected $casts = [
        'clocked_in_at' => 'datetime',
        'clocked_out_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
