<?php

use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Usuario\AuthController;
use App\Http\Controllers\Usuario\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'registrarUsuario']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['jwt.auth']], function () {
    //usuario
    Route::get('/getusers', [UserController::class, 'getUsers']);
    Route::get('/perfil/{idUsuario}', [UserController::class, 'perfil']);
    Route::post('/logout', [AuthController::class, 'login']);

    //producto
    Route::get('/productos', [ProductoController::class, 'getProductos']);
    Route::get('/productos/detalle/{idProducto}', [ProductoController::class, 'detalleProducto']);
    Route::post('/productos/create/{idCategoria}', [ProductoController::class, 'agregarProducto']);
    Route::post('/productos/stock/{idProducto}', [ProductoController::class, 'agregarStock']);

    //review
    Route::post('/review/addReview/{idProducto}', [ReviewController::class, 'agregarComentario']);

    //carrito
    Route::get('/carrito/{idUsuario}', [CarritoController::class, 'getCarrito']);
    Route::post('/carrito/add/{idProducto}', [CarritoController::class, 'addProducto']);
    Route::delete('/carrito/remove/{idCarritoProducto}', [CarritoController::class, 'quitarProducto']);

    //compra
    Route::get('/compra/{idusuario}', [CompraController::class, 'getCompra']);
    Route::post('/compra/addCompra/{idCarrito}', [CompraController::class, 'addCompra']);
    Route::post('/compra/cancelCompra/{idCompra}', [CompraController::class, 'cancelCompra']);
});
