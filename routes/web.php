<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RipsDataController;
use App\Http\Controllers\Auth\RegisterController; // Add this import
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Redirección inicial
Route::get('/', function () {
    return redirect('/login');
});

// Rutas de autenticación
Auth::routes(['register' => false]); // Esto deshabilita las rutas de registro automáticas
// Ruta para mostrar el formulario de registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
    ->middleware('guest')
    ->name('register');

// Ruta para procesar el registro (usa register() en lugar de store())
Route::post('/register', [RegisterController::class, 'register'])
    ->middleware('guest');

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/dashboard/compare', [DashboardController::class, 'compare'])->name('dashboard.compare');

    // RipsData
    Route::prefix('ripsdata')->group(function () {
        Route::get('/', [RipsDataController::class, 'index'])->name('ripsdata.index');
        Route::get('/create', [RipsDataController::class, 'create'])->name('ripsdata.create');
        Route::post('/', [RipsDataController::class, 'store'])->name('ripsdata.store');
        Route::get('/{ripsdata}', [RipsDataController::class, 'show'])->name('ripsdata.show');
        Route::get('/{ripsdata}/edit', [RipsDataController::class, 'edit'])->name('ripsdata.edit');
        Route::put('/{ripsdata}', [RipsDataController::class, 'update'])->name('ripsdata.update');
        Route::delete('/{ripsdata}', [RipsDataController::class, 'destroy'])->name('ripsdata.destroy');
    });
});