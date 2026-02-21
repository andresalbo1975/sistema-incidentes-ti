<?php

namespace App\Http\Controllers; // Asegúrate que solo diga App una vez

use App\Models\Incidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IncidenteController extends Controller 
    // ... resto del código que te pasé antes
{
    public function index()
    {
        // Recuperamos todos los incidentes desde Postgres
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
            'descripcion' => 'required|string|max:1000',
            'id_categoria' => 'required|integer',
            'id_prioridad' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            $proximoId = (Incidente::max('id') ?? 0) + 1;
            $ticketNro = 'TIC-' . date('Y') . '-' . str_pad($proximoId, 4, '0', STR_PAD_LEFT);

            $incidente = new Incidente();
            $incidente->ticket_nro = $ticketNro;
            $incidente->descripcion = $request->descripcion;
            $incidente->es_incidente_mayor = $request->has('es_incidente_mayor');
            $incidente->estado = 'Registrado';
            $incidente->save();

            DB::commit();

            return redirect()->route('incidentes.index')
                ->with('success', "Ticket {$ticketNro} registrado correctamente.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }
}