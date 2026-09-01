<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address',
        'pin_hash', 'is_temp_password', 'is_first_login',
        'assigned_attorney_id', 'preferred_currency', 'device_history',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'pin_hash',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function blog()
    {
        return $this->hasMany(Blog::class, 'user_id','id');
    }

    public function attorney(){
        return $this->hasOne(Attorney::class, 'user_id', 'id');
    }

    public function reliefRequests()
    {
        return $this->hasMany(ReliefRequest::class, 'user_id','id');
    }

    public function conversation()
    {
        return $this->belongsToMany(Conversation::class, 'conversations_user',  'user_id', 'conversation_id')
        ->with(['unreadMessages']);
    }

    public function messages()
    {
        return $this->belongsTo(Message::class, 'user_id', 'id');
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'user_id', 'id')->where(['read'=>false]);
    }

    public function staffDetail()
    {
        return $this->hasOne(StaffDetail::class, 'user_id', 'id');
    }

    public function staffTimeLogs()
    {
        return $this->hasMany(StaffTimeLog::class, 'user_id', 'id');
    }

    public function staffLoginLogs()
    {
        return $this->hasMany(StaffLoginLog::class, 'user_id', 'id');
    }

    public function staffTasks()
    {
        return $this->hasMany(StaffTask::class, 'staff_user_id', 'id');
    }

    public function staffPayoutRequests()
    {
        return $this->hasMany(StaffPayoutRequest::class, 'user_id', 'id');
    }

    public function staffLedgerEntries()
    {
        return $this->hasMany(StaffLedgerEntry::class, 'user_id', 'id');
    }

    public function clientCases()
    {
        return $this->hasMany(ClientCase::class, 'client_id');
    }

    public function attorneyCases()
    {
        return $this->hasMany(ClientCase::class, 'attorney_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    public function assignedAttorney()
    {
        return $this->belongsTo(User::class, 'assigned_attorney_id');
    }

    public function getAssignedCounselAttribute()
    {
        if ($this->assigned_attorney_id && $this->assignedAttorney) {
            return $this->assignedAttorney;
        }
        return User::role(['attorney', 'admin'])->first();
    }

    public function kycDocuments()
    {
        return $this->hasMany(ClientKycDocument::class, 'client_id');
    }

    public function caseSettlements()
    {
        return $this->hasMany(CaseSettlement::class, 'client_id');
    }

    public function systemAuditLogs()
    {
        return $this->hasMany(SystemAuditLog::class, 'user_id');
    }
}
