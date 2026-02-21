<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Incidentes - TI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Gestión de Incidentes</h1>
            <a href="{{ route('incidentes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                + Nuevo Incidente
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-xl overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-left text-xs font-semibold uppercase tracking-wider">
                        <th class="px-5 py-3 border-b">Ticket</th>
                        <th class="px-5 py-3 border-b">Descripción</th>
                        <th class="px-5 py-3 border-b">Estado</th>
                        <th class="px-5 py-3 border-b">Tipo</th>
                        <th class="px-5 py-3 border-b">Fecha</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach($incidentes as $incidente)
                    <tr>
                        <td class="px-5 py-5 border-b font-bold">{{ $incidente->ticket_nro }}</td>
                        <td class="px-5 py-5 border-b text-sm">{{ Str::limit($incidente->descripcion, 50) }}</td>
                        <td class="px-5 py-5 border-b">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $incidente->estado }}
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b">
                            @if($incidente->es_incidente_mayor)
                                <span class="text-red-600 font-bold flex items-center">
                                    ⚠️ Incidente Mayor
                                </span>
                            @else
                                <span class="text-gray-500">Estándar</span>
                            @endif
                        </td>
                        <td class="px-5 py-5 border-b text-sm">
                            {{ $incidente->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>