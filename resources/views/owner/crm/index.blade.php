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

    /* ANCHO TOTAL SIN BORDES INÚTILES */
    .header-hub {
        padding: 3rem 2% 1.5rem 2%; 
        display: flex;
        align-items: center;
        gap: 3rem;
    }

    .crm-container {
        display: flex;
        width: 100vw;
        padding: 0 2%; 
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
        padding: 1rem 1.7rem;
        border-radius: 16px;
        border-left: 6px solid var(--accent-sky);
        margin-bottom: 2.5rem;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-title { font-size: 0.85rem; font-weight: 950; letter-spacing: 0.35em; text-transform: uppercase; }

    /* TARJETAS ELITE 130PX CON GLOW Y RESPONSIVE */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid rgba(255,255,255,0.7); 
        border-radius: 22px;
        padding: 1.4rem;
        margin-bottom: 22px; 
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
        box-shadow: 0 0 60px -15px rgba(56, 189, 248, 0.7); 
        transform: translateY(-8px);
        background: rgba(12, 12, 14, 1);
    }

    /* CONTROLES DE TARJETA (MUY VISIBLES) */
    .card-controls { position: absolute; right: 18px; top: 12px; display: flex; gap: 15px; align-items: center; z-index: 50; }
    .card-handle { color: rgba(56, 189, 248, 0.4); font-size: 1.5rem; transition: 0.2s; cursor: grab; }
    .kanban-card:hover .card-handle { color: var(--accent-sky); transform: scale(1.1); }
    
    .btn-corner { background: transparent; border: 0; padding: 0; font-size: 1.1rem; opacity: 0.2; color: #fff; transition: 0.25s; cursor: pointer; }
    .kanban-card:hover .btn-corner { opacity: 0.7; }
    .btn-corner:hover { opacity: 1 !important; transform: scale(1.3); }

    .btn-archive:hover { color: var(--accent-amber) !important; }
    .btn-trash:hover { color: #ef4444 !important; }

    .card-name { font-size: 1rem; font-weight: 950; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff; padding-right: 70px; }

    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; }
    .btn-sci-fi { 
        background: rgba(255,255,255,0.06); 
        border: 1px solid rgba(255,255,255,0.2); 
        color: var(--accent-sky); 
        padding: 9px 0; 
        border-radius: 12px; 
        font-size: 0.6rem; 
        font-weight: 950; 
        text-align: center; 
        text-transform: uppercase;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        text-decoration: none;
        transition: 0.25s;
    }
    .btn-sci-fi:hover:not(.disabled) { background: var(--accent-sky); color: #000; box-shadow: 0 0 20px rgba(56, 189, 248, 0.4); }

    /* STATS HUB COLUMNA FINAL */
    .stats-hub {
        width: 360px;
        flex-shrink: 0;
        background: rgba(12, 12, 14, 0.9);
        backdrop-filter: blur(25px);
        border: 1px solid rgba(56, 189, 248, 0.3);
        border-radius: 35px;
        padding: 2.5rem;
        height: fit-content;
        margin-right: 2.5%; 
    }
    .simulation-badge { font-size: 0.6rem; font-weight: 950; background: rgba(245,158,11,0.2); color: var(--accent-amber); padding: 6px 14px; border-radius: 10px; margin-bottom: 2rem; border: 1px solid rgba(245,158,11,0.4); display: inline-block; letter-spacing: 1px; }

    /* VENTANITA SCI-FI EXTREMA */
    .master-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.98); z-index: 50000;
        display: none; align-items: center; justify-content: center; backdrop-filter: blur(30px);
    }
    .modal-sci-fi {
        width: 620px; background: #000; border: 2.5px solid var(--accent-sky); border-radius: 50px; padding: 5rem;
        box-shadow: 0 0 300px rgba(56, 189, 248, 0.4);
        animation: snapIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes snapIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
</style>

<div class="header-hub">
    <h1 class="text-white text-3xl font-black uppercase tracking-[0.6em]">Command Hub</h1>
    <div class="h-px bg-zinc-800 flex-1 opacity-10"></div>
    <div class="flex items-center gap-6 text-sky-400 font-black text-xs uppercase tracking-[0.25em] animate-pulse">
        <i class="bi bi-robot fs-2"></i> AGENTE SOCIAL LIVE
    </div>
</div>

<div class="crm-container custom-scrollbar">
    
    @foreach([['id'=>'col-prospecto', 't'=>'Fase 01 | Leads', 'st'=>'prospecto', 'data'=>$prospectos, 'c'=>'sky'], ['id'=>'col-pendiente_pago', 't'=>'Fase 02 | Validar', 'st'=>'pendiente_pago', 'data'=>$pendientes, 'c'=>'amber'], ['id'=>'col-activo', 't'=>'Fase 03 | Activos', 'st'=>'activo', 'data'=>$activos, 'c'=>'emerald']] as $col)
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
                    <button class="btn-corner btn-archive" title="Olvidar" onclick="archiveLead('{{ $u->id }}')"><i class="bi bi-archive-fill"></i></button>
                    <button class="btn-corner btn-trash" title="Borrar" onclick="deleteLead('{{ $u->id }}')"><i class="bi bi-trash3-fill"></i></button>
                    @endif
                    <div class="card-handle" title="Arrastrar"><i class="bi bi-grip-vertical"></i></div>
                </div>
                <div>
                    <div class="card-name">{{ $u->name }}</div>
                    <div class="text-[0.7rem] text-zinc-600 fw-black uppercase tracking-widest">{{ ($col['st'] == 'activo') ? ($u->empresa?->nombre_comercial ?? 'Setup OK') : ($u->lead_source ?? 'LinkedIn') }}</div>
                </div>
                <div class="btn-group-card">
                    @if($col['st'] == 'prospecto')
                        <button onclick="openIA('{{ $u->name }}', '{{ $u->lead_source ?? 'LinkedIn' }}')" class="btn-sci-fi">IA DATA</button>
                        <button class="btn-sci-fi disabled">MAIL</button>
                    @elseif($col['st'] == 'pendiente_pago')
                        <button onclick="alert('Abriendo DOC...')" class="btn-sci-fi" style="color:var(--accent-amber)">DOC</button>
                        <form action="{{ route('owner.crm.activate', $u->id) }}" method="POST" class="m-0 p-0 d-grid">@csrf<button type="submit" class="btn-sci-fi" style="color:var(--accent-amber);">ACTIVAR</button></form>
                    @else
                        <button onclick="alert('Abriendo PANEL EMPRESA...')" class="btn-sci-fi text-emerald-400" style="border-color:rgba(16,185,129,0.3);">PANEL</button>
                        <button onclick="alert('Mostrando STATS EMPRESA...')" class="btn-sci-fi text-emerald-600">STATS</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $col['data']->links() }}</div>
    </div>
    @endforeach

    <!-- LIVE STATS HUB -->
    <div class="stats-hub shadow-2xl">
        <div class="text-[0.75rem] font-black tracking-widest text-sky-400 uppercase mb-8 flex justify-between border-b border-white/5 pb-5">
            <span>Scan Operations</span>
            <div style="width:12px; height:12px;" class="bg-emerald-500 rounded-full animate-pulse shadow-[0_0_15px_#10b981]"></div>
        </div>
        <div class="simulation-badge"><i class="bi bi-eye-fill me-2"></i> SIMULACIÓN ACTIVA</div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; margin-bottom: 3rem;">
            @foreach(['LinkedIn' => ['58', 'sky-500', 'bi-linkedin'], 'Instagram' => ['42', 'pink-500', 'bi-instagram'], 'Facebook' => ['31', 'blue-600', 'bi-facebook'], 'Cloud' => ['11', 'emerald-500', 'bi-chat-dots-fill']] as $lb => $dt)
                <div class="channel-block" style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); border-radius:22px; padding:1.5rem; display:flex; flex-direction:column; align-items:center; cursor:pointer;" onmouseover="this.style.borderColor='var(--accent-sky)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.08)'" onclick="alert('Mostrando actividad simulated...')">
                    <i class="bi {{ $dt[2] }} fs-3 text-{{ $dt[1] }} mb-2"></i>
                    <span class="text-white fw-black text-xl">{{ $dt[0] }}</span>
                    <span class="text-[0.5rem] text-zinc-500 fw-black uppercase">{{ $lb }}</span>
                </div>
            @endforeach
        </div>
        <button class="w-full bg-zinc-950 border border-sky-500/20 text-sky-400 text-[0.7rem] font-black uppercase py-4 rounded-2xl hover:bg-sky-500 hover:text-black transition-all">Protocolo Maestro</button>
    </div>

</div>

{{-- VENTANITA IA REPORTE --}}
<div id="ia-modal" class="master-overlay">
    <div class="modal-sci-fi">
        <h4 class="text-sky-400 font-black uppercase tracking-widest text-center mb-10"><i class="bi bi-robot me-3"></i> Reporte Agente IA</h4>
        <div class="bg-black/50 p-12 rounded-[45px] border border-white/5 font-mono text-[0.9rem] text-zinc-400 mb-12 shadow-inner">
            <div class="text-white mb-4">>>> ANALIZANDO: <span id="ia-target" class="text-sky-400"></span></div>
            <div class="text-zinc-600 mb-8">CANAL: <span id="ia-channel" class="text-emerald-400"></span></div>
            <div class="leading-relaxed border-t border-white/5 pt-8">
                "Este prospecto ha sido calificado como PRIORIDAD ALTA. El Agente detectó una necesidad urgente de facturación móvil para reparto. Sugerencia: Plan Pyme $25.000 + Pack Streaming."
            </div>
        </div>
        <button onclick="document.getElementById('ia-modal').style.display='none'" class="btn-sci-fi w-full py-6 bg-sky-500 text-black border-0 fw-black text-[1rem]">CERRAR OPERACIÓN</button>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    function openIA(name, channel) { document.getElementById('ia-target').innerText = name; document.getElementById('ia-channel').innerText = channel; document.getElementById('ia-modal').style.display = 'flex'; }
    function archiveLead(id) { fetch("{{ route('owner.crm.archive') }}", { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{csrf_token()}}'}, body:JSON.stringify({user_id:id}) }).then(()=>document.getElementById('card-'+id).remove()); }
    function deleteLead(id) { if(confirm('¿Seguro?')) { fetch("{{ route('owner.crm.delete') }}", { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{csrf_token()}}'}, body:JSON.stringify({user_id:id}) }).then(() => document.getElementById('card-'+id).remove()); } }
    document.addEventListener('DOMContentLoaded', function() {
        ['col-prospecto', 'col-pendiente_pago', 'col-activo'].forEach(id => {
            const el = document.getElementById(id);
            if(el) { new Sortable(el, { group:'kanban', handle:'.card-handle', animation:200, onEnd: function(evt) { fetch("{{ route('owner.crm.move') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ user_id: evt.item.getAttribute('data-id'), status: evt.to.getAttribute('data-status') }) }); } }); }
        });
    });
</script>
@endsection
