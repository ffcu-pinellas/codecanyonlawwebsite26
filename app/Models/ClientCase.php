<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientCase extends Model
{
    use HasFactory;

    protected $table = 'client_cases';

    protected $fillable = [
        'case_number',
        'title',
        'description',
        'client_id',
        'attorney_id',
        'status',
        'court_date',
        'lifecycle_stage',
        'progress_percent',
        'claim_amount',
        'settled_amount',
        'currency',
        'show_financial_schedule',
        'show_settlement_escrow',
        'show_jurisdiction_tracker',
        'schedule_title',
        'settlement_title',
        'jurisdiction_title',
    ];

    protected $casts = [
        'court_date' => 'datetime',
        'claim_amount' => 'decimal:2',
        'settled_amount' => 'decimal:2',
        'show_financial_schedule' => 'boolean',
        'show_settlement_escrow' => 'boolean',
        'show_jurisdiction_tracker' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function attorney()
    {
        return $this->belongsTo(User::class, 'attorney_id');
    }

    public function documents()
    {
        return $this->hasMany(CaseDocument::class, 'case_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'case_id');
    }

    public function milestones()
    {
        return $this->hasMany(CaseMilestone::class, 'case_id');
    }

    public function financialSchedules()
    {
        return $this->hasMany(CaseFinancialSchedule::class, 'case_id');
    }

    public function settlement()
    {
        return $this->hasOne(CaseSettlement::class, 'case_id');
    }

    public function jurisdictions()
    {
        return $this->hasMany(CaseJurisdiction::class, 'case_id');
    }

    public function kycDocuments()
    {
        return $this->hasMany(ClientKycDocument::class, 'case_id');
    }
}
