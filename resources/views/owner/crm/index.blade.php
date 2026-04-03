@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #0c0c0e;
        --border-color: rgba(255, 255, 255, 0.2);
        --accent-sky: #38bdf8;
        --accent-amber: #f59e0b;
        --accent-emerald: #10b981;
        --stellar-blue: rgba(30, 58, 138, 0.7);
    }

    body { background-color: var(--oled-bg) !important; color: #fff; overflow-x: hidden; }

    /* FULL CANVAS 100% UTILIZATION */
    .header-hub {
        padding: 3rem 2% 2rem 2%; 
        display: flex;
        align-items: center;
        gap: 3rem;
    }

    .crm-container {
        display: flex;
        width: 100%;
        padding: 0 2%; 
        gap: 3rem;
        height: 78vh;
        overflow-x: auto;
        align-items: flex-start;
    }

    .kanban-col {
        width: 320px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
    }

    .col-header {
        background: linear-gradient(90deg, var(--stellar-blue) 0%, transparent 100%);
        padding: 1rem 1.7rem;
        border-radius: 14px;
        border-left: 6px solid var(--accent-sky);
        margin-bottom: 2.5rem;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-title { font-size: 0.85rem; font-weight: 950; letter-spacing: 0.35em; text-transform: uppercase; }

    /* TARJETA ELITE CON POST-IT Y LINEA BLANCA 1PX */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid #ffffff; /* LÍNEA BLANCA 1PX SAGRADA */
        border-radius: 12px;
        padding: 1.2rem 1.2rem 1.2rem 1.8rem;
        margin-bottom: 20px; 
        height: 130px; 
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        cursor: grab;
        transition: 0.3s;
    }
    .kanban-card:hover { 
        box-shadow: 0 0 40px -10px rgba(56, 189, 248, 0.5); 
        transform: translateX(5px);
        background: #111;
    }

    /* EL POST-IT LATERAL */
    .post-it {
        position: absolute;
        left: -8px;
        top: 0;
        width: 12px;
        height: 100%;
        border-radius: 4px 0 0 4px;
        z-index: 5;
    }
    .post-it-sky { background: var(--accent-sky); }
    .post-it-amber { background: var(--accent-amber); }
    .post-it-emerald { background: var(--accent-emerald); }

    .card-controls { position: absolute; right: 12px; top: 10px; display: flex; gap: 12px; align-items: center; z-index: 20; }
    .card-handle { color: rgba(255,255,255,0.2); font-size: 1.4rem; cursor: grab; }
    .kanban-card:hover .card-handle { color: var(--accent-sky); }
    
    .btn-corner { background: transparent; border: 0; padding: 0; font-size: 1rem; color: #fff; opacity: 0.2; transition: 0.25s; cursor: pointer; }
    .kanban-card:hover .btn-corner { opacity: 0.7; }
    .btn-corner:hover { opacity: 1 !important; transform: scale(1.2); }

    .card-name { font-size: 0.95rem; font-weight: 950; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff; }

    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
    .btn-sci-fi { 
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.15); color: var(--accent-sky); 
        padding: 7px 0; border-radius: 8px; font-size: 0.55rem; font-weight: 950; text-align: center; text-transform: uppercase;
        display: flex; align-items: center; justify-content: center; gap: 5px; text-decoration: none;
    }
    .btn-sci-fi:hover:not(.disabled) { background: var(--accent-sky); color: #000; }

    /* STATS HUB CENTRADO EN EL ESPACIO FINAL */
    .stats-space {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding-top: 5rem;
        min-width: 400px;
    }
    .stats-hub {
        width: 380px;
        background: rgba(12, 12, 14, 0.9);
        backdrop-filter: blur(25px);
        border: 2px solid rgba(56, 189, 248, 0.4);
        border-radius: 35px;
        padding: 2.5rem;
        box-shadow: 0 0 100px rgba(56, 189, 248, 0.15);
    }
    .hub-divider { border-top: 2px solid var(--accent-sky); margin: 1.5rem 0; box-shadow: 0 0 10px var(--accent-sky); }
    .simulation-badge { font-size: 0.6rem; font-weight: 950; background: rgba(245,158,11,0.2); color: var(--accent-amber); padding: 5px 15px; border-radius: 10px; border: 1px solid var(--accent-amber); margin-bottom: 1rem; }

    .channels-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .channel-block { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 1.2rem; display: flex; flex-direction: column; align-items: center; cursor: pointer; transition: 0.2s; }
    .channel-block:hover { border-color: var(--accent-sky); background: rgba(56, 189, 248, 0.1); }
</style>

<div class="header-hub">
    <h1 class="text-white text-3xl font-black uppercase tracking-[0.6em]">Command Hub</h1>
    <div class="h-px bg-zinc-800 flex-1 opacity-20"></div>
    <div class="flex items-center gap-6 text-sky-400 font-black text-xs uppercase tracking-[0.3em] animate-pulse">
        <i class="bi bi-robot fs-2"></i> AGENTE SOCIAL LIVE
    </div>
</div>

<div class="crm-container custom-scrollbar">
    
    @foreach([['id'=>'col-prospecto', 't'=>'Fase 01 | Leads', 'st'=>'prospecto', 'data'=>$prospectos, 'c'=>'sky'], ['id'=>'col-pendiente_pago', 't'=>'Fase 02 | Validar', 'st'=>'pendiente_pago', 'data'=>$pendientes, 'c'=>'amber'], ['id'=>'col-activo', 't'=>'Fase 03 | Activos', 'st'=>'activo', 'data'=>$activos, 'c'=>'emerald']] as $col)
    <div class="kanban-col">
        <div class="col-header" style="{{ $col['c'] != 'sky' ? 'border-left-color: var(--accent-'.$col['c'].')' : '' }}">
            <span class="header-title {{ $col['c'] != 'sky' ? 'text-'.$col['c'].'-500' : '' }}">{{ $col['t'] }}</span>
        </div>
        <div id="{{ $col['id'] }}" class="kanban-list" data-status="{{ $col['st'] }}">
            @foreach($col['data'] as $u)
            <div class="kanban-card" data-id="{{ $u->id }}" id="card-{{ $u->id }}">
                <div class="post-it post-it-{{ $col['c'] }}"></div>
                <div class="card-controls">
                    @if($col['st'] == 'prospecto')
                    <button class="btn-corner" onclick="archiveLead('{{ $u->id }}')"><i class="bi bi-archive-fill"></i></button>
                    <button class="btn-corner" onclick="deleteLead('{{ $u->id }}')"><i class="bi bi-trash3-fill"></i></button>
                    @endif
                    <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                </div>
                <div>
                    <div class="card-name">{{ $u->name }}</div>
                    <div class="text-[0.65rem] text-zinc-500 fw-black uppercase">{{ ($col['st'] == 'activo') ? ($u->empresa?->nombre_comercial ?? 'Setup OK') : ($u->lead_source ?? 'LinkedIn') }}</div>
                </div>
                <div class="btn-group-card">
                    @if($col['st'] == 'prospecto')
                        <button onclick="openIA('{{ $u->name }}')" class="btn-sci-fi">IA DATA</button>
                        <button class="btn-sci-fi disabled">MAIL</button>
                    @elseif($col['st'] == 'pendiente_pago')
                        <button class="btn-sci-fi">DOC</button>
                        <form action="{{ route('owner.crm.activate', $u->id) }}" method="POST" class="m-0 p-0 d-grid">@csrf<button type="submit" class="btn-sci-fi">ACTIVAR</button></form>
                    @else
                        <button class="btn-sci-fi">PANEL</button>
                        <button class="btn-sci-fi disabled">STATS</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $col['data']->links() }}</div>
    </div>
    @endforeach

    <div class="stats-space">
        <div class="stats-hub">
            <div class="text-[0.75rem] font-black tracking-widest text-sky-400 uppercase flex justify-between">
                <span>Scan Operations</span>
                <div style="width:12px; height:12px;" class="bg-emerald-500 rounded-full animate-pulse"></div>
            </div>
            <div class="hub-divider"></div>
            <div class="simulation-badge"><i class="bi bi-eye-fill me-2"></i> SIMULACIÓN ACTIVA</div>
            
            <div class="channels-grid">
                @foreach(['LinkedIn'=>'58','Instagram'=>'42','Facebook'=>'31','WhatsApp'=>'24','Telegram'=>'15'] as $lb => $val)
                    <div class="channel-block">
                        <span class="text-white fw-black text-2xl">{{ $val }}</span>
                        <span class="text-[0.55rem] text-zinc-500 fw-black uppercase">{{ $lb }}</span>
                    </div>
                @endforeach
            </div>

            <button class="w-full bg-zinc-950 border-2 border-sky-500/30 text-sky-400 text-[0.75rem] font-black uppercase py-4 rounded-2xl mt-8 hover:bg-sky-500 hover:text-black transition-all">Protocolo Maestro Agent</button>
        </div>
    </div>

</div>

<div id="ia-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.98); z-index:50000; align-items:center; justify-content:center; backdrop-filter:blur(20px);">
    <div style="width:580px; background:#000; border:3px solid var(--accent-sky); border-radius:50px; padding:4rem; box-shadow:0 0 100px var(--accent-sky);">
        <h4 class="text-sky-400 font-black uppercase tracking-widest text-center mb-8">IA REPORT DATA</h4>
        <div class="bg-zinc-900/50 p-10 rounded-[40px] font-mono text-[0.9rem] text-zinc-400 border border-white/5 shadow-inner">
            >>> TARGET: <span id="ia-target" class="text-white"></span><br>
            <hr class="border-white/5 my-6">
            >>> ANALYZING PROFILE...<br>
            >>> STATUS: QUALIFIED.
        </div>
        <button onclick="document.getElementById('ia-modal').style.display='none'" class="btn-sci-fi w-full py-6 mt-10 bg-sky-500 text-black border-0 fw-black text-[0.9rem]">FINALIZAR REPORTE</button>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    function openIA(name) { document.getElementById('ia-target').innerText = name; document.getElementById('ia-modal').style.display='flex'; }
    function archiveLead(id) { fetch("{{ route('owner.crm.archive') }}", { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{csrf_token()}}'}, body:JSON.stringify({user_id:id}) }).then(()=>document.getElementById('card-'+id).remove()); }
    function deleteLead(id) { if(confirm('¿Borrar lead?')) { fetch("{{ route('owner.crm.delete') }}", { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{csrf_token()}}'}, body:JSON.stringify({user_id:id}) }).then(() => document.getElementById('card-'+id).remove()); } }
    document.addEventListener('DOMContentLoaded', function() {
        ['col-prospecto', 'col-pendiente_pago', 'col-activo'].forEach(id => {
            const el = document.getElementById(id);
            if(el) { new Sortable(el, { group:'kanban', handle:'.card-handle', animation:200, onEnd: function(evt) { fetch("{{ route('owner.crm.move') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ user_id: evt.item.getAttribute('data-id'), status: evt.to.getAttribute('data-status') }) }); } }); }
        });
    });
</script>
@endsection
