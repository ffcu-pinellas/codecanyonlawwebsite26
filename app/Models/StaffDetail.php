<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffDetail extends Model
{
    use HasFactory;

    protected $table = 'staff_details';

    protected $fillable = [
        'user_id',
        'staff_id',
        'position',
        'hired_at',
        'is_active',
        'hourly_rate',
        'next_pay_date',
        'bonus',
        'debt',
        'reimbursement',
        'assigned_officer_id',
        'payment_method',
        'void_check_path',
        'direct_deposit_form_path',
        'payment_verified',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'payment_verified' => 'boolean',
        'hired_at' => 'date',
        'next_pay_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'assigned_officer_id');
    }
}
