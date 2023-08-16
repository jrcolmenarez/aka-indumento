<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table='orders';
    protected $fillable = [
        'id',
        'user_id',
        'state',
        'address_order',
        'paymen_methods'
    ];

    private function orderdetail(){
        return $this->hasMany(Order_Detail::class, 'order_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
