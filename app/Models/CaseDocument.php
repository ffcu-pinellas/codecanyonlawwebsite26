<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseDocument extends Model
{
    use HasFactory;

    protected $table = 'case_documents';

    protected $fillable = [
        'case_id',
        'user_id',
        'client_id',
        'title',
        'document_title',
        'file_path',
        'file_type',
        'file_size',
        'is_client_uploaded',
        'document_type',
        'requires_signature',
        'is_signed',
        'signed_at',
        'custom_content',
        'visibility',
    ];

    protected $casts = [
        'requires_signature' => 'boolean',
        'is_signed'          => 'boolean',
        'is_client_uploaded' => 'boolean',
        'signed_at'          => 'datetime',
    ];

    public function clientCase()
    {
        return $this->belongsTo(ClientCase::class, 'case_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
