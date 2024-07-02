<?php

/*

Título: Entrega Final

Autor: Miguel Ángel Rama Martínez.

Data modificación: 17/06/2024

Versión 1.0

*/

use App\Http\Controllers\AemetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AlbaranController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::middleware('auth:sanctum')->group(function () 
{
    Route::post('/logoutAPI', [LoginController::class, 'logoutAPI']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::post('loginAPI', [LoginController::class, 'loginAPI']);

// Rutas para la API

// Rutas protegidas para admin solo para PUT y DELETE
Route::put('albarans/{id}', [AlbaranController::class, 'update'])->middleware(['auth:sanctum', 'admin']);
Route::delete('albarans/{id}', [AlbaranController::class, 'destroy'])->middleware(['auth:sanctum', 'admin']);
// Rutas sin restricción para las demás operaciones CRUD
Route::apiResource('albarans', AlbaranController::class)->middleware('auth:sanctum');

// Rutas protegidas para admin solo para PUT y DELETE
Route::put('materials/{id}', [MaterialController::class, 'update'])->middleware(['auth:sanctum', 'admin']);
Route::delete('materials/{id}', [MaterialController::class, 'destroy'])->middleware(['auth:sanctum', 'admin']);
// Rutas sin restricción para las demás operaciones CRUD
Route::apiResource('materials', MaterialController::class)->middleware('auth:sanctum');
Route::get('aemet',[AemetController::class, 'aemet'])->name('aemet');
Route::post('editarimagen',[PerfilController::class, 'editarimagen'])->name('editarimagen')->middleware('auth:sanctum');

// Rutas protegidas para admin solo para PUT y DELETE
Route::put('productos/{id}', [ProductoController::class, 'update'])->middleware(['auth:sanctum', 'admin']);
Route::delete('productos/{id}', [ProductoController::class, 'destroy'])->middleware(['auth:sanctum', 'admin']);

// Rutas sin restricción para las demás operaciones CRUD
Route::apiResource('productos', ProductoController::class)->middleware('auth:sanctum');

// Rutas protegidas para admin
Route::apiResource('empresas', EmpresaController::class)->middleware('auth:sanctum', 'admin');
Route::apiResource('usuarios', UsuarioController::class)->middleware('auth:sanctum', 'admin');