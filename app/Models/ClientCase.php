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
    ];

    protected $casts = [
        'court_date' => 'datetime',
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
}
