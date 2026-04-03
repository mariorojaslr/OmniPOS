@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #0c0c0e;
        --border-color: rgba(255, 255, 255, 0.4);
        --accent-sky: #38bdf8;
        --accent-amber: #f59e0b;
        --accent-emerald: #10b981;
        --stellar-blue: rgba(30, 58, 138, 0.7);
    }

    body { background-color: var(--oled-bg) !important; color: #fff; overflow-x: hidden; }

    .header-hub {
        padding: 4rem 5% 2rem 5%; 
        display: flex;
        align-items: center;
        gap: 5rem;
    }

    /* LAYOUT MAESTRO INTEGRADO */
    .master-layout {
        display: grid;
        grid-template-columns: 1fr 340px; 
        gap: 2rem;
        padding: 0 5% 4rem 5%;
        height: auto;
    }

    .crm-container {
        display: flex;
        gap: 2rem;
        overflow-x: auto;
        padding-bottom: 2rem;
    }

    .kanban-col { width: 310px; flex-shrink: 0; display: flex; flex-direction: column; }

    .col-header {
        background: linear-gradient(90deg, var(--stellar-blue) 0%, transparent 100%);
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border-left: 6px solid var(--accent-sky);
        margin-bottom: 2.5rem;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-title { font-size: 0.8rem; font-weight: 950; letter-spacing: 0.25em; text-transform: uppercase; }

    /* TARJETAS SIMÉTRICAS 130PX */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color); 
        border-radius: 18px;
        padding: 1.2rem;
        margin-bottom: 20px; 
        height: 130px; 
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        cursor: grab;
        transition: 0.3s;
    }
    .kanban-card:hover { border-color: var(--accent-sky); box-shadow: 0 15px 40px -10px rgba(56, 189, 248, 0.4); transform: translateY(-3px); }

    .card-controls { position: absolute; right: 12px; top: 10px; display: flex; gap: 10px; }
    .btn-control { background: transparent; border: 0; padding: 0; font-size: 0.95rem; opacity: 0.2; color: #fff; transition: 0.2s; }
    .kanban-card:hover .btn-control { opacity: 0.6; }
    .btn-control:hover { opacity: 1 !important; transform: scale(1.1); }
    .btn-archive:hover { color: var(--accent-amber); }
    .btn-trash:hover { color: #ef4444; }

    .card-name { font-size: 0.9rem; font-weight: 950; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff; padding-right: 55px; }
    .card-subtext { font-size: 0.65rem; color: #52525b; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
    .btn-sci-fi { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: var(--accent-sky); padding: 7px 0; border-radius: 10px; font-size: 0.55rem; font-weight: 950; display: flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; text-transform: uppercase; }
    .btn-sci-fi:hover:not(.disabled) { background: var(--accent-sky); color: #000; }
    .btn-sci-fi.disabled { opacity: 0.1; }

    /* MULTI-CHANNEL STATS HUB (4 BLOQUES) */
    .stats-hub {
        background: rgba(12, 12, 14, 0.85);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(56, 189, 248, 0.2);
        border-radius: 28px;
        padding: 2rem;
        height: fit-content;
        position: sticky;
        top: 2rem;
    }
    .stats-title { font-size: 0.65rem; font-weight: 950; color: var(--accent-sky); letter-spacing: 3px; text-transform: uppercase; margin-bottom: 2rem; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 0.75rem; }
    
    .channels-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .channel-block { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; padding: 1.25rem; display: flex; flex-direction: column; align-items: center; gap: 0.75rem; transition: 0.3s; }
    .channel-block:hover { background: rgba(255,255,255,0.04); border-color: var(--accent-sky); transform: translateY(-3px); }
    .channel-icon { font-size: 1.5rem; }
    .channel-val { font-size: 1.1rem; font-weight: 950; color: #fff; }
    .channel-label { font-size: 0.5rem; font-weight: 800; color: #52525b; text-transform: uppercase; }

    .stat-pulse { width: 10px; height: 10px; background: var(--accent-emerald); border-radius: 50%; box-shadow: 0 0 12px var(--accent-emerald); animation: pulse 1.5s infinite; }

    @keyframes pulse { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(1.3); } 100% { opacity: 1; transform: scale(1); } }

    /* OVERLAY IA MASTER */
    #ia-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.92); z-index: 10000; display: none; align-items: center; justify-content: center; backdrop-filter: blur(8px); }
    .report-card { width: 550px; background: #09090b; border: 2px solid var(--accent-sky); border-radius: 32px; padding: 3rem; box-shadow: 0 0 150px rgba(56, 189, 248, 0.25); animation: springIn 0.4s cubic-bezier(0.19, 1.2, 0.22, 1); }
</style>

<div class="header-hub">
    <h1 class="text-white text-3xl font-black uppercase tracking-[0.5em]">Command Hub</h1>
    <div class="h-px bg-zinc-800 flex-1 opacity-20"></div>
    <div class="flex items-center gap-6 text-sky-400 font-black text-xs uppercase tracking-[0.3em] animate-pulse">
        <i class="bi bi-robot fs-2 text-sky-400"></i> AGENTE SOCIAL LIVE
    </div>
</div>

<div class="master-layout">
    
    <div class="crm-container custom-scrollbar">
        <!-- FASE 01 -->
        <div class="kanban-col">
            <div class="col-header">
                <span class="header-title">Fase 01 | Leads</span>
                <span class="text-white/40 text-xs font-black">{{ $prospectos->total() }}</span>
            </div>
            <div id="col-prospecto" class="kanban-list" data-status="prospecto">
                @foreach($prospectos as $pro)
                <div class="kanban-card" data-id="{{ $pro->id }}" id="card-{{ $pro->id }}">
                    <div class="card-controls">
                        <button type="button" class="btn-control btn-archive" onclick="archiveLead('{{ $pro->id }}')"><i class="bi bi-archive-fill"></i></button>
                        <button type="button" class="btn-control btn-trash" onclick="deleteLead('{{ $pro->id }}')"><i class="bi bi-trash3-fill"></i></button>
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
                        <button type="button" class="btn-sci-fi disabled"><i class="bi bi-envelope"></i> MAIL</button>
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
                    <div class="card-controls">
                        <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                    </div>
                    <div>
                        <div class="card-name">{{ $pen->name }}</div>
                        <div class="card-subtext text-amber-500/30">Validar Pago</div>
                    </div>
                    <div class="btn-group-card">
                        @if($pen->payment_voucher)
                            <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-sci-fi" style="color:var(--accent-amber)"><i class="bi bi-file-earmark-pdf"></i> VOUCHER</a>
                        @else
                            <span class="btn-sci-fi disabled">SIN DOC</span>
                        @endif
                        <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="m-0 p-0 d-grid">
                            @csrf
                            <button type="submit" class="btn-sci-fi" style="color:var(--accent-amber);border-color:rgba(245,158,11,0.2);">ACT</button>
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
                    <div class="card-controls">
                        <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                    </div>
                    <div>
                        <div class="card-name text-zinc-300">{{ $act->name }}</div>
                        <div class="card-subtext truncate">{{ $act->empresa?->nombre_comercial ?? 'Setup OK' }}</div>
                    </div>
                    <div class="btn-group-card">
                        <button class="btn-sci-fi" style="color:var(--accent-emerald);border-color:rgba(16,185,129,0.2);">PANEL</button>
                        <button class="btn-sci-fi disabled">STATS</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- OMNI-CHANNEL STATS HUB -->
    <div class="stats-hub">
        <div class="flex justify-between items-center stats-title">
            <span>Social Live Scanner</span>
            <div class="stat-pulse"></div>
        </div>
        
        <div class="channels-grid">
            <div class="channel-block">
                <i class="bi bi-linkedin channel-icon text-sky-500"></i>
                <div class="channel-val">58</div>
                <div class="channel-label">LinkedIn</div>
            </div>
            <div class="channel-block">
                <i class="bi bi-instagram channel-icon text-pink-500"></i>
                <div class="channel-val">42</div>
                <div class="channel-label">Instagram</div>
            </div>
            <div class="channel-block">
                <i class="bi bi-facebook channel-icon text-blue-600"></i>
                <div class="channel-val">31</div>
                <div class="channel-label">Facebook</div>
            </div>
            <div class="channel-block">
                <i class="bi bi-chat-dots-fill channel-icon text-emerald-500"></i>
                <div class="channel-val">11</div>
                <div class="channel-label">WhatsApp/Tg</div>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-white/5">
            <div class="stat-row">
                <span class="channel-label">Efectividad Total</span>
                <span class="text-emerald-400 fw-black">12.4%</span>
            </div>
        </div>
    </div>

</div>

{{-- OVERLAY IA --}}
<div id="ia-overlay">
    <div class="report-card">
        <div class="flex justify-between items-center mb-10">
            <h4 class="text-sky-400 font-black uppercase tracking-[0.2em] m-0"><i class="bi bi-robot me-2"></i> Reporte Agente IA</h4>
            <button onclick="closeReport()" class="bg-transparent border-0 text-white/20 hover:text-white transition-colors">
                <i class="bi bi-x-lg fs-4"></i>
            </button>
        </div>
        <div class="text-zinc-300 font-monospace" style="font-size: 0.85rem;">
            <div class="mb-8 border-b border-white/5 pb-6">>>> ANALIZANDO: <span id="report-name" class="text-white"></span></div>
            <div class="bg-black/30 p-8 rounded-3xl border border-white/5 shadow-inner mb-10">
                <div class="mb-4"><span class="text-zinc-600">CANAL:</span> <span id="report-source" class="text-emerald-400"></span></div>
                <div class="text-zinc-400 leading-relaxed text-[0.75rem]">"Intención detectada. El Agente Social Live registró interés en soluciones POS móviles."</div>
            </div>
            <button onclick="closeReport()" class="btn-sci-fi w-full py-4 bg-sky-500 text-black border-0 fw-black text-[0.8rem]">VOLVER A LA CONSOLA</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    let curId = null;
    function openReport(id, name, source) {
        curId = id;
        document.getElementById('card-'+id).classList.add('active-spotlight');
        document.getElementById('report-name').innerText = name;
        document.getElementById('report-source').innerText = source;
        document.getElementById('ia-overlay').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeReport() {
        document.getElementById('ia-overlay').style.display = 'none';
        if(curId) document.getElementById('card-'+curId).classList.remove('active-spotlight');
        document.body.style.overflow = 'auto';
    }
    function archiveLead(id) {
        if(confirm('¿Olvidar lead?')) {
            fetch("{{ route('owner.crm.archive') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ user_id: id }) })
            .then(() => document.getElementById('card-'+id).remove());
        }
    }
    function deleteLead(id) {
        if(confirm('¿Borrar lead permanentemente?')) {
            fetch("{{ route('owner.crm.delete') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ user_id: id }) })
            .then(() => document.getElementById('card-'+id).remove());
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        ['col-prospecto', 'col-pendiente_pago', 'col-activo'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                new Sortable(el, { group: 'kanban', handle: '.card-handle', onEnd: function(evt) { fetch("{{ route('owner.crm.move') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ user_id: evt.item.getAttribute('data-id'), status: evt.to.getAttribute('data-status') }) }); } });
            }
        });
    });
</script>
@endsection
