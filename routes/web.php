<?php

/*

Título: Entrega Final

Autor: Miguel Ángel Rama Martínez.

Data modificación: 17/06/2024

Versión 1.0

*/

use App\Http\Controllers\AemetController;
use App\Http\Controllers\AlbaranController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Ruta para los Idiomas
Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
})->name('idioma');

// Las funciones sobre usuarios solo puede realizarlas el admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('listaxeusuarios',[UsuarioController::class, 'listaxeusuarios'])->name('listaxeusuarios');
    Route::get('novousuario',[UsuarioController::class, 'novousuario']);
    Route::post('novousuario',[UsuarioController::class, 'crearnovousuario']);
    Route::get('modusuario/{id}',[UsuarioController::class, 'modusuario']);
    Route::put('modusuario/{id}',[UsuarioController::class, 'editmodusuario']);
    Route::get('eliminarusuario/{id}',[UsuarioController::class, 'eliminarusuario']);
});

// Necesitamos que el usuario se identifique para entrar a estas funciones de ALBARANES
// Todas estas rutas hacen referencia a las vistas de blade
Route::middleware(['auth'])->group(function () {
    Route::get('listaxealbarans',[AlbaranController::class, 'listaxealbarans'])->name('listaxealbarans');
    Route::get('listaxealbarans/{id}',[AlbaranController::class, 'veralbaran']);
    Route::get('novoalbaran',[AlbaranController::class, 'novoalbaran']);
    Route::post('novoalbaran',[AlbaranController::class, 'crearnovoalbaran']);
    Route::get('novoalbaransalida',[AlbaranController::class, 'novoalbaransalida']);
    Route::post('novoalbaransalida',[AlbaranController::class, 'crearnovoalbaransalida']);
    Route::get('modalbaran/{id}',[AlbaranController::class, 'modalbaran']);
    Route::put('modalbaran/{id}',[AlbaranController::class, 'editmodalbaran']);
    Route::get('modalbaransalida/{id}',[AlbaranController::class, 'modalbaransalida']);
    Route::put('modalbaransalida/{id}',[AlbaranController::class, 'editmodalbaransalida']);
    Route::get('eliminaralbaran/{id}',[AlbaranController::class, 'eliminaralbaran']);
    Route::get('eliminaralbaransalida/{id}',[AlbaranController::class, 'eliminaralbaransalida']);

});

// Necesitamos que el usuario se identifique para entrar a estas funciones de PERFILES
// Todas estas rutas hacen referencia a las vistas de blade
Route::middleware(['auth'])->group(function () {
    Route::get('verperfil',[PerfilController::class, 'verperfil']);
    Route::post('modperfil',[PerfilController::class, 'editmodperfil'])->name('modperfil');
    Route::put('modperfil/{id}',[PerfilController::class, 'editdatosperfil']);

});

// Para modificar la imagen de perfil
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('modperfil', [PerfilController::class, 'editmodperfil']);
});

// Necesitamos que el usuario se identifique para entrar a estas funciones de MATERIALES
Route::middleware(['auth'])->group(function () {
    Route::get('listaxematerials',[MaterialController::class, 'listaxematerials'])->name('listaxematerials');
    Route::get('novomaterial',[MaterialController::class, 'novomaterial']);
    Route::post('novomaterial',[MaterialController::class, 'crearnovomaterial']);
    Route::get('modmaterial/{id}',[MaterialController::class, 'modmaterial']);
    Route::put('modmaterial/{id}',[MaterialController::class, 'editmodmaterial']);
    Route::get('eliminarmaterial/{id}',[MaterialController::class, 'eliminarmaterial']);
    Route::get('buscarmaterials',[MaterialController::class, 'buscarmaterials']);
    Route::post('buscarmaterial',[MaterialController::class, 'buscarmaterial']);
});

// Necesitamos que el usuario se identifique para entrar a estas funciones de PRODUCTOS
Route::middleware(['auth'])->group(function () {
    Route::get('listaxeproductos',[ProductoController::class, 'listaxeproductos'])->name('listaxeproductos');
    Route::get('listaxeproductos/{id}',[ProductoController::class, 'verproducto']);
    Route::get('novoproducto',[ProductoController::class, 'novoproducto'])->name('novoproducto');
    Route::post('novoproducto',[ProductoController::class, 'crearnovoproducto']);
    Route::get('modproducto/{id}',[ProductoController::class, 'modproducto']);
    Route::put('modproducto/{id}',[ProductoController::class, 'editmodproducto']);
    Route::get('eliminarproducto/{id}',[ProductoController::class, 'eliminarproducto']);
});

// Necesitamos que el usuario se identifique para entrar a estas funciones de EMPRESAS
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('listaxeempresas',[EmpresaController::class, 'listaxeempresas'])->name('listaxeempresas');
    Route::get('novaempresa',[EmpresaController::class, 'novaempresa']);
    Route::post('novaempresa',[EmpresaController::class, 'crearnovaempresa']);
    Route::get('modempresa/{id}',[EmpresaController::class, 'modempresa']);
    Route::put('modempresa/{id}',[EmpresaController::class, 'editmodempresa']);
    Route::get('eliminarempresa/{id}',[EmpresaController::class, 'eliminarempresa']);
});

//Controlador para usar la API de Aemet
Route::middleware(['auth'])->group(function () {
    Route::get('verprediccion',[AemetController::class, 'verprediccion'])->name('verprediccion');
});

require __DIR__.'/auth.php';
