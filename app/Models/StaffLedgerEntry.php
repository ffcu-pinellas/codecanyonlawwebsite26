<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffLedgerEntry extends Model
{
    use HasFactory;

    protected $table = 'staff_ledger_entries';

    protected $fillable = [
        'user_id',
        'type', // debt, reimbursement, bonus
        'amount',
        'paid_amount',
        'status', // pending, approved, paid, partially_paid
        'attachment_path',
        'description',
        'entry_date',
        'created_by', // admin, staff
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
