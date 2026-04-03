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

    .header-hub {
        padding: 4rem 15% 2rem 15%; 
        display: flex;
        align-items: center;
        gap: 5rem;
        position: relative;
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

    .kanban-card:hover {
        border-color: var(--accent-sky);
        box-shadow: 0 15px 40px -10px rgba(56, 189, 248, 0.4);
        transform: translateY(-5px);
    }

    .card-controls {
        position: absolute;
        right: 12px;
        top: 8px;
        display: flex;
        gap: 12px;
        align-items: center;
    }
    
    .btn-trash { color: rgba(239, 68, 68, 0.2); background: transparent; border: 0; padding: 0; font-size: 0.9rem; transition: 0.2s; }
    .kanban-card:hover .btn-trash { color: rgba(239, 68, 68, 0.6); }
    .btn-trash:hover { color: #ef4444 !important; }

    .btn-archive { color: rgba(255, 255, 255, 0.1); background: transparent; border: 0; padding: 0; font-size: 0.9rem; transition: 0.2s; }
    .kanban-card:hover .btn-archive { color: rgba(255, 255, 255, 0.4); }
    .btn-archive:hover { color: var(--accent-amber) !important; }

    .card-handle { color: rgba(255,255,255,0.1); font-size: 1.1rem; }
    .kanban-card:hover .card-handle { color: var(--accent-sky); }

    .card-name { font-size: 0.9rem; font-weight: 950; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff; padding-right: 55px; }

    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
    .btn-sci-fi { 
        background: rgba(255,255,255,0.03); 
        border: 1px solid rgba(255,255,255,0.1); 
        color: var(--accent-sky); 
        padding: 7px 0; 
        border-radius: 10px; 
        font-size: 0.55rem; 
        font-weight: 950; 
        text-align: center; 
        text-decoration: none;
        text-transform: uppercase;
        display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .btn-sci-fi:hover:not(.disabled) { background: var(--accent-sky); color: #000; border-color: var(--accent-sky); }
    .btn-sci-fi.disabled { opacity: 0.1; }

    /* STATS HUB INTEGRADO AL FINAL */
    .stats-hub {
        background: rgba(12, 12, 14, 0.8);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(56, 189, 248, 0.2);
        border-radius: 24px;
        padding: 2rem;
        margin-left: 2.5rem;
        width: 320px;
        flex-shrink: 0;
    }
    .channels-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-top: 1rem; }
    .channel-block { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 1rem; display: flex; flex-direction: column; align-items: center; cursor: pointer; transition: 0.2s; }
    .channel-block:hover { border-color: var(--accent-sky); background: rgba(56, 189, 248, 0.1); }
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
                    <button class="btn-archive" onclick="archiveLead('{{ $pro->id }}')"><i class="bi bi-archive-fill"></i></button>
                    <button class="btn-trash" onclick="deleteLead('{{ $pro->id }}')"><i class="bi bi-trash3-fill"></i></button>
                    <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                </div>
                <div>
                    <div class="card-name">{{ $pro->name }}</div>
                    <div class="text-[0.65rem] text-zinc-600 fw-black uppercase">{{ $pro->lead_source ?? 'Landing Directo' }}</div>
                </div>
                <div class="btn-group-card">
                    <button onclick="openIA('{{ $pro->id }}', '{{ $pro->name }}')" class="btn-sci-fi"><i class="bi bi-robot"></i> IA DATA</button>
                    <button class="btn-sci-fi disabled">MAIL</button>
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
                    <div class="text-[0.65rem] text-amber-500/40 fw-black uppercase">Validar Pago</div>
                </div>
                <div class="btn-group-card">
                    @if($pen->payment_voucher)
                        <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-sci-fi" style="color:var(--accent-amber)"><i class="bi bi-file-earmark-pdf"></i> DOC</a>
                    @else
                        <span class="btn-sci-fi disabled">SIN DOC</span>
                    @endif
                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="m-0 p-0 d-grid">
                        @csrf
                        <button type="submit" class="btn-sci-fi" style="color:var(--accent-amber);border-color:rgba(245,158,11,0.2);">ACTIVAR</button>
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
                    <div class="text-[0.65rem] text-zinc-600 fw-black uppercase">{{ $act->empresa?->nombre_comercial ?? 'Setup OK' }}</div>
                </div>
                <div class="btn-group-card">
                    <button class="btn-sci-fi" style="color:var(--accent-emerald);border-color:rgba(16,185,129,0.2);">PANEL</button>
                    <button class="btn-sci-fi disabled">STATS</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- STATS HUB (DERECHA) -->
    <div class="stats-hub shadow-2xl">
        <div class="text-[0.6rem] font-black tracking-widest text-sky-400 uppercase mb-4 flex justify-between">
            <span>Social Live Scanner</span>
            <span class="text-amber-500">Simulación</span>
        </div>
        <div class="channels-grid">
            <div class="channel-block" onclick="alert('Mostrando actividad...')">
                <i class="bi bi-linkedin fs-5 text-sky-500"></i>
                <span class="text-white fw-black">58</span>
                <span class="text-[0.45rem] text-zinc-500">LINKEDIN</span>
            </div>
            <div class="channel-block">
                <i class="bi bi-instagram fs-5 text-pink-500"></i>
                <span class="text-white fw-black">42</span>
                <span class="text-[0.45rem] text-zinc-500">INSTAGRAM</span>
            </div>
            <div class="channel-block">
                <i class="bi bi-facebook fs-5 text-blue-600"></i>
                <span class="text-white fw-black">31</span>
                <span class="text-[0.45rem] text-zinc-500">FACEBOOK</span>
            </div>
            <div class="channel-block">
                <i class="bi bi-chat-dots-fill fs-5 text-emerald-500"></i>
                <span class="text-white fw-black">11</span>
                <span class="text-[0.45rem] text-zinc-500">CLOUD</span>
            </div>
        </div>
        <div class="mt-10">
            <button class="w-full bg-transparent border border-sky-500/30 text-sky-400 text-[0.6rem] font-black uppercase py-3 rounded-xl hover:bg-sky-500 hover:text-black">Protocolo Técnico</button>
        </div>
    </div>

</div>

{{-- MODAL IA --}}
<div id="ia-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.95); z-index:10001; align-items:center; justify-content:center; backdrop-filter:blur(15px);">
    <div style="width:550px; background:#09090b; border:2px solid var(--accent-sky); border-radius:32px; padding:3rem; box-shadow: 0 0 100px rgba(56, 189, 248, 0.2);">
        <h4 id="ia-name" class="text-white font-black uppercase mb-6 tracking-widest text-center">Analizando Lead</h4>
        <div class="bg-black/40 p-6 rounded-2xl border border-white/5 font-mono text-[0.8rem] text-zinc-400 mb-8">
            >>> Interés detectado en POS.<br>>>> Sugiriendo Plan Master Suite.
        </div>
        <button onclick="document.getElementById('ia-overlay').style.display='none'" class="btn-sci-fi w-full py-4 bg-sky-500 text-black border-0 fw-black">CERRAR</button>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    function openIA(id, name) {
        document.getElementById('ia-name').innerText = name;
        document.getElementById('ia-overlay').style.display = 'flex';
    }

    // Lógica de Draggables y AJAX se mantiene intacta
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
