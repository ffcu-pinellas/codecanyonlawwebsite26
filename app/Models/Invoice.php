<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'case_id',
        'client_id',
        'amount',
        'due_date',
        'status',
        'description',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function clientCase()
    {
        return $this->belongsTo(ClientCase::class, 'case_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
