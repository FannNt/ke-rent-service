<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    public function user()
    {
        $this->belongsTo(User::class);
    }
    public function rating()
    {
        return $this->hasMany(Rating::class, 'product_id');
    }
}
