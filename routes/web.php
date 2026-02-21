<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenteController;

// Redirección inicial
Route::get('/', function () {
    return redirect()->route('incidentes.create');
});

// Grupo de rutas para Incidentes
Route::prefix('incidentes')->group(function () {
    // 1. Mostrar formulario de registro (Bloque: Iniciar Registro)
    Route::get('/crear', [IncidenteController::class, 'create'])->name('incidentes.create');

    // 2. Procesar el guardado (Bloque: Registrar y Categorizar)
    Route::post('/', [IncidenteController::class, 'store'])->name('incidentes.store');

    // 3. Ver listado de incidentes (Para seguimiento)
    Route::get('/', [IncidenteController::class, 'index'])->name('incidentes.index');
});