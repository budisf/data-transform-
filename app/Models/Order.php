<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'id_product',
        'id_transaction',
        'qty',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    public function transaction()
    {
        return $this->belongsTo(transaction::class);
    }

}
