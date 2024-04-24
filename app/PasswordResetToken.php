<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    protected $table = 'users_password_resets_tokens';
    protected $primaryKey = 'idToken';

    protected $fillable = [
        'idToken', 'idUser', 'token', 'expires_at'
    ];

    
    public function user()
    {
        return $this->belongsTo('App\User', 'idUser', 'idUser');
    }    
}
