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
            'id_categoria' => 'required|integer',
            'id_prioridad' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();
            $ultimoId = Incidente::max('id') ?? 0;
            $ticketNro = 'TIC-' . date('Y') . '-' . str_pad($ultimoId + 1, 4, '0', STR_PAD_LEFT);

            $incidente = new Incidente();
            $incidente->ticket_nro = $ticketNro;
            $incidente->descripcion = $request->descripcion;
            $incidente->es_incidente_mayor = $request->has('es_incidente_mayor');
            $incidente->estado = 'Registrado';
            $incidente->save();
            DB::commit();

            return redirect()->route('incidentes.index')->with('success', "Ticket {$ticketNro} creado.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }
}