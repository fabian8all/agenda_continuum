<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/solicitudes/nueva', function () {
    return view('solicitudes.nueva');
})->name('solicitudes.crear');
