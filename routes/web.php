<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\WeatherController;

// Página inicial redirige al dashboard o tareas
Route::get('/', function () {
    return redirect('/tasks');
});

// Rutas públicas:
Route::get('/productos', [ProductController::class, 'index'])->name('productos.index');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

// Dashboard (opcional, según Breeze o Jetstream)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas por login
Route::middleware('auth')->group(function () {
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('tasks', TaskController::class)->except(['index']);
    Route::resource('productos', ProductController::class)->except(['index']);
    Route::resource('categories', CategoryController::class)->except(['index']);

    Route::get('/pokemon', [PokemonController::class, 'index'])->name('pokemon.index');
    Route::get('/', [WeatherController::class, 'index']);
    Route::get('/weather', [WeatherController::class, 'getWeather'])->name('weather.get');
});



// Autenticación
require __DIR__ . '/auth.php';
