namespace App\App\Http\Controllers;

use App\Models\Incidente;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IncidenteController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validación (Basada en los campos del BPMN)
        $validated = $request->validate([
            'descripcion' => 'required|string',
            'id_categoria' => 'required|integer',
            'id_prioridad' => 'required|integer',
            'es_incidente_mayor' => 'boolean'
        ]);

        // 2. Lógica de Negocio: Generar Número de Ticket automático
        // Ejemplo: TIC-2026-001
        $ultimoId = Incidente::max('id') ?? 0;
        $ticketNro = 'TIC-' . date('Y') . '-' . str_pad($ultimoId + 1, 3, '0', STR_PAD_LEFT);

        // 3. Crear el registro (Equivale al bloque "Registrar Solicitud" del BPMN)
        $incidente = Incidente::create([
            'ticket_nro' => $ticketNro,
            'descripcion' => $validated['descripcion'],
            'categoria_id' => $validated['id_categoria'],
            'prioridad_id' => $validated['id_prioridad'],
            'es_incidente_mayor' => $request->has('es_incidente_mayor') ? $request->es_incidente_mayor : false,
            'estado' => 'Registrado',
            'user_id' => auth()->id() // Asumiendo que hay login
        ]);

        // 4. Lógica de Flujo (Rombos de decisión del BPMN)
        if ($incidente->es_incidente_mayor) {
            // Aquí dispararíamos el "Procedimiento de Incidente Mayor"
            return response()->json([
                'message' => 'Alerta: Incidente Mayor registrado. Notificando a especialistas.',
                'ticket' => $incidente->ticket_nro
            ], 201);
        }

        return response()->json([
            'message' => 'Incidente registrado exitosamente',
            'ticket' => $incidente->ticket_nro
        ], 201);
    }
}