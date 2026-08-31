<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseSettlement extends Model
{
    use HasFactory;

    protected $table = 'case_settlements';

    protected $fillable = [
        'case_id',
        'client_id',
        'gross_amount',
        'legal_fee_percent',
        'legal_fee_amount',
        'expenses_amount',
        'net_client_payout',
        'currency',
        'escrow_trust_ref',
        'custody_depository',
        'clearance_stage',
        'status',
        'payout_method',
        'payout_destination_details',
        'client_confirmed_at',
        'client_signature_hash',
        'is_enabled',
        'notes',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'legal_fee_percent' => 'decimal:2',
        'legal_fee_amount' => 'decimal:2',
        'expenses_amount' => 'decimal:2',
        'net_client_payout' => 'decimal:2',
        'client_confirmed_at' => 'datetime',
        'is_enabled' => 'boolean',
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
