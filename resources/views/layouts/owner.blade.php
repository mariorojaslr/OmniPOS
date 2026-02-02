<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MultiPOS | Owner</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-800">
<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg hidden md:flex flex-col">
        <div class="p-6 text-xl font-bold border-b">
            MultiPOS
            <span class="block text-sm font-normal text-gray-400">Owner Panel</span>
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('owner.dashboard') }}"
               class="block px-4 py-2 rounded-lg bg-indigo-600 text-white">
                Dashboard
            </a>

            <a href="#"
               class="block px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100">
                Empresas
            </a>

            <a href="#"
               class="block px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100">
                Facturación
            </a>

            <a href="#"
               class="block px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100">
                Estadísticas
            </a>
        </nav>

        <div class="p-4 border-t">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-4 py-2 rounded-lg text-red-600 hover:bg-red-50">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 p-8">
        @yield('content')
    </main>

</div>
</body>
</html>
