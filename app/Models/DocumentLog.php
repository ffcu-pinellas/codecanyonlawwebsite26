<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_key',
        'template_title',
        'content',
        'client_id',
        'staff_id',
        'recipient_email',
        'sent_by',
        'sent_to_email',
        'pdf_path',
        'status',
        'action_required',
        'signed_path',
        'admin_notes',
        'recipient_notes',
        'tracking_token',
        'opened_at'
    ];

    protected $casts = [
        'sent_to_email' => 'boolean',
        'opened_at' => 'datetime'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
