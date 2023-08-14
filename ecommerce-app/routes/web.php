<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;
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

Route::get('/prueba', [PruebaController::class, 'testOrm']);
Route::post('/api/user/register',[UserController::class, 'register']);
Route::post('/api/user/login',[UserController::class, 'login']);
Route::put('/api/user/update',[UserController::class, 'update']);
Route::post('/api/user/upload',[UserController::class, 'upload'])->middleware(ApiAuthMiddleware::class);
Route::get('/api/user/avatar/{filename}',[UserController::class, 'getImage']);
route::get('/api/user/detail/{id}',[UserController::class, 'detail']);
route::get('/api/user/all', [UserController::class, 'storeUser']);
route::delete('/api/user/{id}', [UserController::class, 'destroy'])->middleware(ApiAutMiddleware::class);

route::resource('/api/category', CategoryController::class);

route::resource('/api/product', ProductController::class);
