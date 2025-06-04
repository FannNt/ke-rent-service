<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';

    protected $fillable = [
        'order_id',
        'transaction_id',
        'methods',
        'status'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
