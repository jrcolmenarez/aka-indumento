<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shopping_Cart extends Model
{
    use HasFactory;
    protected $table='shopping_cart';

    protected $fillable = [
        'id',
        'user_id',
        'product_id',
        'amount'
    ];

    public function products(){
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
