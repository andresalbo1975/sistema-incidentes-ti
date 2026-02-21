<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Incidente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-lg mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Registrar Incidente</h2>
        <form action="{{ route('incidentes.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Descripción</label>
                <textarea name="descripcion" class="w-full border p-2 rounded" required></textarea>
            </div>
            <div class="mb-4 text-red-600 font-bold">
                <input type="checkbox" name="es_incidente_mayor" value="1"> ¿Es un Incidente Mayor?
            </div>
            <input type="hidden" name="id_categoria" value="1">
            <input type="hidden" name="id_prioridad" value="1">
            <button class="bg-blue-600 text-white px-4 py-2 rounded w-full">Crear Ticket</button>
        </form>
    </div>
</body>
</html>