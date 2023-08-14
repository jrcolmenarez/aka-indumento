<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Product;
use Category;

class Subcategory extends Model
{
    use HasFactory;
    protected $table='subcategories';
    protected $fillable = [
        'id',
        'name',
        'description',
        'category_id'
    ];

    public function products(){
        return $this->hasMany(Products::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
