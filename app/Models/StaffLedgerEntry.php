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
        'description',
        'entry_date',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
