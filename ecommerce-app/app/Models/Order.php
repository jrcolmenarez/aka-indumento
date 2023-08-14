<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Order_Detail;

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

    private function orderDetail(){
        return $this->hasMany(Order_Detail::class);
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
