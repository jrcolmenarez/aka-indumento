<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Models\Order_Detail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['middleware' => ['cors']], function () {
    Route::get('/prueba', [PruebaController::class, 'testOrm']);
    Route::post('/api/user/register',[UserController::class, 'register']);
    Route::post('/api/user/login',[UserController::class, 'login']);
    Route::put('/api/user/update',[UserController::class, 'update']);
    Route::post('/api/user/upload',[UserController::class, 'upload'])->middleware(ApiAuthMiddleware::class);
    Route::get('/api/user/avatar/{filename}',[UserController::class, 'getImage']);
    route::get('/api/user/detail/{id}',[UserController::class, 'detail']);
    route::get('/api/user/all', [UserController::class, 'storeUser']);
    route::delete('/api/user/{id}', [UserController::class, 'destroy'])->middleware(ApiAutMiddleware::class);

    //category rutas
    route::resource('/api/category', CategoryController::class);
    //subcategory
    route::resource('/api/subcategory', SubcategoryController::class);

    //Product Rutas
    route::resource('/api/product', ProductController::class);
    route::post('/api/product/upload', [ProductController::class, 'upload']);
    route::get('/api/product/image/{filename}', [ProductController::class, 'getImage']);

    //Orders Rutas
    route::resource('/api/order', OrderController::class);

    //ordersDetail Rutas
    route::resource('/api/orderdetail', Order_Detail::class);

    //shopping_cart rutas
    route::resource('/api/shoppingcart', ShoppingCartController::class);
    route::post('/api/shoppingcart/emptycart', [ShoppingCartController::class, 'empty']);
});




