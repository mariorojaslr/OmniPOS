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
        padding: 3rem 4% 2rem 4%; 
        display: flex;
        align-items: center;
        gap: 3rem;
    }

    .crm-container {
        display: flex;
        width: 100%;
        padding: 0 2%; 
        gap: 0; /* SEPARADOS POR DIVIDERS */
        height: 78vh;
        overflow-x: auto;
    }

    .kanban-col {
        width: 380px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        padding: 0 2rem;
        border-right: 1px dashed rgba(255,255,255,0.1); /* LÍNEA DIVISORIA DE LA IMAGEN */
    }

    .col-header {
        background: linear-gradient(90deg, #1e3a8a 0%, transparent 100%);
        padding: 1.2rem 2rem;
        border-radius: 12px;
        margin-bottom: 3.5rem;
        height: 65px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-title { font-size: 1rem; font-weight: 950; letter-spacing: 0.3em; text-transform: uppercase; color: #fff; }

    /* TARJETA "MAS O MENOS ASÍ" */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid rgba(255,255,255,0.6); 
        border-radius: 16px;
        padding: 1.5rem 1.5rem 0.5rem 2.8rem; /* ESPACIO PARA EL POST-IT */
        margin-bottom: 25px; 
        height: 145px; 
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: 0.3s;
    }
    .kanban-card:hover {
        border-color: var(--accent-sky);
        box-shadow: 0 0 30px rgba(56, 189, 248, 0.3);
    }

    /* EL POST-IT DE LA IMAGEN */
    .post-it {
        position: absolute;
        left: 0;
        top: 0;
        width: 10px;
        height: 100%;
        border-radius: 16px 0 0 16px;
        z-index: 5;
    }
    .p-sky { background: var(--accent-sky); }
    .p-amber { background: var(--accent-amber); }
    .p-emerald { background: var(--accent-emerald); }

    .card-controls { position: absolute; right: 15px; top: 12px; display: flex; gap: 15px; opacity: 0.2; }
    .kanban-card:hover .card-controls { opacity: 0.8; }

    .card-name { font-size: 1.1rem; font-weight: 950; text-transform: uppercase; color: #fff; margin-bottom: 2px; }
    .card-subtext { font-size: 0.75rem; color: #52525b; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 5px; }
    .btn-sci-fi { 
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.2); color: var(--accent-sky); 
        padding: 8px 0; border-radius: 10px; font-size: 0.7rem; font-weight: 950; text-align: center; text-transform: uppercase;
    }
    .btn-sci-fi:hover { background: var(--accent-sky); color: #000; }

    /* FOOTER STATUS DE LA IMAGEN */
    .card-footer-status {
        border-top: 1px solid rgba(255,255,255,0.05);
        margin-top: 10px;
        padding-top: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.6rem;
        font-weight: 950;
        color: var(--accent-sky);
        letter-spacing: 1.5px;
    }

    /* HUB CENTRADITO */
    .stats-hub-box {
        flex: 1;
        display: flex;
        justify-content: center;
        padding-top: 4rem;
    }
    .hub-styled {
        width: 380px;
        background: #000;
        border: 2px solid rgba(56, 189, 248, 0.4);
        border-radius: 40px;
        padding: 2.5rem;
    }
</style>

<div class="header-hub">
    <h1 class="text-white text-3xl font-black uppercase tracking-[0.5em]">Command Hub</h1>
    <div class="h-px bg-zinc-800 flex-1 opacity-10"></div>
    <div class="flex items-center gap-6 text-sky-400 font-black text-xs uppercase tracking-[0.2em] animate-pulse">
        <i class="bi bi-robot fs-2"></i> AGENTE SOCIAL LIVE
    </div>
</div>

<div class="crm-container custom-scrollbar">
    
    @foreach([['id'=>'col-prospecto', 't'=>'Fase 01 | Leads', 'st'=>'prospecto', 'data'=>$prospectos, 'c'=>'sky', 'tag'=>'PROSPECTO OK', 'icon'=>'bi-person-fill'], ['id'=>'col-pendiente_pago', 't'=>'Fase 02 | Validar', 'st'=>'pendiente_pago', 'data'=>$pendientes, 'c'=>'amber', 'tag'=>'VALIDAR PAGO', 'icon'=>'bi-lightning-fill'], ['id'=>'col-activo', 't'=>'Fase 03 | Activos', 'st'=>'activo', 'data'=>$activos, 'c'=>'emerald', 'tag'=>'SAAS ACTIVO OK', 'icon'=>'bi-shield-fill-check']] as $col)
    <div class="kanban-col">
        <div class="col-header" style="border-left: 6px solid var(--accent-{{ $col['c'] }})">
            <span class="header-title">{{ $col['t'] }}</span>
            <span class="text-white/40 font-black">{{ $col['data']->total() }}</span>
        </div>
        <div id="{{ $col['id'] }}" class="kanban-list" data-status="{{ $col['st'] }}">
            @foreach($col['data'] as $u)
            <div class="kanban-card" data-id="{{ $u->id }}" id="card-{{ $u->id }}">
                <div class="post-it p-{{ $col['c'] }}"></div>
                <div class="card-controls">
                    <div class="card-handle fs-4"><i class="bi bi-grip-vertical"></i></div>
                </div>
                <div>
                    <div class="card-name">{{ $u->name }}</div>
                    <div class="card-subtext">{{ ($col['st'] == 'activo') ? ($u->empresa?->nombre_comercial ?? 'GRAFILAR - MCR') : ($u->lead_source ?? 'ORGANIC') }}</div>
                </div>
                <div class="btn-group-card">
                    @if($col['st'] == 'prospecto')
                        <button class="btn-sci-fi">IA DATA</button>
                        <button class="btn-sci-fi" style="opacity:0.2" disabled>MAIL</button>
                    @elseif($col['st'] == 'pendiente_pago')
                        <button class="btn-sci-fi" style="opacity:0.2" disabled>NO DOC</button>
                        <button class="btn-sci-fi" style="border-color:var(--accent-amber); color:var(--accent-amber)">ACT</button>
                    @else
                        <button class="btn-sci-fi" style="color:var(--accent-emerald)">PANEL</button>
                        <button class="btn-sci-fi" style="opacity:0.1" disabled>STATS</button>
                    @endif
                </div>
                <div class="card-footer-status" style="color: var(--accent-{{ $col['c'] }})">
                    <span>{{ $col['tag'] }}</span>
                    <i class="bi {{ $col['icon'] }}"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <div class="stats-hub-box">
        <div class="hub-styled">
           <div class="text-[0.8rem] font-black tracking-widest text-sky-400 uppercase flex justify-between border-b border-white/5 pb-4 mb-4">
                <span>Scan Operations</span>
                <div style="width:12px; height:12px;" class="bg-emerald-500 rounded-full animate-pulse shadow-[0_0_15px_#10b981]"></div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 2rem;">
                @foreach(['LinkedIn'=>'58','Instagram'=>'42','Facebook'=>'31','WhatsApp'=>'24'] as $lb => $val)
                    <div class="p-5" style="background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.08); border-radius:20px; display:flex; flex-direction:column; align-items:center;">
                        <span class="text-white fw-black text-2xl">{{ $val }}</span>
                        <span class="text-[0.5rem] text-zinc-500 fw-black uppercase">{{ $lb }}</span>
                    </div>
                @endforeach
            </div>
            <button class="w-full bg-zinc-950 border-2 border-sky-500/20 text-sky-400 text-[0.7rem] font-black uppercase py-4 rounded-2xl mt-8 hover:bg-sky-500 hover:text-black transition-all">Protocolo Maestro Agent</button>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ['col-prospecto', 'col-pendiente_pago', 'col-activo'].forEach(id => {
            const el = document.getElementById(id);
            if(el) { new Sortable(el, { group:'kanban', handle:'.card-handle', animation:200 }); }
        });
    });
</script>
@endsection
