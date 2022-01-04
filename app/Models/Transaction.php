<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = [
        'id_user',
        'transaction_time',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'id_transaction');
    }
}
