<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Detail extends Model
{
    use HasFactory;
    protected $table='order_details';

    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'amount',
        'price_unit',
        'total'
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function user(){

    }

}
