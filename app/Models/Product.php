<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'image_publicId'
    ];
    public function user()
    {
        $this->belongsTo(User::class, 'user_id');
    }
    public function rating()
    {
        return $this->hasMany(Rating::class, 'product_id');
    }
}
