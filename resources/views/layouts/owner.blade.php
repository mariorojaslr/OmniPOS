<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MultiPOS | Suite Maestra</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #000; color: #fff; }
        .nav-link-active { 
            background: rgba(255, 255, 255, 0.05); 
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff !important;
        }
    </style>
</head>

<body class="bg-black text-slate-300">

    {{-- NAV SUPERIOR MASTER --}}
    <nav class="sticky top-0 z-[100] bg-black/80 backdrop-blur-xl border-b border-white/5 px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-10">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo_premium.png') }}" alt="MultiPOS" style="max-height: 28px;">
                <span class="text-[10px] font-black text-zinc-500 uppercase tracking-[0.3em] border-l border-white/10 ps-3">Suite Maestra</span>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('owner.dashboard') }}" 
                   class="px-5 py-2 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all {{ request()->routeIs('owner.dashboard') ? 'nav-link-active' : 'text-zinc-500 hover:text-white' }}">
                    Panel de Control
                </a>
                <a href="{{ route('owner.crm.index') }}" 
                   class="px-5 py-2 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all {{ request()->routeIs('owner.crm.*') ? 'nav-link-active' : 'text-zinc-500 hover:text-white' }}">
                    CRM de Ventas
                </a>
                <a href="{{ route('owner.empresas.index') }}" 
                   class="px-5 py-2 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all {{ request()->routeIs('owner.empresas.*') ? 'nav-link-active' : 'text-zinc-500 hover:text-white' }}">
                    Empresas
                </a>
                <a href="{{ route('owner.planes.index') }}" 
                   class="px-5 py-2 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all {{ request()->routeIs('owner.planes.*') ? 'nav-link-active' : 'text-zinc-500 hover:text-white' }}">
                    Planes
                </a>
                <a href="{{ route('owner.soporte.index') }}" 
                   class="px-5 py-2 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all {{ request()->routeIs('owner.soporte.*') ? 'nav-link-active' : 'text-zinc-500 hover:text-white' }}">
                    Soporte
                </a>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="bg-zinc-900 border border-white/5 text-[10px] uppercase font-black text-zinc-400 px-4 py-2 rounded-lg hover:bg-red-600 hover:text-white transition-all">
                    Salir
                </button>
            </form>
        </div>
    </nav>

    {{-- MAIN CONTENT (FULL WIDTH) --}}
    <main class="w-full">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
