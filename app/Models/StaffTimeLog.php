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
        'clock_in_ip',
        'clock_out_ip',
        'clock_in_latitude',
        'clock_in_longitude',
        'clock_out_latitude',
        'clock_out_longitude',
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
