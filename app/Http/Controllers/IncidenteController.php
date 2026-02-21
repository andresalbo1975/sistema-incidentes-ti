<?php

namespace App\Http\Controllers;

use App\Models\Incidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncidenteController extends Controller
{
    public function index()
    {
        $incidentes = Incidente::orderBy('created_at', 'desc')->get();
        return view('incidentes.index', compact('incidentes'));
    }

    public function create()
    {
        return view('incidentes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Cálculo del número de ticket
            $ultimoId = Incidente::max('id') ?? 0;
            $ticketNro = 'TIC-' . date('Y') . '-' . str_pad($ultimoId + 1, 4, '0', STR_PAD_LEFT);

            // Creación del registro
            $incidente = new Incidente();
            $incidente->ticket_nro = $ticketNro;
            $incidente->descripcion = $request->descripcion; // Captura del textarea
            $incidente->es_incidente_mayor = $request->has('es_incidente_mayor');
            $incidente->estado = 'Registrado';
            $incidente->save();

            DB::commit();

            return redirect()->route('incidentes.index')->with('success', 'Incidente ' . $ticketNro . ' creado con éxito.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Error al guardar: ' . $e->getMessage());
        }
    }
}