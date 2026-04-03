@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #0c0c0e;
        --border-color: rgba(255, 255, 255, 0.18);
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
        height: 75vh;
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
        padding: 1rem 1.85rem;
        border-radius: 16px;
        border-left: 6px solid var(--accent-sky);
        margin-bottom: 3.5rem;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-title { font-size: 0.9rem; font-weight: 950; letter-spacing: 0.35em; text-transform: uppercase; }

    /* TARJETAS SIMÉTRICAS ELITE 130PX CON GLOW */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid rgba(255,255,255,0.7); 
        border-radius: 20px;
        padding: 1.3rem;
        margin-bottom: 22px; 
        height: 130px; 
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        cursor: grab;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .kanban-card:hover {
        border-color: var(--accent-sky);
        box-shadow: 0 0 50px -10px rgba(56, 189, 248, 0.6); /* GLOW AZUL MASTER */
        transform: translateY(-8px);
        background: rgba(12, 12, 14, 0.95);
    }

    /* CONTROLES DE TARJETA (Grip, Trash, Archive) */
    .card-controls {
        position: absolute;
        right: 15px;
        top: 12px;
        display: flex;
        gap: 15px;
        align-items: center;
        z-index: 10;
    }
    
    .btn-control { background: transparent; border: 0; padding: 0; font-size: 0.95rem; opacity: 0.15; color: #fff; transition: 0.2s; cursor: pointer; }
    .kanban-card:hover .btn-control { opacity: 0.6; }
    .btn-control:hover { opacity: 1 !important; transform: scale(1.2); }

    .btn-archive:hover { color: var(--accent-amber) !important; }
    .btn-trash:hover { color: #ef4444 !important; }

    .card-handle { color: rgba(255,255,255,0.1); font-size: 1.2rem; cursor: grab; }
    .kanban-card:hover .card-handle { color: var(--accent-sky); }

    .card-name { font-size: 0.95rem; font-weight: 950; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff; padding-right: 60px; }
    .card-subtext { font-size: 0.7rem; color: #52525b; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; }

    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 0.7rem; }
    .btn-sci-fi { 
        background: rgba(255,255,255,0.04); 
        border: 1px solid rgba(255,255,255,0.18); 
        color: var(--accent-sky); 
        padding: 8px 0; 
        border-radius: 12px; 
        font-size: 0.6rem; 
        font-weight: 950; 
        text-align: center; 
        text-transform: uppercase;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-sci-fi:hover:not(.disabled) { background: var(--accent-sky); color: #000; border-color: var(--accent-sky); box-shadow: 0 0 15px rgba(56, 189, 248, 0.3); }
    .btn-sci-fi.disabled { opacity: 0.1; }

    /* STATS HUB COLUMNA 04 (AL FINAL) */
    .stats-hub {
        width: 360px;
        flex-shrink: 0;
        background: rgba(12, 12, 14, 0.85);
        backdrop-filter: blur(25px);
        border: 1px solid rgba(56, 189, 248, 0.25);
        border-radius: 32px;
        padding: 2.5rem;
        height: fit-content;
        margin-right: 15%; /* CIERRE SIMÉTRICO 15% */
    }

    .simulation-badge { font-size: 0.55rem; font-weight: 950; background: rgba(245,158,11,0.15); color: var(--accent-amber); padding: 5px 12px; border-radius: 10px; margin-bottom: 2rem; border: 1px solid rgba(245,158,11,0.3); display: inline-block; letter-spacing: 1px; }

    /* MODALES UNIFICADOS */
    .master-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.96); z-index: 20000;
        display: none; align-items: center; justify-content: center; backdrop-filter: blur(20px);
    }
    .modal-sci-fi {
        width: 580px; background: #09090b; border: 2px solid var(--accent-sky); border-radius: 40px; padding: 4rem;
        box-shadow: 0 0 200px rgba(56, 189, 248, 0.35);
        animation: springIn 0.45s cubic-bezier(0.19, 1.2, 0.22, 1);
    }
</style>

<div class="header-hub">
    <h1 class="text-white text-3xl font-black uppercase tracking-[0.5em]">Command Hub</h1>
    <div class="h-px bg-zinc-800 flex-1 opacity-20"></div>
    <div class="flex items-center gap-6 text-sky-400 font-black text-xs uppercase tracking-[0.3em] animate-pulse">
        <i class="bi bi-robot fs-2"></i> AGENTE SOCIAL LIVE
    </div>
</div>

<div class="crm-container custom-scrollbar" id="crmContainer">
    
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
                    <button class="btn-control btn-archive" title="Olvidar Lead" onclick="archiveLead('{{ $pro->id }}')"><i class="bi bi-archive-fill"></i></button>
                    <button class="btn-control btn-trash" title="Borrar para siempre" onclick="deleteLead('{{ $pro->id }}')"><i class="bi bi-trash3-fill"></i></button>
                    <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                </div>
                <div>
                    <div class="card-name">{{ $pro->name }}</div>
                    <div class="card-subtext">{{ $pro->lead_source ?? 'Landing Directo' }}</div>
                </div>
                <div class="btn-group-card">
                    <button onclick="openIA('{{ $pro->name }}', '{{ $pro->lead_source ?? 'META ADS' }}')" class="btn-sci-fi"><i class="bi bi-robot"></i> IA DATA</button>
                    <button class="btn-sci-fi disabled"><i class="bi bi-envelope"></i> MAIL</button>
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
                <div class="card-controls"><div class="card-handle"><i class="bi bi-grip-vertical"></i></div></div>
                <div>
                    <div class="card-name">{{ $pen->name }}</div>
                    <div class="text-[0.7rem] text-amber-500/40 fw-black uppercase tracking-wider">Validación Pago</div>
                </div>
                <div class="btn-group-card">
                    @if($pen->payment_voucher)
                        <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-sci-fi" style="color:var(--accent-amber);"><i class="bi bi-file-earmark-pdf"></i> DOC</a>
                    @else
                        <span class="btn-sci-fi disabled">NO DOC</span>
                    @endif
                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="m-0 p-0 d-grid">
                        @csrf
                        <button type="submit" class="btn-sci-fi" style="color:var(--accent-amber);border-color:rgba(245,158,11,0.25);"><i class="bi bi-lightning-fill"></i> ACTIVAR</button>
                    </form>
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
                <div class="card-controls"><div class="card-handle"><i class="bi bi-grip-vertical"></i></div></div>
                <div>
                    <div class="card-name text-zinc-300">{{ $act->name }}</div>
                    <div class="text-[0.7rem] text-zinc-600 fw-black uppercase">{{ $act->empresa?->nombre_comercial ?? 'Setup OK' }}</div>
                </div>
                <div class="btn-group-card">
                    <button class="btn-sci-fi text-emerald-400" style="border-color:rgba(16,185,129,0.3);"><i class="bi bi-gear-fill"></i> PANEL</button>
                    <button class="btn-sci-fi disabled text-emerald-800"><i class="bi bi-bar-chart-fill"></i> STATS</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- FINAL STATS HUB (COL 04) -->
    <div class="stats-hub shadow-2xl">
        <div class="text-[0.7rem] font-black tracking-widest text-sky-400 uppercase mb-8 flex justify-between border-b border-white/5 pb-4">
            <span>Social Live Scanner</span>
            <div style="width:12px; height:12px;" class="bg-emerald-500 rounded-full animate-pulse shadow-[0_0_15px_#10b981]"></div>
        </div>
        
        <div class="simulation-badge"><i class="bi bi-eye-fill me-2"></i> MODO SIMULACIÓN ACTIVA</div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2.5rem;">
            @foreach(['LinkedIn' => ['58', 'sky-500', 'bi-linkedin'], 'Instagram' => ['42', 'pink-500', 'bi-instagram'], 'Facebook' => ['31', 'blue-600', 'bi-facebook'], 'Cloud' => ['11', 'emerald-500', 'bi-chat-dots-fill']] as $lb => $dt)
                <div class="channel-block" style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06); border-radius:20px; padding:1.4rem; display:flex; flex-direction:column; align-items:center; cursor:pointer;" onmouseover="this.style.borderColor='var(--accent-sky)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'" onclick="openChannelActivity('{{ $lb }}')">
                    <i class="bi {{ $dt[2] }} fs-3 text-{{ $dt[1] }} mb-2"></i>
                    <span class="text-white fw-black text-xl">{{ $dt[0] }}</span>
                    <span class="text-[0.5rem] text-zinc-500 fw-black uppercase">{{ $lb }}</span>
                </div>
            @endforeach
        </div>

        <button onclick="openProtocol()" class="w-full bg-zinc-900 border border-sky-500/20 text-sky-400 text-[0.65rem] font-black uppercase py-4 rounded-2xl hover:bg-sky-500 hover:text-black transition-all">
            <i class="bi bi-shield-lock-fill me-2"></i> Protocolo Técnico
        </button>
    </div>

</div>

{{-- MODAL IA VENTANITA --}}
<div id="ia-modal" class="master-overlay">
    <div class="modal-sci-fi">
        <div class="flex justify-between items-center mb-10">
            <h4 class="text-sky-400 font-black uppercase tracking-widest m-0"><i class="bi bi-robot me-3"></i> Reporte Agente IA</h4>
            <button onclick="closeModals()" class="bg-transparent border-0 text-zinc-600 hover:text-white transition-colors"><i class="bi bi-x-lg fs-3"></i></button>
        </div>
        <div class="text-zinc-300 font-mono text-[0.85rem] bg-black/40 p-10 rounded-[35px] border border-white/5 shadow-inner mb-10">
            <div class="mb-4 text-white">>>> ANALIZANDO: <span id="ia-target" class="text-sky-400"></span></div>
            <div class="mb-2 text-zinc-600">CANAL: <span id="ia-channel" class="text-emerald-400"></span></div>
            <hr class="border-white/5 my-8">
            <div class="leading-relaxed">"Intención detectada. El Agente sugirió migración inmediata. Lead calificado para Fase 02."</div>
        </div>
        <button onclick="closeModals()" class="btn-sci-fi w-full py-5 bg-sky-500 text-black border-0 fw-black text-[0.9rem]">CERRAR VENTANA</button>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    function openIA(name, channel) { 
        document.getElementById('ia-target').innerText = name; 
        document.getElementById('ia-channel').innerText = channel; 
        document.getElementById('ia-modal').style.display = 'flex'; 
        document.body.style.overflow = 'hidden'; 
    }
    function closeModals() { 
        document.querySelectorAll('.master-overlay').forEach(m => m.style.display = 'none'); 
        document.body.style.overflow = 'auto'; 
    }
    function archiveLead(id) { if(confirm('¿Olvidar lead?')) { fetch("{{ route('owner.crm.archive') }}", { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{csrf_token()}}'}, body:JSON.stringify({user_id:id}) }).then(()=>document.getElementById('card-'+id).remove()); } }
    function deleteLead(id) { if(confirm('¿Borrar definitivamente?')) { fetch("{{ route('owner.crm.delete') }}", { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{csrf_token()}}'}, body:JSON.stringify({user_id:id}) }).then(() => document.getElementById('card-'+id).remove()); } }

    document.addEventListener('DOMContentLoaded', function() {
        ['col-prospecto', 'col-pendiente_pago', 'col-activo'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                new Sortable(el, { group: 'kanban', handle: '.card-handle', animation: 200, 
                    onEnd: function(evt) {
                        fetch("{{ route('owner.crm.move') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ user_id: evt.item.getAttribute('data-id'), status: evt.to.getAttribute('data-status') }) });
                    }
                });
            }
        });
    });
</script>
@endsection
