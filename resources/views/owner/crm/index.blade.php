@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #0c0c0e;
        --accent-sky: #38bdf8;
        --accent-amber: #f59e0b;
        --accent-emerald: #10b981;
        --stellar-blue: #1e3a8a;
    }

    body { background-color: var(--oled-bg) !important; color: #fff; overflow-x: hidden; }

    .header-hub {
        padding: 3rem 4% 1.5rem 4%; 
        display: flex;
        align-items: center;
        gap: 4rem;
    }

    .crm-container {
        display: flex;
        width: 100vw;
        padding: 0 3%; 
        gap: 0;
        height: 78vh;
        overflow-x: auto;
    }

    .kanban-col {
        width: 320px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        padding: 0 1.2rem;
        border-right: 1px dashed rgba(255,255,255,0.08); /* DIVISORES DE LA IMAGEN */
    }

    .col-header {
        background: linear-gradient(90deg, var(--stellar-blue) 0%, transparent 100%);
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 3.5rem;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-title { font-size: 0.75rem; font-weight: 950; letter-spacing: 0.35em; text-transform: uppercase; white-space: nowrap; }

    /* TARJETA ELITE SIMÉTRICA 130PX CON POST-IT */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid #ffffff; /* LÍNEA BLANCA 1PX */
        border-radius: 14px;
        padding: 1.2rem 1.2rem 1.2rem 2.2rem; /* EQUIDISTANTE */
        margin-bottom: 22px; 
        height: 135px; 
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center; 
        gap: 0.6rem;
        cursor: grab;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .kanban-card:hover {
        border-color: var(--accent-sky);
        box-shadow: 0 0 50px -10px var(--stellar-blue); /* SOMBRA AZUL ESTELAR */
        transform: translateY(-5px);
    }

    .post-it {
        position: absolute;
        left: 0;
        top: 0;
        width: 9px;
        height: 100%;
        border-radius: 14px 0 0 14px;
        z-index: 10;
        margin-left: -1px;
    }
    .p-sky { background: var(--accent-sky); }
    .p-amber { background: var(--accent-amber); }
    .p-emerald { background: var(--accent-emerald); }

    .card-controls { position: absolute; right: 12px; top: 10px; display: flex; gap: 12px; opacity: 0.2; }
    .kanban-card:hover .card-controls { opacity: 0.9; }

    .card-name { font-size: 0.85rem; font-weight: 950; text-transform: uppercase; color: #fff; line-height: 1.2; }
    .card-subtext { font-size: 0.6rem; color: #52525b; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; }
    .btn-sci-fi { 
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.15); color: var(--accent-sky); 
        padding: 5px 0; border-radius: 8px; font-size: 0.5rem; font-weight: 950; text-align: center; text-transform: uppercase;
    }
    .btn-sci-fi:hover { background: var(--accent-sky); color: #000; }

    .status-line {
        border-top: 1px solid rgba(255,255,255,0.08);
        padding-top: 6px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.52rem;
        font-weight: 950;
        letter-spacing: 1px;
    }

    /* SCANNER HUB BOX BLANCO CENTRADO */
    .scanner-hub-box {
        flex: 1;
        display: flex;
        justify-content: center;
        padding-top: 4rem;
        min-width: 440px;
    }
    .scanner-card {
        width: 400px;
        background: #000;
        border: 2px solid #ffffff; /* MARCO BLANCO TOTAL */
        border-radius: 40px;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 0 0 100px rgba(30, 58, 138, 0.2);
    }
    .scanner-divider { width: 100%; border-top: 4px solid var(--accent-sky); margin: 2rem 0; box-shadow: 0 0 15px var(--accent-sky); }
    
    .grid-centered {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        width: 100%;
        justify-items: center;
    }
    .mini-card {
        width: 100%;
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 20px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: 0.25s;
    }
    .mini-card:hover { border-color: #fff; background: rgba(255,255,255,0.05); }

</style>

<div class="header-hub">
    <h1 class="text-white text-3xl font-black uppercase tracking-[0.5em]">Command Hub</h1>
    <div class="h-px bg-zinc-800 flex-1 opacity-10"></div>
    <div class="flex items-center gap-6 text-sky-400 font-black text-xs uppercase tracking-[0.25em] animate-pulse">
        <i class="bi bi-robot fs-2"></i> AGENTE SOCIAL LIVE
    </div>
</div>

<div class="crm-container custom-scrollbar">
    
    @foreach([['id'=>'col-prospecto', 't'=>'Fase 01 | Leads', 'st'=>'prospecto', 'data'=>$prospectos, 'c'=>'sky', 'tag'=>'PROSPECTO OK', 'ic'=>'bi-person-fill'], ['id'=>'col-pendiente_pago', 't'=>'Fase 02 | Validar', 'st'=>'pendiente_pago', 'data'=>$pendientes, 'c'=>'amber', 'tag'=>'VALIDAR PAGO', 'ic'=>'bi-lightning-fill'], ['id'=>'col-activo', 't'=>'Fase 03 | Activos', 'st'=>'activo', 'data'=>$activos, 'c'=>'emerald', 'tag'=>'SAAS ACTIVO OK', 'ic'=>'bi-shield-fill-check']] as $col)
    <div class="kanban-col">
        <div class="col-header" style="border-left: 6px solid var(--accent-{{ $col['c'] }})">
            <span class="header-title">{{ $col['t'] }}</span>
            <span class="text-white/40 font-bold">{{ $col['data']->total() }}</span>
        </div>
        <div id="{{ $col['id'] }}" class="kanban-list" data-status="{{ $col['st'] }}">
            @foreach($col['data'] as $u)
            <div class="kanban-card" data-id="{{ $u->id }}" id="card-{{ $u->id }}">
                <div class="post-it p-{{ $col['c'] }}"></div>
                <div class="card-controls"><div class="card-handle fs-3 text-sky-400/20"><i class="bi bi-grip-vertical"></i></div></div>
                
                <div class="flex flex-col gap-0.5">
                    <div class="card-name">{{ $u->name }}</div>
                    <div class="card-subtext">{{ ($col['st'] == 'activo') ? ($u->empresa?->nombre_comercial ?? 'Setup OK') : ($u->lead_source ?? 'LinkedIn') }}</div>
                </div>

                <div class="btn-group-card">
                    @if($col['st'] == 'prospecto')
                        <button class="btn-sci-fi">IA DATA</button>
                        <button class="btn-sci-fi" style="opacity:0.2" disabled>MAIL</button>
                    @elseif($col['st'] == 'pendiente_pago')
                        <button class="btn-sci-fi" style="opacity:0.3">DOC</button>
                        <button class="btn-sci-fi" style="border-color:var(--accent-amber); color:var(--accent-amber)">ACT</button>
                    @else
                        <button class="btn-sci-fi" style="color:var(--accent-emerald)">PANEL</button>
                        <button class="btn-sci-fi" style="opacity:0.1" disabled>STATS</button>
                    @endif
                </div>

                <div class="status-line" style="color: var(--accent-{{ $col['c'] }})">
                    <span>{{ $col['tag'] }}</span>
                    <i class="bi {{ $col['ic'] }}"></i>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $col['data']->links() }}</div>
    </div>
    @endforeach

    <div class="scanner-hub-box">
        <div class="scanner-card">
            <div class="text-[0.75rem] font-black tracking-widest text-sky-400 uppercase flex justify-between w-full border-b border-white/5 pb-4">
                <span>Scan Operations</span>
                <div style="width:12px; height:12px;" class="bg-emerald-500 rounded-full animate-pulse shadow-[0_0_15px_#10b981]"></div>
            </div>
            <div class="scanner-divider"></div>
            <div class="grid-centered">
                @foreach(['LinkedIn'=>'58','Instagram'=>'42','Facebook'=>'31','WhatsApp'=>'24','Telegram'=>'15'] as $n => $v)
                    <div class="mini-card">
                        <span class="text-white fw-black text-2xl">{{ $v }}</span>
                        <span class="text-[0.55rem] text-zinc-500 fw-black uppercase">{{ $n }}</span>
                    </div>
                @endforeach
            </div>
            <button class="w-full bg-zinc-950 border-2 border-white/20 text-white text-[0.7rem] font-black uppercase py-6 rounded-[35px] mt-12 hover:bg-white hover:text-black transition-all">Protocolo Maestro Agent</button>
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
