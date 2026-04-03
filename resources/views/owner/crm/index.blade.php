@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #0c0c0e;
        --border-color: rgba(255, 255, 255, 0.1);
        --accent-sky: #38bdf8;
        --accent-amber: #f59e0b;
        --accent-emerald: #10b981;
        --stellar-blue: rgba(30, 58, 138, 0.7);
    }

    body { background-color: var(--oled-bg) !important; color: #fff; overflow-x: hidden; }

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
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-title { font-size: 0.85rem; font-weight: 950; letter-spacing: 0.3em; text-transform: uppercase; }

    /* TARJETAS SIMÉTRICAS ELITE 130PX */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid rgba(255,255,255,0.4); 
        border-radius: 18px;
        padding: 1.2rem;
        margin-bottom: 20px; 
        height: 130px; 
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        cursor: grab;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .kanban-card:hover {
        border-color: var(--accent-sky);
        box-shadow: 0 15px 40px -10px rgba(56, 189, 248, 0.4);
        transform: translateY(-5px);
    }

    /* CONTROLES SUPERIORES (GRIP + ARCHIVE + TRASH) */
    .card-controls {
        position: absolute;
        right: 12px;
        top: 8px;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-control {
        background: transparent;
        border: 0;
        padding: 0;
        font-size: 0.9rem;
        opacity: 0.15;
        transition: all 0.2s;
        color: #fff;
    }
    .kanban-card:hover .btn-control { opacity: 0.5; }
    .btn-control:hover { opacity: 1 !important; transform: scale(1.2); }
    
    .btn-archive:hover { color: var(--accent-amber); }
    .btn-trash:hover { color: #ef4444; }

    .card-handle { color: rgba(255,255,255,0.15); font-size: 1.1rem; }
    .kanban-card:hover .card-handle { color: var(--accent-sky); opacity: 1; }

    .card-name { font-size: 0.9rem; font-weight: 950; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff; padding-right: 55px; }
    .card-subtext { font-size: 0.7rem; color: #52525b; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .btn-sci-fi { 
        background: rgba(255,255,255,0.03); 
        border: 1px solid rgba(255,255,255,0.1); 
        color: var(--accent-sky); 
        padding: 7px 0; 
        border-radius: 12px; 
        font-size: 0.55rem; 
        font-weight: 950; 
        text-align: center; 
        text-decoration: none;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: 0.2s;
    }
    .btn-sci-fi:hover:not(.disabled) { background: var(--accent-sky); color: #000; border-color: var(--accent-sky); }
    .btn-sci-fi.disabled { opacity: 0.1; cursor: not-allowed; }

    .status-label {
        font-size: 0.5rem;
        font-weight: 900;
        color: var(--accent-sky);
        letter-spacing: 1.5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(255,255,255,0.05);
        padding-top: 6px;
    }

    /* OVERLAY IA MASTER */
    #ia-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.92);
        z-index: 10000;
        display: none; 
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(8px);
    }

    .report-card {
        width: 550px;
        background: #09090b;
        border: 2px solid var(--accent-sky);
        border-radius: 32px;
        padding: 3rem;
        box-shadow: 0 0 150px rgba(56, 189, 248, 0.25);
        animation: springIn 0.4s cubic-bezier(0.19, 1.2, 0.22, 1);
    }

    @keyframes springIn {
        from { transform: scale(0.9) translateY(30px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
    }

    .active-spotlight { border-color: var(--accent-sky) !important; box-shadow: 0 0 80px rgba(56, 189, 248, 0.5) !important; z-index: 100 !important; }
</style>

<div class="header-hub">
    <h1 class="text-white text-3xl font-black uppercase tracking-[0.5em]">Command Hub</h1>
    <div class="h-px bg-zinc-800 flex-1 opacity-20"></div>
    <div class="flex items-center gap-6 text-sky-400 font-black text-xs uppercase tracking-[0.3em] animate-pulse">
        <i class="bi bi-robot fs-2"></i> AGENTE SOCIAL LIVE
    </div>
</div>

<div class="crm-container custom-scrollbar px-12" id="crmContainer">
    
    <!-- PHASE 01 -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title">Fase 01 | Leads</span>
            <span class="text-white/40 text-xs font-black">{{ $prospectos->total() }}</span>
        </div>
        <div id="col-prospecto" class="kanban-list" data-status="prospecto">
            @foreach($prospectos as $pro)
            <div class="kanban-card" data-id="{{ $pro->id }}" id="card-{{ $pro->id }}">
                <div class="card-controls">
                    <button type="button" class="btn-control btn-archive" title="Olvidar por ahora" onclick="archiveLead('{{ $pro->id }}')"><i class="bi bi-archive-fill"></i></button>
                    <button type="button" class="btn-control btn-trash" title="Borrar para siempre" onclick="deleteLead('{{ $pro->id }}')"><i class="bi bi-trash3-fill"></i></button>
                    <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                </div>
                <div>
                    <div class="card-name">{{ $pro->name }}</div>
                    <div class="card-subtext">{{ $pro->lead_source ?? 'Landing Directo' }}</div>
                </div>
                <div class="btn-group-card">
                    <button type="button" class="btn-sci-fi" onclick="openReport('{{ $pro->id }}', '{{ $pro->name }}', '{{ $pro->lead_source ?? 'META ADS' }}')">
                        <i class="bi bi-robot"></i> IA DATA
                    </button>
                    <button type="button" class="btn-sci-fi disabled">
                        <i class="bi bi-envelope"></i> MAIL
                    </button>
                </div>
                <div class="status-label">
                    <span>PROSPECTO OK</span>
                    <i class="bi bi-person-plus-fill"></i>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $prospectos->links() }}</div>
    </div>

    <!-- PHASE 02 -->
    <div class="kanban-col">
        <div class="col-header" style="border-left-color: var(--accent-amber)">
            <span class="header-title text-amber-500">Fase 02 | Validar</span>
        </div>
        <div id="col-pendiente_pago" class="kanban-list" data-status="pendiente_pago">
            @foreach($pendientes as $pen)
            <div class="kanban-card" data-id="{{ $pen->id }}" id="card-{{ $pen->id }}">
                <div class="card-controls">
                    <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                </div>
                <div>
                    <div class="card-name">{{ $pen->name }}</div>
                    <div class="card-subtext text-amber-500/30">Validación Pago</div>
                </div>
                <div class="btn-group-card">
                    @if($pen->payment_voucher)
                        <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-sci-fi" style="color:var(--accent-amber)">
                           <i class="bi bi-file-earmark-pdf"></i> DOC
                        </a>
                    @else
                        <span class="btn-sci-fi disabled"><i class="bi bi-x-circle"></i> NO DOC</span>
                    @endif
                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="m-0 p-0 d-grid">
                        @csrf
                        <button type="submit" class="btn-sci-fi" style="color:var(--accent-amber);border-color:rgba(245,158,11,0.2);">
                            <i class="bi bi-lightning-charge-fill"></i> ACT
                        </button>
                    </form>
                </div>
                <div class="status-label" style="color: var(--accent-amber)">
                    <span>VALIDAR PAGO</span>
                    <i class="bi bi-lightning-fill"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- PHASE 03 -->
    <div class="kanban-col">
        <div class="col-header" style="border-left-color: var(--accent-emerald)">
            <span class="header-title text-emerald-500">Fase 03 | Activos</span>
        </div>
        <div id="col-activo" class="kanban-list" data-status="activo">
            @foreach($activos as $act)
            <div class="kanban-card" data-id="{{ $act->id }}" id="card-{{ $act->id }}">
                <div class="card-controls">
                    <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                </div>
                <div>
                    <div class="card-name text-zinc-300">{{ $act->name }}</div>
                    <div class="card-subtext truncate">{{ $act->empresa?->nombre_comercial ?? 'Setup OK' }}</div>
                </div>
                <div class="btn-group-card">
                    <button class="btn-sci-fi" style="color:var(--accent-emerald);border-color:rgba(16,185,129,0.2);">
                        <i class="bi bi-gear-fill"></i> PANEL
                    </button>
                    <button class="btn-sci-fi disabled" style="color:var(--accent-emerald);border-color:rgba(16,185,129,0.2);">
                        <i class="bi bi-bar-chart-fill"></i> STATS
                    </button>
                </div>
                <div class="status-label" style="color: var(--accent-emerald)">
                    <span>SAAS ACTIVO</span>
                    <i class="bi bi-shield-check-fill"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- OVERLAY IA MASTER --}}
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
                <div class="text-zinc-400 leading-relaxed text-[0.75rem]">
                    "El Agente Social Live ha detectado intención de compra inmediata basada en comportamiento social registrado."
                </div>
            </div>

            <button onclick="closeReport()" class="btn-sci-fi w-full py-4 bg-sky-500 text-black border-0 fw-black text-[0.8rem] shadow-xl shadow-sky-500/10">
                VOLVER A LA CONSOLA
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    let currentId = null;

    function openReport(id, name, source) {
        currentId = id;
        const card = document.getElementById('card-'+id);
        if(card) card.classList.add('active-spotlight');
        document.getElementById('report-name').innerText = name;
        document.getElementById('report-source').innerText = source;
        document.getElementById('ia-overlay').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeReport() {
        document.getElementById('ia-overlay').style.display = 'none';
        if(currentId) {
            const card = document.getElementById('card-'+currentId);
            if(card) card.classList.remove('active-spotlight');
        }
        document.body.style.overflow = 'auto';
    }

    function archiveLead(id) {
        if(confirm('¿Olvidar este lead? (Se guardará pero no ocupará espacio aquí)')) {
            const card = document.getElementById('card-'+id);
            card.style.opacity = '0.3';
            card.style.transform = 'scale(0.9)';
            
            fetch("{{ route('owner.crm.archive') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ user_id: id })
            }).then(() => card.remove());
        }
    }

    function deleteLead(id) {
        if(confirm('¿Borrar definitivamente? Se perderá todo el historial.')) {
            const card = document.getElementById('card-'+id);
            card.style.opacity = '0.3';
            card.style.transform = 'translateX(100px)';
            
            fetch("{{ route('owner.crm.delete') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ user_id: id })
            }).then(() => card.remove());
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['col-prospecto', 'col-pendiente_pago', 'col-activo'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                new Sortable(el, {
                    group: 'kanban',
                    handle: '.card-handle',
                    animation: 250,
                    onEnd: function(evt) {
                        const uid = evt.item.getAttribute('data-id');
                        const status = evt.to.getAttribute('data-status');
                        fetch("{{ route('owner.crm.move') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ user_id: uid, status: status })
                        });
                    }
                });
            }
        });
    });
</script>
@endsection
