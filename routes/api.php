<?php

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
});
