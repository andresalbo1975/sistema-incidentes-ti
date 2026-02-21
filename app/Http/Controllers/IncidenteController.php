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
   // Generamos el número de ticket
    $ultimoId = \App\Models\Incidente::max('id') ?? 0;
    $ticketNro = 'TIC-' . date('Y') . '-' . str_pad($ultimoId + 1, 4, '0', STR_PAD_LEFT);

    // Creamos el registro usando los datos del request
    \App\Models\Incidente::create([
        'ticket_nro' => $ticketNro,
        'descripcion' => $request->descripcion, // <--- Verifica que el nombre coincida con el textarea
        'es_incidente_mayor' => $request->has('es_incidente_mayor'),
        'estado' => 'Registrado',
    ]);

    return redirect()->route('incidentes.index');
}

        try {
            DB::beginTransaction();

            $ultimoId = Incidente::max('id') ?? 0;
            $ticketNro = 'TIC-' . date('Y') . '-' . str_pad($ultimoId + 1, 4, '0', STR_PAD_LEFT);

            $incidente = new Incidente();
            $incidente->descripcion = $request->input('descripcion'); // Verifica que el nombre coincida
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