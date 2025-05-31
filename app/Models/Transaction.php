<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';


    protected $fillable = [
        'product_id',
        'user_id',
        'total_price',
        'status',
        'rent_day',
        'rental_start',
        'rental_end'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
