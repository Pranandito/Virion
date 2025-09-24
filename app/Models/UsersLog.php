<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersLog extends Model
{
    protected $primaryKey = 'log_id';
    protected $fillable = [
        'user_id',
        'last_login',
        'last_location',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
