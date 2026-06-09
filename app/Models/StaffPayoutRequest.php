<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPayoutRequest extends Model
{
    use HasFactory;

    protected $table = 'staff_payout_requests';

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
