<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseFinancialSchedule extends Model
{
    use HasFactory;

    protected $table = 'case_financial_schedules';

    protected $fillable = [
        'case_id',
        'item_category',
        'item_description',
        'reference_code',
        'amount',
        'currency',
        'status',
        'entry_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'entry_date' => 'date',
    ];

    public function clientCase()
    {
        return $this->belongsTo(ClientCase::class, 'case_id');
    }
}
