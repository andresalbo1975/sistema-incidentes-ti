<?php

namespace App\Http\Controllers;

use App\Models\Incidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IncidenteController extends Controller
{
    /**
     * Lista todos los incidentes (Vista de seguimiento)
     */
    public function index()
    {
        $incidentes = Incidente::orderBy('created_at', 'desc')->get();
        return view('incidentes.index', compact('incidentes'));
    }

    /**
     * Muestra el formulario de registro (BPMN: Iniciar Registro)
     */
    public function create()
    {
        return view('incidentes.create');
    }

    /**
     * Procesa el registro y aplica la lógica de negocio
     */
    public function store(Request $request)
    {
        // 1. Validación de campos según el esquema relacional
        $request->validate([
            'descripcion' => 'required|string|max:1000',
            'id_categoria' => 'required|integer',
            'id_prioridad' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            // 2. Lógica de generación de Ticket: TIC-YYYY-000X
            $ultimoId = Incidente::max('id') ?? 0;
            $ticketNro = 'TIC-' . date('Y') . '-' . str_pad($ultimoId + 1, 4, '0', STR_PAD_LEFT);

            // 3. Persistencia en PostgreSQL
            $incidente = new Incidente();
            $incidente->ticket_nro = $ticketNro;
            $incidente->descripcion = $request->descripcion;
            $incidente->es_incidente_mayor = $request->has('es_incidente_mayor');
            $incidente->estado = 'Registrado';
            $incidente->save();

            DB::commit();

            // 4. Lógica de flujo: Notificación si es incidente mayor
            $mensaje = "Ticket {$ticketNro} generado exitosamente.";
            if ($incidente->es_incidente_mayor) {
                $mensaje .= " Se ha activado el protocolo de Incidente Mayor.";
            }

            return redirect()->route('incidentes.index')->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Error al guardar en base de datos: ' . $e->getMessage());
        }
    }
}