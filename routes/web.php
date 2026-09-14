<?php

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

Route::get('/solicitudes/nueva', function () {
    return view('solicitudes.nueva');
})->name('solicitudes.crear');
