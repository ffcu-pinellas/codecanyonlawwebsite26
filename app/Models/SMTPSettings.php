<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMTPSettings extends Model
{
    use HasFactory;

    protected $fillable = ["mail_driver","mail_host","mail_port","mail_username","mail_password","mail_encryption","mail_from_address"];
}
