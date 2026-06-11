<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseMilestone extends Model
{
    use HasFactory;

    protected $table = 'case_milestones';

    protected $fillable = [
        'case_id',
        'title',
        'description',
        'status',
        'milestone_date',
    ];

    protected $casts = [
        'milestone_date' => 'datetime',
    ];

    public function clientCase()
    {
        return $this->belongsTo(ClientCase::class, 'case_id');
    }
}
