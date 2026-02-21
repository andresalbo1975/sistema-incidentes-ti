<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Incidentes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Incidentes Registrados</h1>
            <a href="{{ route('incidentes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Nuevo Incidente
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="p-3 border text-left">Ticket</th>
                    <th class="p-3 border text-left">Descripción</th>
                    <th class="p-3 border text-center">Estado</th>
                    <th class="p-3 border text-center">Tipo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incidentes as $incidente)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border font-mono font-bold">{{ $incidente->ticket_nro }}</td>
                    <td class="p-3 border text-sm text-gray-600">{{ $incidente->descripcion }}</td>
                    <td class="p-3 border text-center">
                        <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-xs">
                            {{ $incidente->estado }}
                        </span>
                    </td>
                    <td class="p-3 border text-center">
                        @if($incidente->es_incidente_mayor)
                            <span class="text-red-600 font-bold">⚠️ Mayor</span>
                        @else
                            <span class="text-gray-500">Estándar</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>