<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Root -> redirige a tasks (útil para /)
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas públicas (index públicos)
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

// Dashboard: redirigir al listado de tareas (evita vistas vacías tras el login)
Route::get('/dashboard', function () {
    return redirect()->route('tasks.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas por login
Route::middleware('auth')->group(function () {    

    // Recursos (index ya está definido públicamente arriba)
    Route::resource('tasks', TaskController::class)->except(['index']);    
});

// Autenticación (Breeze / Fortify / auth.php)
require __DIR__ . '/auth.php';
