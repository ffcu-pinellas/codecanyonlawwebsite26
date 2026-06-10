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
        'title',
        'file_path',
        'file_type',
        'file_size',
        'is_client_uploaded',
    ];

    public function clientCase()
    {
        return $this->belongsTo(ClientCase::class, 'case_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
