@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #0c0c0e;
        --border-color: rgba(255, 255, 255, 1);
        --accent-sky: #38bdf8;
        --stellar-blue: rgba(30, 58, 138, 0.7);
    }

    body { background-color: var(--oled-bg) !important; color: #fff; overflow-x: hidden; }

    /* CABECERA BALACEADA - 15% DE MARGEN */
    .header-hub {
        padding: 4rem 15% 2rem 15%; 
        display: flex;
        align-items: center;
        gap: 5rem;
    }

    .crm-container {
        display: flex;
        width: 100vw;
        padding: 0 15%;
        gap: 2.5rem;
        height: 70vh;
        overflow-x: auto;
    }

    .kanban-col {
        width: 320px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
    }

    .col-header {
        background: linear-gradient(90deg, var(--stellar-blue) 0%, transparent 100%);
        padding: 0.9rem 1.75rem;
        border-radius: 14px;
        border-left: 6px solid var(--accent-sky);
        margin-bottom: 3rem;
        height: 60px; /* SIMETRIA TOTAL HORIZONTAL */
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-title { font-size: 0.85rem; font-weight: 950; letter-spacing: 0.3em; text-transform: uppercase; }

    /* TARJETAS SIMÉTRICAS - LEY DE HIERRO 130PX */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color); 
        border-radius: 18px;
        padding: 1.2rem;
        margin-bottom: 20px; 
        height: 130px; 
        width: 100%;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.3s, border-color 0.3s;
    }

    .card-name { font-size: 0.95rem; font-weight: 950; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff; }
    .card-subtext { font-size: 0.7rem; color: #52525b; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .btn-card { 
        background: rgba(255,255,255,0.03); 
        border: 1px solid rgba(255,255,255,0.1); 
        color: var(--accent-sky); 
        padding: 8px 0; 
        border-radius: 12px; 
        font-size: 0.65rem; 
        font-weight: 950; 
        text-align: center; 
        text-decoration: none;
        text-transform: uppercase;
    }
    .btn-card:hover:not(.disabled) { background: var(--accent-sky); color: #000; }
    .btn-card.disabled { opacity: 0.1; }

    /* OVERLAY CUSTOM MASTER (EL LABEL) */
    #ia-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.9);
        z-index: 10000;
        display: none; 
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }

    .report-card {
        width: 550px;
        background: #09090b;
        border: 2px solid var(--accent-sky);
        border-radius: 32px;
        padding: 3rem;
        box-shadow: 0 0 150px rgba(56, 189, 248, 0.2);
        animation: springIn 0.4s cubic-bezier(0.19, 1.2, 0.22, 1);
    }

    @keyframes springIn {
        from { transform: scale(0.8) translateY(20px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
    }

    .active-spotlight { border-color: var(--accent-sky) !important; box-shadow: 0 0 60px rgba(56, 189, 248, 0.3) !important; }
</style>

<div class="header-hub">
    <h1 class="text-white text-3xl font-black uppercase tracking-[0.5em]">Command Hub</h1>
    <div class="h-px bg-zinc-800 flex-1 opacity-20"></div>
    <div class="flex items-center gap-6 text-sky-400 font-black text-xs uppercase tracking-[0.3em] animate-pulse">
        <i class="bi bi-robot fs-2"></i> AGENTE SOCIAL LIVE
    </div>
</div>

<div class="crm-container custom-scrollbar" id="crmContainer">
    
    <!-- FASE 01 -->
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
                    <button type="button" class="btn-card" onclick="openReport('{{ $pro->id }}', '{{ $pro->name }}', '{{ $pro->lead_source ?? 'META ADS' }}')">IA DATA</button>
                    <button type="button" class="btn-card disabled">MAIL</button>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $prospectos->links() }}</div>
    </div>

    <!-- FASE 02 -->
    <div class="kanban-col">
        <div class="col-header" style="border-left-color: var(--accent-amber)">
            <span class="header-title text-amber-500">Fase 02 | Validar</span>
        </div>
        <div id="col-pendiente_pago" class="kanban-list" data-status="pendiente_pago">
            @foreach($pendientes as $pen)
            <div class="kanban-card" data-id="{{ $pen->id }}" id="card-{{ $pen->id }}">
                <div>
                    <div class="card-name">{{ $pen->name }}</div>
                    <div class="card-subtext text-amber-500/30">PAGO EN CURSO</div>
                </div>
                <div class="btn-group-card">
                    @if($pen->payment_voucher)
                        <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-card" style="color:var(--accent-amber)">VOUCHER</a>
                    @else
                        <span class="btn-card disabled">SIN DOC</span>
                    @endif
                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="m-0 p-0 d-grid">
                        @csrf
                        <button type="submit" class="btn-card" style="color:var(--accent-amber);border-color:rgba(245,158,11,0.2);">ACTIVAR</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- FASE 03 -->
    <div class="kanban-col">
        <div class="col-header" style="border-left-color: var(--accent-emerald)">
            <span class="header-title text-emerald-500">Fase 03 | Activos</span>
        </div>
        <div id="col-activo" class="kanban-list" data-status="activo">
            @foreach($activos as $act)
            <div class="kanban-card" data-id="{{ $act->id }}" id="card-{{ $act->id }}">
                <div>
                    <div class="card-name text-zinc-300">{{ $act->name }}</div>
                    <div class="card-subtext truncate">{{ $act->empresa?->nombre_comercial ?? 'SaaS Activo OK' }}</div>
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

{{-- MODAL CUSTOM OLED INDEPENDIENTE --}}
<div id="ia-overlay">
    <div class="report-card">
        <div class="flex justify-between items-center mb-10">
            <h4 class="text-sky-400 font-black uppercase tracking-[0.2em] m-0"><i class="bi bi-robot me-2"></i> Reporte Agente IA</h4>
            <button onclick="closeReport()" class="bg-transparent border-0 text-white/20 hover:text-white transition-colors">
                <i class="bi bi-x-lg fs-4"></i>
            </button>
        </div>
        
        <div class="text-zinc-300 font-monospace" style="font-size: 0.85rem;">
            <div class="mb-8 border-b border-white/5 pb-6">>>> ANALIZANDO: <span id="report-name" class="text-white text-bold"></span></div>
            
            <div class="bg-black/30 p-8 rounded-3xl border border-white/5 shadow-inner mb-10">
                <div class="mb-4"><span class="text-zinc-600">CANAL:</span> <span id="report-source" class="text-emerald-400"></span></div>
                <div class="text-zinc-400 leading-relaxed">
                    "El Agente Social Live ha detectado una alta probabilidad de conversión. El perfil busca una solución robusta pero amigable para la gestión de su negocio multi-sede."
                </div>
            </div>

            <button onclick="closeReport()" class="btn-card w-full py-4 bg-sky-500 text-black border-0 fw-black text-[0.8rem] shadow-xl shadow-sky-500/10">
                VOLVER A LA CONSOLA
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    let currentActiveId = null;

    function openReport(id, name, source) {
        currentActiveId = id;
        document.getElementById('card-'+id).classList.add('active-spotlight');
        document.getElementById('report-name').innerText = name;
        document.getElementById('report-source').innerText = source;
        document.getElementById('ia-overlay').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeReport() {
        document.getElementById('ia-overlay').style.display = 'none';
        if(currentActiveId) {
            document.getElementById('card-'+currentActiveId).classList.remove('active-spotlight');
        }
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['col-prospecto', 'col-pendiente_pago', 'col-activo'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                new Sortable(el, {
                    group: 'kanban',
                    handle: '.kanban-card',
                    animation: 250,
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
