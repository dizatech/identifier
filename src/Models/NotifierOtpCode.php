<?php

namespace Dizatech\Identifier\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifierOtpCode extends Model
{
    use HasFactory;
    protected $fillable = ['code','user_id','expires_at','is_expired'];
}
