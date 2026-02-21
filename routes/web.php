<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidenteController;

Route::get('/', function () {
    return redirect()->route('incidentes.index');
});

Route::resource('incidentes', IncidenteController::class);