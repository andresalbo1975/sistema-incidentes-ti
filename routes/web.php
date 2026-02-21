<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenteController;

// Redirección inicial al listado
Route::get('/', function () {
    return redirect()->route('incidentes.index');
});

// Rutas manuales para controlar el flujo del BPMN
Route::get('/incidentes', [IncidenteController::class, 'index'])->name('incidentes.index');
Route::get('/incidentes/crear', [IncidenteController::class, 'create'])->name('incidentes.create');
Route::post('/incidentes', [IncidenteController::class, 'store'])->name('incidentes.store');