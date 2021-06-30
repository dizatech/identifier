<?php

namespace Dizatech\Identifier\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentifierOtpCode extends Model
{
    use HasFactory;
    protected $fillable = ['code','user_id','expired_at','is_expired'];
}
