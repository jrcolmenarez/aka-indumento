<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;
use App\Models\User;
use App\Models\Shopping_Cart;
use PhpParser\Node\Expr\Cast;


class PruebaController extends Controller
{
    public function testOrm(){
        $otro = Category::all();

        foreach ($otro as $cat){
            echo "<h1>".$cat->id."</h1>";
            echo "<h1>".$cat->name."</h1>";
            foreach($cat->subcategory as $sub){
                echo "<h3> Sub Categoria: ".$sub->name."</3>";
            }
            echo "<h1>".$cat->description."</h1>";
            echo "<hr>";
        }

        $usuario = User::all();
        foreach ($usuario as $user){
            echo "<h1>".$user->name."</h1>";
            foreach ($user->product as $prod){
                echo "<h1>".$prod->name."</h1>";
            }

        }

        $carro = Shopping_Cart::all();
        foreach ($carro as $cart){
            echo "Carrito: ".$cart->id;
            echo "<br>nombre: ".$cart->user->name;
            echo "<br> Articulo: ".$cart->products->name;
        }

       /* $categoria = Subcategory::all();
        var_dump($categoria);

        echo "<h1>entre en la wea</h1>";*/
    }
}
