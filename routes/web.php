<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RipsDataController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

// Grupo de rutas para RipsData
Route::prefix('ripsdata')->group(function () {
    Route::get('/', [RipsDataController::class, 'index'])->name('ripsdata.index');
    Route::get('/create', [RipsDataController::class, 'create'])->name('ripsdata.create');
    Route::post('/', [RipsDataController::class, 'store'])->name('ripsdata.store');
    Route::get('/{ripsdata}', [RipsDataController::class, 'show'])->name('ripsdata.show');
    Route::get('/{ripsdata}/edit', [RipsDataController::class, 'edit'])->name('ripsdata.edit');
    Route::put('/{ripsdata}', [RipsDataController::class, 'update'])->name('ripsdata.update');
    Route::delete('/{ripsdata}', [RipsDataController::class, 'destroy'])->name('ripsdata.destroy');
});

// Rutas para Dashboard (sin cambios)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard/compare', [DashboardController::class, 'compare'])->name('dashboard.compare');