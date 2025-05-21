<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserKtp extends Model
{
    protected $guarded = [];
    protected $hidden = [
        'nik',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
