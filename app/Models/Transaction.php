<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    protected $hidden = 
    [
        // ki di isi apaan fan?
    ];

    public function user()
    {
        $this->belongsTo
        (
            //
        );
    }

    public function rating()
    {
        return $this->hasMany
        (
            //
        );
    }
}
