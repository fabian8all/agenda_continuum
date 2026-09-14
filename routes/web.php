<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/escenarios', function () {
    return view('escenarios.index');
})->name('escenarios.index');

Route::get('/calendario', function () {
    return view('calendario.index');
})->name('calendario.index');

Route::get('/login', [AuthController::class, 'login'])->middleware('verify.auth')->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/solicitudes/nueva', function () {
    return view('solicitudes.nueva');
})->middleware('verify.auth')->name('solicitudes.crear');

Route::get('/solicitudes', function () {
    return view('solicitudes.gestion');
})->middleware(['verify.auth', 'role:admin,coordinador'])->name('solicitudes.gestion');

Route::get('/administracion/usuarios', function () {
    return view('administracion.usuarios');
})->middleware(['verify.auth', 'role:coordinador'])->name('administracion.usuarios');
