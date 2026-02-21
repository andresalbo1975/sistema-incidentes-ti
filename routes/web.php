<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenteController;

// Redirección al crear por defecto
Route::get('/', function () {
    return redirect()->route('incidentes.create');
});

// Rutas del Sistema de Incidentes
Route::prefix('incidentes')->group(function () {
    Route::get('/', [IncidenteController::class, 'index'])->name('incidentes.index');
    Route::get('/crear', [IncidenteController::class, 'create'])->name('incidentes.create');
    Route::post('/', [IncidenteController::class, 'store'])->name('incidentes.store');
});