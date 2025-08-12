<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetPassWordToken extends Model
{
    use HasFactory;
    protected $table = 'reset_password_tokens';
    protected $fillable = [
        'email',
        'token',
        'expries_at',
        'use_at',
    ];
    protected $dates= ['expires_at','use_at'];
    public $timestamps = true;
     protected $casts = [
        'expries_at' => 'datetime',
        'use_at' => 'datetime',
    ];
}
