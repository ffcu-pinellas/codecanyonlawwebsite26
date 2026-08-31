<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseJurisdiction extends Model
{
    use HasFactory;

    protected $table = 'case_jurisdictions';

    protected $fillable = [
        'case_id',
        'jurisdiction_name',
        'court_venue',
        'action_type',
        'docket_number',
        'status',
        'filing_date',
        'notes',
        'is_enabled',
    ];

    protected $casts = [
        'filing_date' => 'date',
        'is_enabled' => 'boolean',
    ];

    public function clientCase()
    {
        return $this->belongsTo(ClientCase::class, 'case_id');
    }
}
