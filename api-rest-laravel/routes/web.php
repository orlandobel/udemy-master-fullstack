<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PruebasController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Models\User;

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

/* Rutas de prueba */
Route::get('/pruevas/{nombre?}', function($nombre = null) {
    $texto = '<h2>Texto desde una ruta</h2>';
    $texto .= ' Nombre: '.$nombre;

    return view('pruebas', [ 'texto' => $texto]);
});

Route::get('/animales', [PruebasController::class, 'index']);

Route::get('/testorm', [PruebasController::class, 'testORM']);

/* Rutas de API */
    // Rutas de pruebas
    Route::get('/usuario/pruebas', [UserController::class, 'pruebas']);
    Route::get('/categoria/pruebas', [CategoryController::class, 'pruebas']);
    Route::get('/entrada/pruebas', [PostController::class, 'pruebas']);


    Route::post('/api/register', [UserController::class, 'register']);
    Route::post('/api/login', [UserController::class, 'login']);
    Route::put('/api/user/update', [UserController::class, 'update']);