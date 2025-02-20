<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersChat extends Model
{
    protected $guarded = [
        'users_chat'
    ];
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
