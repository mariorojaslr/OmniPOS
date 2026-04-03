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

    /* APROVECHAMIENTO TOTAL DE LA PANTALLA */
    .header-hub {
        padding: 3rem 2.5% 2rem 2.5%; 
        display: flex;
        align-items: center;
        gap: 3rem;
    }

    .crm-container {
        display: flex;
        width: 100%;
        padding: 0 2.5%; 
        gap: 2rem;
        height: 78vh;
        overflow-x: auto;
    }

    .kanban-col {
        width: 340px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
    }

    .col-header {
        background: linear-gradient(90deg, var(--stellar-blue) 0%, transparent 100%);
        padding: 1rem 1.5rem;
        border-radius: 14px;
        border-left: 6px solid var(--accent-sky);
        margin-bottom: 2.5rem;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-title { font-size: 0.8rem; font-weight: 950; letter-spacing: 0.3em; text-transform: uppercase; }

    /* TARJETAS SIMÉTRICAS 130PX CON GLOW */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid rgba(255,255,255,0.7); 
        border-radius: 18px;
        padding: 1.2rem;
        margin-bottom: 20px; 
        height: 130px; 
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        cursor: grab;
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .kanban-card:hover { 
        border-color: var(--accent-sky); 
        box-shadow: 0 0 50px -10px rgba(56, 189, 248, 0.6); 
        transform: translateY(-5px); 
    }

    .card-controls { position: absolute; right: 12px; top: 10px; display: flex; gap: 12px; align-items: center; }
    .card-handle { color: rgba(255,255,255,0.2); font-size: 1.1rem; }
    .btn-trash { color: rgba(239, 68, 68, 0.2); background: transparent; border: 0; padding: 0; font-size: 0.9rem; transition: 0.2s; }
    .btn-trash:hover { color: #ef4444 !important; transform: scale(1.1); }
    .btn-archive { color: rgba(255, 255, 255, 0.1); background: transparent; border: 0; padding: 0; font-size: 0.9rem; transition: 0.2s; }
    .btn-archive:hover { color: var(--accent-amber) !important; transform: scale(1.1); }

    .card-name { font-size: 0.95rem; font-weight: 950; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff; padding-right: 50px; }

    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
    .btn-sci-fi { 
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.15); color: var(--accent-sky); 
        padding: 7px 0; border-radius: 10px; font-size: 0.55rem; font-weight: 950; text-align: center; text-transform: uppercase;
        display: flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none;
    }
    .btn-sci-fi:hover:not(.disabled) { background: var(--accent-sky); color: #000; }

    /* STATS HUB COLUMNA FINAL (FULL WIDTH OPTIMIZED) */
    .stats-hub {
        width: 350px;
        flex-shrink: 0;
        background: rgba(12, 12, 14, 0.85);
        backdrop-filter: blur(25px);
        border: 1px solid rgba(56, 189, 248, 0.25);
        border-radius: 28px;
        padding: 2.2rem;
        height: min-content;
        margin-right: 2.5%; 
    }
    .simulation-badge { font-size: 0.5rem; font-weight: 950; background: rgba(245,158,11,0.1); color: var(--accent-amber); padding: 5px 12px; border-radius: 10px; margin-bottom: 2rem; border: 1px solid rgba(245,158,11,0.2); display: inline-block; }

</style>

<div class="header-hub">
    <h1 class="text-white text-3xl font-black uppercase tracking-[0.5em]">Command Hub</h1>
    <div class="h-px bg-zinc-800 flex-1 opacity-10"></div>
    <div class="flex items-center gap-6 text-sky-400 font-black text-xs uppercase tracking-[0.2em] animate-pulse">
        <i class="bi bi-robot fs-2"></i> AGENTE SOCIAL LIVE
    </div>
</div>

<div class="crm-container custom-scrollbar">
    
    @php
        $cols = [
            ['id' => 'col-prospecto', 't' => 'Fase 01 | Leads', 'st' => 'prospecto', 'data' => $prospectos, 'c' => 'sky'],
            ['id' => 'col-pendiente_pago', 't' => 'Fase 02 | Validar', 'st' => 'pendiente_pago', 'data' => $pendientes, 'c' => 'amber'],
            ['id' => 'col-activo', 't' => 'Fase 03 | Activos', 'st' => 'activo', 'data' => $activos, 'c' => 'emerald']
        ];
    @endphp

    @foreach($cols as $col)
    <div class="kanban-col">
        <div class="col-header" style="{{ $col['c'] != 'sky' ? 'border-left-color: var(--accent-'.$col['c'].')' : '' }}">
            <span class="header-title {{ $col['c'] != 'sky' ? 'text-'.$col['c'].'-500' : '' }}">{{ $col['t'] }}</span>
            <span class="text-white/40 text-xs font-black">{{ $col['data']->total() }}</span>
        </div>
        <div id="{{ $col['id'] }}" class="kanban-list" data-status="{{ $col['st'] }}">
            @foreach($col['data'] as $u)
            <div class="kanban-card" data-id="{{ $u->id }}" id="card-{{ $u->id }}">
                <div class="card-controls">
                    @if($col['st'] == 'prospecto')
                    <button class="btn-archive" onclick="archiveLead('{{ $u->id }}')"><i class="bi bi-archive-fill"></i></button>
                    <button class="btn-trash" onclick="deleteLead('{{ $u->id }}')"><i class="bi bi-trash3-fill"></i></button>
                    @endif
                    <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                </div>
                <div>
                    <div class="card-name">{{ $u->name }}</div>
                    <div class="text-[0.65rem] text-zinc-600 fw-black uppercase">{{ ($col['st'] == 'activo') ? ($u->empresa?->nombre_comercial ?? 'Setup OK') : ($u->lead_source ?? 'Landing Directo') }}</div>
                </div>
                <div class="btn-group-card">
                    @if($col['st'] == 'prospecto')
                        <button onclick="openModalIA('{{ $u->name }}', '{{ $u->lead_source ?? 'META ADS' }}')" class="btn-sci-fi">IA DATA</button>
                        <button class="btn-sci-fi disabled">MAIL</button>
                    @elseif($col['st'] == 'pendiente_pago')
                        <a href="#" class="btn-sci-fi" style="color:var(--accent-amber)">DOC</a>
                        <form action="{{ route('owner.crm.activate', $u->id) }}" method="POST" class="m-0 p-0 d-grid">@csrf<button type="submit" class="btn-sci-fi" style="color:var(--accent-amber);border-color:rgba(245,158,11,0.25);">ACTIVAR</button></form>
                    @else
                        <button class="btn-sci-fi text-emerald-400" style="border-color:rgba(16,185,129,0.3);">PANEL</button>
                        <button class="btn-sci-fi disabled">STATS</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $col['data']->links() }}</div>
    </div>
    @endforeach

    <!-- STATS HUB (SIEMPRE VISIBLE AL BORDE DERECHO) -->
    <div class="stats-hub shadow-2xl">
        <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
            <span class="text-[0.65rem] font-black tracking-widest text-sky-400 uppercase">Live Operations</span>
            <div style="width:10px; height:10px;" class="bg-emerald-500 rounded-full animate-pulse shadow-[0_0_10px_#10b981]"></div>
        </div>
        <div class="simulation-badge"><i class="bi bi-eye-fill me-2"></i> MODO SIMULACIÓN</div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
            @foreach(['LinkedIn'=>'58', 'Instagram'=>'42', 'Facebook'=>'31', 'Cloud'=>'11'] as $lb => $val)
                <div class="channel-block" style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06); border-radius:18px; padding:1.2rem; display:flex; flex-direction:column; align-items:center;">
                    <span class="text-white fw-black text-xl">{{ $val }}</span>
                    <span class="text-[0.45rem] text-zinc-500 fw-black uppercase">{{ $lb }}</span>
                </div>
            @endforeach
        </div>
        <button class="w-full bg-zinc-950 border border-sky-500/20 text-sky-400 text-[0.65rem] font-black uppercase py-4 rounded-xl hover:bg-sky-500 hover:text-black transition-all">Protocolo Maestro</button>
    </div>

</div>

{{-- MODAL IA VENTANITA UNIFICADA --}}
<div id="ia-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.96); z-index:40000; align-items:center; justify-content:center; backdrop-filter:blur(15px);">
    <div style="width:550px; background:#09090b; border:2px solid var(--accent-sky); border-radius:40px; padding:4rem; box-shadow: 0 0 150px rgba(56, 189, 248, 0.4);">
        <h4 class="text-sky-400 font-black uppercase tracking-widest text-center mb-8"><i class="bi bi-robot me-3"></i> Reporte IA</h4>
        <div class="bg-black/50 p-8 rounded-[35px] border border-white/5 font-mono text-[0.8rem] text-zinc-400 mb-10 shadow-inner">
            <div class="text-white">>>> ANALIZANDO: <span id="ia-target" class="text-sky-400"></span></div>
            <hr class="border-white/5 my-6">
            <div>"Intención detectada vía perfil público. El prospecto requiere asistencia en logística POS. Lead enviado a validación."</div>
        </div>
        <button onclick="document.getElementById('ia-modal').style.display='none'" class="btn-sci-fi w-full py-5 bg-sky-500 text-black border-0 fw-black">CERRAR REPORTE</button>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    function openModalIA(name) { document.getElementById('ia-target').innerText = name; document.getElementById('ia-modal').style.display='flex'; }
    document.addEventListener('DOMContentLoaded', function() {
        ['col-prospecto', 'col-pendiente_pago', 'col-activo'].forEach(id => {
            const el = document.getElementById(id);
            if(el) { new Sortable(el, { group: 'kanban', handle: '.card-handle', animation: 200, 
                onEnd: function(evt) { fetch("{{ route('owner.crm.move') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ user_id: evt.item.getAttribute('data-id'), status: evt.to.getAttribute('data-status') }) }); } 
            }); }
        });
    });
    function archiveLead(id) { fetch("{{ route('owner.crm.archive') }}", { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{csrf_token()}}'}, body:JSON.stringify({user_id:id}) }).then(()=>document.getElementById('card-'+id).remove()); }
    function deleteLead(id) { if(confirm('¿Borrar definitivamente?')) { fetch("{{ route('owner.crm.delete') }}", { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{csrf_token()}}'}, body:JSON.stringify({user_id:id}) }).then(() => document.getElementById('card-'+id).remove()); } }
</script>
@endsection
