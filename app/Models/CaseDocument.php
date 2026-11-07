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

    protected static function booted()
    {
        static::saving(function ($doc) {
            // Guarantee legacy columns are always populated
            if (empty($doc->title) && !empty($doc->document_title)) {
                $doc->title = $doc->document_title;
            }
            if (empty($doc->user_id) && !empty($doc->client_id)) {
                $doc->user_id = $doc->client_id;
            }

            // Safely guard against missing columns on remote databases that haven't migrated yet
            try {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('case_documents', 'client_id')) {
                    unset($doc->client_id);
                } elseif (empty($doc->client_id) && !empty($doc->user_id)) {
                    $doc->client_id = $doc->user_id;
                }

                if (!\Illuminate\Support\Facades\Schema::hasColumn('case_documents', 'document_title')) {
                    unset($doc->document_title);
                } elseif (empty($doc->document_title) && !empty($doc->title)) {
                    $doc->document_title = $doc->title;
                }
            } catch (\Throwable $e) {
                // If schema check fails, safely unset newly added columns
                unset($doc->client_id);
                unset($doc->document_title);
            }
        });
    }

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
