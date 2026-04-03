@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #0c0c0e;
        --border-color: rgba(255, 255, 255, 1);
        --accent-indigo: #6366f1;
        --accent-amber: #f59e0b;
        --accent-emerald: #10b981;
        --accent-sky: #38bdf8;
        --stellar-blue: rgba(30, 58, 138, 0.7);
    }

    body { background-color: var(--oled-bg) !important; color: #fff; overflow: hidden; }

    /* CABECERA CON ESPACIO Y PROPORCIÓN REFORZADA */
    .header-hub {
        padding: 4rem 10% 2rem 10%; /* MUCHO MAS AIRE */
        display: flex;
        align-items: center;
        gap: 4rem;
    }

    .crm-container {
        display: flex;
        width: 100vw;
        padding: 0 10%;
        gap: 2rem;
        height: 70vh;
        overflow-x: auto;
    }

    .kanban-col {
        width: 310px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
    }

    .col-header {
        background: linear-gradient(90deg, var(--stellar-blue) 0%, transparent 100%);
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        border-left: 5px solid var(--accent-sky);
        margin-bottom: 2.5rem;
        height: 55px; /* ALINEACION HORIZONTAL GARANTIZADA */
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-title { font-size: 0.8rem; font-weight: 900; letter-spacing: 0.25em; text-transform: uppercase; }

    /* TARJETAS SIMÉTRICAS 130PX */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color); 
        border-radius: 16px;
        padding: 1rem;
        margin-bottom: 20px; 
        height: 130px; 
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s;
    }

    .card-name { font-size: 0.9rem; font-weight: 950; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .card-subtext { font-size: 0.65rem; color: #71717a; font-weight: 700; text-transform: uppercase; }

    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
    .btn-card { 
        background: rgba(255,255,255,0.03); 
        border: 1px solid rgba(255,255,255,0.1); 
        color: var(--accent-sky); 
        padding: 7px 0; 
        border-radius: 10px; 
        font-size: 0.6rem; 
        font-weight: 900; 
        text-align: center; 
        text-decoration: none;
    }
    .btn-card:hover:not(.disabled) { background: var(--accent-sky); color: #000; }
    .btn-card.disabled { opacity: 0.1; }

    /* CUSTOM OLED MODAL (EL LABEL) */
    #ia-overlay {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.85);
        z-index: 9999;
        display: none; /* OCULTO POR DEFECTO */
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .custom-ai-card {
        width: 500px;
        background: #0c0c0e;
        border: 2px solid var(--accent-sky);
        border-radius: 28px;
        padding: 2.5rem;
        box-shadow: 0 0 120px rgba(56, 189, 248, 0.25);
        position: relative;
    }

    .active-spotlight {
        border-color: var(--accent-sky) !important;
        box-shadow: 0 0 50px rgba(56, 189, 248, 0.4) !important;
    }
</style>

<div class="header-hub">
    <h1 class="text-white text-3xl font-black uppercase tracking-[0.6em]">Command Hub</h1>
    <div class="h-px bg-zinc-800 flex-1 opacity-20"></div>
    <div class="flex items-center gap-6 text-sky-400 font-black text-[0.9rem] uppercase tracking-widest animate-pulse">
        <i class="bi bi-robot fs-2"></i> AGENTE SOCIAL LIVE
    </div>
</div>

<div class="crm-container custom-scrollbar" id="crmContainer">
    <!-- COLUMNA 1 -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title">Fase 01 | Leads</span>
            <span class="text-white/40 text-xs font-black">{{ $prospectos->total() }}</span>
        </div>
        <div id="col-prospecto" class="kanban-list" data-status="prospecto">
            @foreach($prospectos as $pro)
            <div class="kanban-card" data-id="{{ $pro->id }}" id="card-{{ $pro->id }}">
                <div>
                    <div class="card-name">{{ $pro->name }}</div>
                    <div class="card-subtext">{{ $pro->lead_source ?? 'Landing Directo' }}</div>
                </div>
                <div class="btn-group-card">
                    <button type="button" class="btn-card" onclick="openCustomIA('{{ $pro->id }}', '{{ $pro->name }}', '{{ $pro->lead_source ?? 'INSTAGRAM' }}')">IA DATA</button>
                    <button type="button" class="btn-card disabled">MAIL</button>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $prospectos->links() }}</div>
    </div>

    <!-- COLUMNA 2 -->
    <div class="kanban-col">
        <div class="col-header" style="border-left-color: var(--accent-amber)">
            <span class="header-title text-amber-500">Fase 02 | Validar</span>
        </div>
        <div id="col-pendiente_pago" class="kanban-list" data-status="pendiente_pago">
            @foreach($pendientes as $pen)
            <div class="kanban-card" data-id="{{ $pen->id }}" id="card-{{ $pen->id }}">
                <div>
                    <div class="card-name">{{ $pen->name }}</div>
                    <div class="card-subtext text-amber-500/40">Validar Pago</div>
                </div>
                <div class="btn-group-card">
                    @if($pen->payment_voucher)
                        <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-card text-amber-500">DOC</a>
                    @else
                        <span class="btn-card disabled">SIN DOC</span>
                    @endif
                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="m-0 p-0 d-grid">
                        @csrf
                        <button type="submit" class="btn-card" style="color:var(--accent-amber);border-color:rgba(245,158,11,0.2);">ACT</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- COLUMNA 3 -->
    <div class="kanban-col">
        <div class="col-header" style="border-left-color: var(--accent-emerald)">
            <span class="header-title text-emerald-500">Fase 03 | Activos</span>
        </div>
        <div id="col-activo" class="kanban-list" data-status="activo">
            @foreach($activos as $act)
            <div class="kanban-card" data-id="{{ $act->id }}" id="card-{{ $act->id }}">
                <div>
                    <div class="card-name text-zinc-300">{{ $act->name }}</div>
                    <div class="card-subtext truncate">{{ $act->empresa?->nombre_comercial ?? 'Setup OK' }}</div>
                </div>
                <div class="btn-group-card">
                    <button class="btn-card" style="color:var(--accent-emerald);border-color:rgba(16,185,129,0.2);">PANEL</button>
                    <button class="btn-card disabled">STATS</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- MODAL CUSTOM OLED --}}
<div id="ia-overlay" onclick="closeCustomIA()">
    <div class="custom-ai-card" onclick="event.stopPropagation()">
        <h4 class="text-sky-400 font-black uppercase tracking-widest mb-6"> <i class="bi bi-robot me-2"></i> Reporte IA</h4>
        <div class="text-zinc-300 font-monospace" style="font-size: 0.8rem;">
            <div class="mb-6 border-b border-white/5 pb-4">>>> ANALIZANDO: <span id="ia-name" class="text-white"></span></div>
            <div class="bg-black/40 p-6 rounded-2xl border border-white/5 shadow-inner mb-8">
                <div class="mb-4"><span class="text-zinc-600">CANAL:</span> <span id="ia-source" class="text-emerald-400"></span></div>
                <div class="text-zinc-400">"El Agente Social Live detectó una búsqueda de solución POS rápida para entorno móvil."</div>
            </div>
            <button onclick="closeCustomIA()" class="btn-card w-full py-4 bg-sky-500 text-black border-0 fw-black text-[0.8rem]">
                VOLVER A LA CONSOLA
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    let activeCard = null;

    function openCustomIA(id, name, source) {
        activeCard = id;
        document.getElementById('card-'+id).classList.add('active-spotlight');
        document.getElementById('ia-name').innerText = name;
        document.getElementById('ia-source').innerText = source;
        document.getElementById('ia-overlay').style.display = 'flex';
    }

    function closeCustomIA() {
        document.getElementById('ia-overlay').style.display = 'none';
        if(activeCard) document.getElementById('card-'+activeCard).classList.remove('active-spotlight');
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['col-prospecto', 'col-pendiente_pago', 'col-activo'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                new Sortable(el, {
                    group: 'kanban',
                    animation: 200,
                    onEnd: function(evt) {
                        fetch("{{ route('owner.crm.move') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ user_id: evt.item.getAttribute('data-id'), status: evt.to.getAttribute('data-status') })
                        });
                    }
                });
            }
        });
    });
</script>
@endsection
