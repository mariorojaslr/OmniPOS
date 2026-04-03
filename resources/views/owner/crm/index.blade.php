@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #0c0c0e;
        --border-color: rgba(255, 255, 255, 0.15);
        --accent-sky: #38bdf8;
        --accent-amber: #f59e0b;
        --accent-emerald: #10b981;
        --stellar-blue: rgba(30, 58, 138, 0.7);
    }

    body { background-color: var(--oled-bg) !important; color: #fff; overflow-x: hidden; }

    /* MÁRGENES SAGRADOS DEL 15% */
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
        border: 1px solid rgba(255,255,255,0.6); 
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
    .kanban-card:hover { border-color: var(--accent-sky); box-shadow: 0 15px 45px -10px rgba(56, 189, 248, 0.4); transform: translateY(-5px); }

    .card-controls { position: absolute; right: 12px; top: 10px; display: flex; gap: 12px; align-items: center; }
    .card-handle { color: rgba(255,255,255,0.2); font-size: 1.1rem; }
    .btn-trash { color: rgba(239, 68, 68, 0.2); background: transparent; border: 0; padding: 0; font-size: 0.95rem; transition: 0.2s; }
    .btn-trash:hover { color: #ef4444 !important; transform: scale(1.1); }
    .btn-archive { color: rgba(255,255,255,0.1); background: transparent; border: 0; padding: 0; font-size: 0.95rem; transition: 0.2s; }
    .btn-archive:hover { color: var(--accent-amber) !important; transform: scale(1.1); }

    .card-name { font-size: 0.95rem; font-weight: 950; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff; padding-right: 45px; }

    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
    .btn-sci-fi { 
        background: rgba(255,255,255,0.03); 
        border: 1px solid rgba(255,255,255,0.15); 
        color: var(--accent-sky); 
        padding: 8px 0; 
        border-radius: 10px; 
        font-size: 0.55rem; 
        font-weight: 950; 
        text-align: center; 
        text-transform: uppercase;
        display: flex; align-items: center; justify-content: center; gap: 6px;
        text-decoration: none;
    }
    .btn-sci-fi:hover:not(.disabled) { background: var(--accent-sky); color: #000; border-color: var(--accent-sky); }
    .btn-sci-fi.disabled { opacity: 0.1; }

    /* STATS HUB (COLUMNA 04 - AL FINAL) */
    .stats-hub {
        width: 360px;
        flex-shrink: 0;
        background: rgba(12, 12, 14, 0.85);
        backdrop-filter: blur(25px);
        border: 1px solid rgba(56, 189, 248, 0.25);
        border-radius: 32px;
        padding: 2.5rem;
        height: min-content;
        margin-right: 15%; /* CIERRE SIMÉTRICO */
    }
    .simulation-badge { font-size: 0.5rem; font-weight: 950; background: rgba(245,158,11,0.15); color: var(--accent-amber); padding: 5px 12px; border-radius: 10px; margin-bottom: 2rem; border: 1px solid rgba(245,158,11,0.3); display: inline-block; letter-spacing: 1px; }

    /* OVERLAY MODAL - LA "VENTANITA" QUE TE GUSTABA */
    .master-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.95); z-index: 10005;
        display: none; align-items: center; justify-content: center; backdrop-filter: blur(15px);
    }
    .modal-sci-fi {
        width: 550px; background: #09090b; border: 2px solid var(--accent-sky); border-radius: 40px; padding: 4rem;
        box-shadow: 0 0 150px rgba(56, 189, 248, 0.3);
        animation: springIn 0.4s cubic-bezier(0.19, 1.2, 0.22, 1);
    }
    @keyframes springIn { from { opacity: 0; transform: scale(0.9) translateY(20px); } to { opacity: 1; transform: scale(1) translateY(0); } }
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
                    <button class="btn-archive" title="Olvidar" onclick="archiveLead('{{ $pro->id }}')"><i class="bi bi-archive-fill"></i></button>
                    <button class="btn-trash" title="Borrar" onclick="deleteLead('{{ $pro->id }}')"><i class="bi bi-trash3-fill"></i></button>
                    <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                </div>
                <div>
                    <div class="card-name">{{ $pro->name }}</div>
                    <div class="text-[0.65rem] text-zinc-600 fw-black uppercase">{{ $pro->lead_source ?? 'Landing Directo' }}</div>
                </div>
                <div class="btn-group-card">
                    <button onclick="openModalIA('{{ $pro->name }}', '{{ $pro->lead_source ?? 'META ADS' }}')" class="btn-sci-fi"><i class="bi bi-robot"></i> IA DATA</button>
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
                <div class="card-controls">
                    <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                </div>
                <div>
                    <div class="card-name">{{ $pen->name }}</div>
                    <div class="text-[0.7rem] text-amber-500/30 fw-black uppercase">Validación Pago</div>
                </div>
                <div class="btn-group-card">
                    @if($pen->payment_voucher)
                        <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-sci-fi" style="color:var(--accent-amber)"><i class="bi bi-file-earmark-pdf"></i> DOC</a>
                    @else
                        <span class="btn-sci-fi disabled">NO DOC</span>
                    @endif
                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="m-0 p-0 d-grid">
                        @csrf
                        <button type="submit" class="btn-sci-fi" style="color:var(--accent-amber);border-color:rgba(245,158,11,0.2);"><i class="bi bi-lightning-fill"></i> ACTIVAR</button>
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
                <div class="card-controls">
                    <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                </div>
                <div>
                    <div class="card-name text-zinc-300">{{ $act->name }}</div>
                    <div class="text-[0.7rem] text-zinc-600 fw-black uppercase">{{ $act->empresa?->nombre_comercial ?? 'Setup OK' }}</div>
                </div>
                <div class="btn-group-card">
                    <button class="btn-sci-fi text-emerald-400" style="border-color:rgba(16,185,129,0.2);"><i class="bi bi-gear-fill"></i> PANEL</button>
                    <button class="btn-sci-fi disabled text-emerald-700"><i class="bi bi-bar-chart-fill"></i> STATS</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- STATS HUB (COL 04) -->
    <div class="stats-hub shadow-2xl">
        <div class="text-[0.65rem] font-black tracking-widest text-sky-400 uppercase mb-4 flex justify-between border-b border-white/5 pb-4">
            <span>Live Scan Ops</span>
            <div style="width:10px; height:10px;" class="bg-emerald-500 rounded-full animate-pulse shadow-[0_0_10px_#10b981]"></div>
        </div>
        
        <div class="simulation-badge"><i class="bi bi-eye-fill me-2"></i> MODO SIMULACIÓN ACTIVA</div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
            @foreach(['LinkedIn' => ['58', 'sky-500', 'bi-linkedin'], 'Instagram' => ['42', 'pink-500', 'bi-instagram'], 'Facebook' => ['31', 'blue-600', 'bi-facebook'], 'Cloud' => ['11', 'emerald-500', 'bi-chat-dots-fill']] as $lb => $dt)
                <div class="channel-block" style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:18px; padding:1.25rem; display:flex; flex-direction:column; align-items:center; cursor:pointer;" onmouseover="this.style.borderColor='var(--accent-sky)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.05)'" onclick="openModalLog('{{ $lb }}')">
                    <i class="bi {{ $dt[2] }} fs-4 text-{{ $dt[1] }} mb-1"></i>
                    <span class="text-white fw-black text-lg">{{ $dt[0] }}</span>
                    <span class="text-[0.45rem] text-zinc-500 fw-black uppercase">{{ $lb }}</span>
                </div>
            @endforeach
        </div>

        <button onclick="openModalProtocol()" class="w-full bg-zinc-900 border border-sky-500/20 text-sky-400 text-[0.65rem] font-black uppercase py-4 rounded-2xl hover:bg-sky-500 hover:text-black transition-all">
            <i class="bi bi-shield-lock-fill me-2"></i> Protocolo Técnico
        </button>
    </div>

</div>

{{-- MODAL IA REPORT (LA VENTANITA) --}}
<div id="ia-modal" class="master-overlay">
    <div class="modal-sci-fi">
        <div class="flex justify-between items-center mb-10">
            <h4 class="text-sky-400 font-black uppercase tracking-widest m-0"><i class="bi bi-robot me-3"></i> Reporte Agente IA</h4>
            <button onclick="closeModals()" class="bg-transparent border-0 text-zinc-600 hover:text-white transition-colors"><i class="bi bi-x-lg fs-3"></i></button>
        </div>
        <div class="text-zinc-300 font-mono text-[0.85rem] bg-black/40 p-8 rounded-3xl border border-white/5 shadow-inner mb-10">
            <div class="mb-4 text-white">>>> ANALIZANDO: <span id="ia-target" class="text-sky-400"></span></div>
            <div class="mb-2 text-zinc-600">CANAL: <span id="ia-channel" class="text-emerald-400"></span></div>
            <hr class="border-white/5 my-6">
            <div class="leading-relaxed">"Interés detectado en migración de POS tradicional a Suite Móvil. Sugerencia: Enviar oferta Plan Enterprise."</div>
        </div>
        <button onclick="closeModals()" class="btn-sci-fi w-full py-5 bg-sky-500 text-black border-0 fw-black text-[0.85rem]">VOLVER A LA CONSOLA</button>
    </div>
</div>

{{-- MODAL LOG CANAL --}}
<div id="log-modal" class="master-overlay">
    <div class="modal-sci-fi">
        <h4 class="text-sky-400 font-black uppercase tracking-widest mb-10">Log Operativo: <span id="log-name"></span></h4>
        <div id="log-content" class="bg-black p-8 rounded-3xl font-mono text-[0.75rem] h-[300px] overflow-y-auto border border-white/5"></div>
        <button onclick="closeModals()" class="btn-sci-fi w-full mt-10 py-5 bg-sky-500 text-black border-0 fw-black">SALIR</button>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    function openModalIA(name, channel) {
        document.getElementById('ia-target').innerText = name;
        document.getElementById('ia-channel').innerText = channel;
        document.getElementById('ia-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function openModalLog(ch) {
        document.getElementById('log-name').innerText = ch;
        const cont = document.getElementById('log-content');
        cont.innerHTML = `<div class='text-zinc-600'>[09:12] Sincronizando con ${ch} Master Pool...</div>`;
        cont.innerHTML += `<div class='mt-4'>[10:45 AM] Prospecto detectado: Nivel de interés ALTO.</div>`;
        document.getElementById('log-modal').style.display = 'flex';
    }
    function closeModals() {
        document.querySelectorAll('.master-overlay').forEach(m => m.style.display = 'none');
        document.body.style.overflow = 'auto';
    }
    
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

    function archiveLead(id) { fetch("{{ route('owner.crm.archive') }}", { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{csrf_token()}}'}, body:JSON.stringify({user_id:id}) }).then(()=>document.getElementById('card-'+id).remove()); }
    function deleteLead(id) { fetch("{{ route('owner.crm.delete') }}", { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{csrf_token()}}'}, body:JSON.stringify({user_id:id}) }).then(()=>document.getElementById('card-'+id).remove()); }
</script>
@endsection
