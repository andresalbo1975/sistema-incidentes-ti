<?php

namespace App\Http\Controllers;

use App\Models\Incidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncidenteController extends Controller
{
    /**
     * Muestra la lista de incidentes registrados.
     */
    public function index()
    {
        $incidentes = Incidente::orderBy('created_at', 'desc')->get();
        return view('incidentes.index', compact('incidentes'));
    }

    /**
     * Muestra el formulario de registro inicial (BPMN: Iniciar Registro).
     */
    public function create()
    {
        return view('incidentes.create');
    }

    /**
     * Procesa el registro, genera el ticket y evalúa el flujo.
     */
    public function store(Request $request)
    {
        // Validación de datos de entrada
        $request->validate([
            'descripcion' => 'required|string|max:1000',
            'id_categoria' => 'required|integer',
            'id_prioridad' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            // Lógica de generación de Ticket (TIC-AAAA-ID)
            $proximoId = (Incidente::max('id') ?? 0) + 1;
            $ticketNro = 'TIC-' . date('Y') . '-' . str_pad($proximoId, 4, '0', STR_PAD_LEFT);

            // Creación del registro en PostgreSQL
            $incidente = new Incidente();
            $incidente->ticket_nro = $ticketNro;
            $incidente->descripcion = $request->descripcion;
            $incidente->es_incidente_mayor = $request->has('es_incidente_mayor'); // Checkbox del formulario
            $incidente->estado = 'Registrado';
            // $incidente->user_id = auth()->id(); // Descomentar cuando tengas login
            $incidente->save();

            DB::commit();

            // Lógica de decisión según el BPMN (Rombo: ¿Es un Incidente Mayor?)
            $mensaje = "Incidente {$ticketNro} registrado exitosamente.";
            if ($incidente->es_incidente_mayor) {
                $mensaje .= " ALERTA: Se ha activado el procedimiento de Incidente Mayor.";
            }

            return redirect()->route('incidentes.index')->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Error al registrar el incidente: ' . $e->getMessage());
        }
    }
}