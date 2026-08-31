<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemAuditLog extends Model
{
    use HasFactory;

    protected $table = 'system_audit_logs';

    protected $fillable = [
        'user_id',
        'user_type',
        'action_key',
        'details',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function logAction($actionKey, $details, $userId = null, $userType = 'client')
    {
        try {
            return self::create([
                'user_id' => $userId ?? (auth()->check() ? auth()->id() : null),
                'user_type' => $userType,
                'action_key' => $actionKey,
                'details' => is_array($details) ? json_encode($details) : (string)$details,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            \Log::error('SystemAuditLog error: ' . $e->getMessage());
            return null;
        }
    }
}
