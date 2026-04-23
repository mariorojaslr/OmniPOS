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

        /* AYUDA OWNER */
        #help-trigger {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #d4af37, #f43f5e);
            color: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            cursor: pointer;
            z-index: 9999;
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.4);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
        }

        #help-trigger:hover {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 15px 35px rgba(212, 175, 55, 0.6);
        }

        .offcanvas-help {
            position: fixed;
            top: 20px;
            right: -450px;
            bottom: 20px;
            width: 400px;
            background: rgba(10,12,14,0.98);
            backdrop-filter: blur(20px);
            z-index: 10000;
            border-radius: 25px 0 0 25px;
            box-shadow: -20px 0 50px rgba(0,0,0,0.3);
            transition: right 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
        }

        .offcanvas-help.show { right: 0; }

        .help-header {
            background: #d4af37;
            padding: 15px 20px;
            border-radius: 20px 20px 0 0;
            color: white;
            cursor: move;
        }

        .help-body {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
        }
        
        .manual-body { line-height: 1.6; color: #d1d5db; }
        .manual-body h1, .manual-body h2, .manual-body h3 { color: #d4af37; margin-top: 1rem; }
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

    <!-- PANEL INTEGRADO DE AYUDA (INTELIGENTE) -->
    <div class="offcanvas-help" id="helpPanel" style="display:none; position:fixed; right:30px; top:100px; width:450px; height:600px; background:rgba(10,12,14,0.9); backdrop-filter:blur(20px); border-radius:20px; border:1px solid rgba(255,255,255,0.1); flex-direction:column; z-index:11000; resize:both; overflow:hidden;">
        <div class="help-header d-flex align-items-center justify-content-between" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background:#d4af37; cursor:move;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-robot me-1"></i>
                <h6 class="mb-0 fw-bold" style="margin:0;">Manual Contextual</h6>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-dark border-0" id="btnEditHelp" title="Editar Manual"><i class="bi bi-pencil-square"></i></button>
                <button type="button" onclick="openHelp()" style="background: transparent; border: none; color: black; cursor: pointer; font-size: 1.5rem;">&times;</button>
            </div>
        </div>
        <div class="help-body" id="helpContentArea" style="flex-grow:1; overflow-y:auto; padding:25px;">
            {{-- Cargando... --}}
        </div>
        
        <!-- EDITOR -->
        <div id="helpEditorArea" style="display:none;" class="p-3">
            <input type="text" id="editHelpTitle" class="form-control mb-2 bg-transparent text-white" placeholder="Título...">
            <div id="summernoteHelp"></div>
            <div class="d-flex justify-content-end gap-2 mt-2">
                <button class="btn btn-sm btn-secondary" onclick="cancelEditHelp()">Cancelar</button>
                <button class="btn btn-sm btn-warning" onclick="saveHelpContent()">Guardar</button>
            </div>
        </div>

        <div style="padding: 10px 20px; text-align: center; border-top: 1px solid rgba(255,255,255,0.05); font-size: 10px; color: grey;">
             Ruta Master: {{ Route::currentRouteName() }}
        </div>
    </div>

    {{-- BOTÓN MÁGICO DE AYUDA --}}
    <div id="help-trigger" onclick="openHelp()"><i class="bi bi-magic"></i></div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('scripts')

    <script>
        const currentRoute = '{{ Route::currentRouteName() }}';

        $(function() {
            $("#helpPanel").draggable({ handle: ".help-header", containment: "window" })
                          .resizable({ minWidth: 300, minHeight: 100, handles: "all" });
        });

        function openHelp() {
            const panel = document.getElementById('helpPanel');
            if (panel.style.display === 'flex') {
                panel.style.display = 'none';
            } else {
                panel.style.display = 'flex';
                fetchHelpContent();
            }
        }

        function fetchHelpContent() {
            const area = document.getElementById('helpContentArea');
            area.innerHTML = '<div style="text-align:center; padding:50px;">Cargando manual...</div>';

            fetch(`/help/fetch?route=${currentRoute}`)
                .then(res => res.json())
                .then(res => {
                    if(res.success && res.data) {
                        area.innerHTML = `<h4 style="color:#d4af37;">${res.data.title}</h4><div class="manual-body">${res.data.content}</div>`;
                    } else {
                        area.innerHTML = `<div style="text-align:center; padding:50px; opacity:0.5;">Sin manual. <button class="btn btn-sm btn-warning" onclick="activateEditor()">Crear</button></div>`;
                    }
                });
        }

        function activateEditor() {
            document.getElementById('helpContentArea').style.display = 'none';
            document.getElementById('helpEditorArea').style.display = 'block';
            const area = document.getElementById('helpContentArea');
            const currentTitle = area.querySelector('h4')?.innerText || '';
            const currentContent = area.querySelector('.manual-body')?.innerHTML || '';
            document.getElementById('editHelpTitle').value = currentTitle;
            $('#summernoteHelp').summernote({ height: 300 });
            $('#summernoteHelp').summernote('code', currentContent);
        }

        function cancelEditHelp() {
            document.getElementById('helpContentArea').style.display = 'block';
            document.getElementById('helpEditorArea').style.display = 'none';
            $('#summernoteHelp').summernote('destroy');
        }

        function saveHelpContent() {
            const title = document.getElementById('editHelpTitle').value;
            const content = $('#summernoteHelp').summernote('code');
            
            if(!title || !content) {
                Swal.fire({ icon: 'error', title: 'Incompleto', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                return;
            }

            fetch('/help/save', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ route_name: currentRoute, title: title, content: content })
            })
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Guardado con éxito!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    });
                    cancelEditHelp();
                    openHelp();
                    fetchHelpContent();
                }
            });
        }

        document.getElementById('btnEditHelp')?.addEventListener('click', activateEditor);
    </script>
</body>
</html>
