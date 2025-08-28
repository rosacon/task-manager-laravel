<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PruebaFetchyAxiosController;
use App\Http\Controllers\PruebaSelectJsController;
use App\Http\Controllers\PruebaPaisesController;
use App\Http\Controllers\ApiTestTryCatchController;
use App\Http\Controllers\CscController;

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

    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::post('/locations/states', [LocationController::class, 'getStates'])->name('locations.states');
    Route::post('/locations/cities', [LocationController::class, 'getCities'])->name('locations.cities');
    Route::get('/locations/refresh', [LocationController::class, 'refreshCountries'])->name('locations.refresh');
    Route::get('locations/refresh-states/{country}', [LocationController::class, 'refreshStates'])
        ->name('locations.refresh.states');
    Route::get('locations/refresh-countries-json', [LocationController::class, 'refreshCountriesJson']);
    Route::get('/pruebaselectjs', [PruebaSelectJsController::class, 'index'])->name('pruebaselectjs.index');
    Route::get('/pruebafetchyaxios', [PruebaFetchyAxiosController::class, 'index'])->name('pruebafetchyaxios.index');
    Route::get('/pruebapaises', [PruebaPaisesController::class, 'index'])->name('pruebapaises.index');
    Route::get('/pruebapaises/refresh', [PruebaPaisesController::class, 'refresh'])->name('pruebapaises.refresh');
    Route::get('/apitest', [ApiTestTryCatchController::class, 'getData']);

    Route::prefix('csc')->group(function () {
    Route::get('/countries', [CscController::class, 'countries'])->name('csc.countries');
    Route::get('/states/{countryIso2}', [CscController::class, 'states'])->name('csc.states');
    Route::get('/cities/{countryIso2}/{stateIso2}', [CscController::class, 'cities'])->name('csc.cities');
});
    
});

// Autenticación
require __DIR__ . '/auth.php';
