<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MultiPOS | Owner</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-800">
<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg hidden md:flex flex-col">
        <div class="p-6 border-b text-center">
            <img src="{{ asset('images/logo_premium.png') }}" alt="MultiPOS Logo" class="mx-auto mb-2" style="max-height: 40px;">
            <span class="block text-xs font-normal text-gray-400 uppercase tracking-wider">Owner Control Center</span>
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('owner.dashboard') }}"
               class="block px-4 py-2 rounded-lg {{ request()->routeIs('owner.dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Dashboard Principal
            </a>

            <a href="{{ route('owner.crm.index') }}"
               class="block px-4 py-2 rounded-lg {{ request()->routeIs('owner.crm.*') ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                CRM de Ventas
            </a>

            <a href="{{ route('owner.empresas.index') }}"
               class="block px-4 py-2 rounded-lg {{ request()->routeIs('owner.empresas.*') ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Empresas
            </a>

            <a href="{{ route('owner.planes.index') }}"
               class="block px-4 py-2 rounded-lg {{ request()->routeIs('owner.planes.*') ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Planes y Facturación
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
@yield('scripts')
</body>
</html>
