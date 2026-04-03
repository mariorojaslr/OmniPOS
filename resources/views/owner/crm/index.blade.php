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
        padding: 1rem 1.5% 0.5rem 1.5%; 
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap; /* Permitir que baje en móvil */
    }

    .crm-container {
        display: flex;
        width: 100%;
        padding: 0 1.5%; 
        gap: 0.5rem;
        height: auto; /* Dejar que crezca en móvil */
        min-height: 82vh;
        overflow-x: auto;
        flex-wrap: nowrap;
    }

    .kanban-col {
        flex: 1;
        min-width: 320px;
        display: flex;
        flex-direction: column;
        padding: 0 1rem;
        border-right: 1px dashed rgba(255,255,255,0.08);
    }

    .kanban-list {
        flex: 1;
        min-height: 500px; /* Asegura que se pueda soltar en columnas vacías */
        padding-bottom: 200px; /* Margen de maniobra inferior */
    }

    /* MEDIA QUERY PARA MÓVILES */
    @media (max-width: 768px) {
        .header-hub {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
            padding-bottom: 2rem;
        }
        .crm-container {
            flex-direction: column;
            overflow-x: hidden;
            gap: 3rem;
        }
        .kanban-col {
            min-width: 100%;
            border-right: none;
            border-bottom: 1px dashed rgba(255,255,255,0.1);
            padding-bottom: 2rem;
        }
        .scanner-hub-box {
            min-width: 100% !important;
            padding-top: 2rem !important;
        }
        .scanner-card {
            width: 100% !important;
            border-radius: 20px !important;
            padding: 1.5rem !important;
        }
        .header-hub h1 {
            font-size: 1.5rem !important;
            letter-spacing: 0.2em !important;
        }
    }

    .col-header {
        background: linear-gradient(90deg, var(--stellar-blue) 0%, transparent 100%);
        padding: 0.8rem 1.6rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        height: 50px; 
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-title { font-size: 0.8rem; font-weight: 950; letter-spacing: 0.35em; text-transform: uppercase; white-space: nowrap; color: #fff; }

    /* TARJETA ELITE SIN DESBORDES */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid #ffffff; 
        border-radius: 14px;
        padding: 1.2rem 1.2rem 1.2rem 2.8rem; /* PADDING EQUILIBRADO */
        margin-bottom: 22px; 
        height: 155px; /* UN POCO MÁS PARA EVITAR APLASTAMIENTO */
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center; 
        gap: 0.6rem;
        cursor: grab;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    .kanban-card:hover {
        border-color: var(--accent-sky);
        box-shadow: 0 0 50px -10px var(--stellar-blue); 
        transform: translateY(-5px);
    }

    .post-it {
        position: absolute;
        left: 0;
        top: 0;
        width: 10px;
        height: 100%;
        border-radius: 14px 0 0 14px;
        z-index: 10;
        margin-left: -1px;
    }
    .p-sky { background: var(--accent-sky); }
    .p-amber { background: var(--accent-amber); }
    .p-emerald { background: var(--accent-emerald); }

    .card-controls { position: absolute; right: 12px; top: 12px; display: flex; gap: 12px; opacity: 0.2; }
    .kanban-card:hover .card-controls { opacity: 1; }
    .card-handle { color: var(--accent-sky); font-size: 1.5rem; }

    /* NOMBRE CON LÍMITE DE 2 RENGLONES */
    .card-name { 
        font-size: 0.85rem; 
        font-weight: 950; 
        text-transform: uppercase; 
        color: #fff; 
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .card-subtext { font-size: 0.6rem; color: #52525b; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 5px; }
    .btn-sci-fi { 
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.15); color: var(--accent-sky); 
        padding: 6px 0; border-radius: 8px; font-size: 0.52rem; font-weight: 950; text-align: center; text-transform: uppercase;
        cursor: pointer;
    }

    .status-line {
        border-top: 1px solid rgba(255,255,255,0.08);
        padding-top: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.5rem;
        font-weight: 950;
        letter-spacing: 1.5px;
    }

    /* SCANNER HUB - SIMETRÍA BLANCA TOTAL */
    .scanner-hub-box {
        flex: 1;
        display: flex;
        justify-content: center;
        padding-top: 4rem;
        min-width: 480px;
    }
    .scanner-card {
        width: 420px;
        background: #000;
        border: 2px solid #ffffff; 
        border-radius: 40px;
        padding: 3rem 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2.5rem;
    }

    .box-info-white {
        width: 100%;
        border: 1px solid #ffffff; 
        border-radius: 12px;
        padding: 0.8rem;
        font-size: 0.7rem;
        font-weight: 950;
        text-transform: uppercase;
        color: #ffffff;
        text-align: center;
        letter-spacing: 2px;
    }
    
    .grid-centered {
        display: flex;
        flex-wrap: wrap;
        justify-content: center; 
        gap: 1.2rem;
        width: 100%;
    }
    .mini-card {
        width: 155px;
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.2); 
        border-radius: 20px;
        padding: 1.4rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        cursor: pointer;
    }
    .mini-card:hover { 
        border-color: var(--accent-sky); 
        background: rgba(56, 189, 248, 0.05); 
        box-shadow: 0 0 30px -5px var(--stellar-blue);
    }
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
        <div id="{{ $col['id'] }}" class="kanban-list" data-status="{{ $col['st'] }}" style="min-height: 60vh; padding-bottom: 100px;">
            @foreach($col['data'] as $u)
            <div class="kanban-card" data-id="{{ $u->id }}" id="card-{{ $u->id }}">
                <div class="post-it p-{{ $col['c'] }}"></div>
                <div class="card-controls">
                    <div class="flex gap-2">
                        <button onclick="forgetLead({{ $u->id }})" class="text-amber-500 hover:text-amber-400" title="Olvidar Lead">
                            <i class="bi bi-eye-slash-fill"></i>
                        </button>
                        <button onclick="deleteLead({{ $u->id }})" class="text-red-500 hover:text-red-400" title="Borrar Definitivamente">
                            <i class="bi bi-trash3-fill"></i>
                        </button>
                    </div>
                    <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                </div>
                
                <div class="flex flex-col gap-0.5" onclick="openIA('{{ $u->name }}')">
                    <div class="card-name">{{ $u->name }}</div>
                    <div class="card-subtext">{{ ($col['st'] == 'activo' || ($u->empresa && $u->empresa->activo)) ? ($u->empresa?->nombre_comercial ?? 'GRAFILAR - MCR') : ($u->lead_source ?? 'LinkedIn') }}</div>
                </div>

                <div class="btn-group-card">
                    @if($col['st'] == 'prospecto')
                        <button onclick="openIA('{{ $u->name }}')" class="btn-sci-fi">IA DATA</button>
                        <button class="btn-sci-fi" style="opacity:0.2" disabled>MAIL</button>
                    @elseif($col['st'] == 'pendiente_pago')
                        <button onclick="openIA('{{ $u->name }}')" class="btn-sci-fi">REPORTE</button>
                        <button onclick="window.location.href='{{ route('owner.crm.activate', $u->id) }}'" class="btn-sci-fi" style="border-color: var(--accent-emerald); color: var(--accent-emerald);">ACT</button>
                    @else
                        <button onclick="window.location.href='{{ route('owner.empresas.users.index', $u->empresa->id) }}'" class="btn-sci-fi">PANEL</button>
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
    </div>
    @endforeach

    <div class="scanner-hub-box">
        <div class="scanner-card">
            <div class="box-info-white">Scan Operations</div>
            
            <div class="grid-centered">
                @foreach($agent_data as $n => $d)
                    <div class="mini-card" onclick="openAgentReport('{{ $n }}', {{ $d['scanned'] }}, {{ $d['hits'] }}, {{ $d['hunted'] }}, '{{ $d['color'] }}')">
                        <span class="text-white fw-black text-2xl" style="line-height:1">{{ str_pad($d['scanned'], 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-[0.45rem] text-zinc-600 fw-black uppercase mt-1">{{ $n }}</span>
                    </div>
                @endforeach
            </div>

            <div class="box-info-white">Protocolo Maestro Agent</div>
        </div>
    </div>
</div>

{{-- MODAL REPORTE AGENTE --}}
<div id="agent-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); z-index:60000; align-items:center; justify-content:center; backdrop-filter:blur(15px);">
    <div style="width:650px; background:#0c0c0e; border:2px solid #fff; border-radius:40px; padding:3rem; box-shadow:0 0 80px rgba(255,255,255,0.05);">
        <div class="text-center mb-5">
            <div id="agent-icon" class="mb-3 fs-1 text-sky-400"><i class="bi bi-robot"></i></div>
            <h3 id="agent-name" class="text-white font-black uppercase tracking-widest">LinkedIn Agent</h3>
        </div>
        
        <div class="bg-zinc-900/40 p-4 rounded-2xl mb-6">
            <h5 class="text-[0.65rem] font-black uppercase tracking-widest text-zinc-500 mb-3 border-b border-white/5 pb-2">Bitácora de Conversación en Vivo</h5>
            <div id="agent-logs" class="space-y-3 font-mono text-[0.75rem]" style="max-height: 250px; overflow-y: auto;">
                {{-- Inyectado por JS --}}
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div class="text-center">
                <span class="block text-zinc-500 text-[0.6rem] font-bold uppercase">Escaneos</span>
                <span id="stat-scanned" class="text-white font-black fs-5">58</span>
            </div>
            <div class="text-center border-x border-white/5">
                <span class="block text-zinc-500 text-[0.6rem] font-bold uppercase">Intereses</span>
                <span id="stat-hits" class="text-amber-500 font-black fs-5">12</span>
            </div>
            <div class="text-center">
                <span class="block text-zinc-500 text-[0.6rem] font-bold uppercase">Hunted</span>
                <span id="stat-leads" class="text-emerald-500 font-black fs-5">4</span>
            </div>
        </div>
        
        <button onclick="document.getElementById('agent-modal').style.display='none'" class="btn-sci-fi w-full py-4 mt-8 bg-white text-black border-0 fw-black text-[0.75rem]">SALIR DEL REPORTE</button>
    </div>
</div>

{{-- MODAL IA --}}
<div id="ia-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.98); z-index:50000; align-items:center; justify-content:center; backdrop-filter:blur(20px);">
    <div style="width:580px; background:#000; border:3px solid var(--accent-sky); border-radius:50px; padding:4rem; box-shadow:0 0 100px var(--stellar-blue);">
        <h4 class="text-sky-400 font-black uppercase tracking-widest text-center mb-8">IA REPORT DATA</h4>
        <div id="ia-content" class="bg-zinc-900/50 p-10 rounded-[40px] font-mono text-[0.9rem] text-zinc-400 border border-white/5 shadow-inner">
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
    
    function openAgentReport(name, scanned, hits, leads, color) {
        document.getElementById('agent-name').innerText = name + ' Agent';
        document.getElementById('stat-scanned').innerText = scanned;
        document.getElementById('stat-hits').innerText = hits;
        document.getElementById('stat-leads').innerText = leads;
        document.getElementById('agent-icon').className = 'mb-3 fs-1 text-' + color;
        document.getElementById('agent-logs').innerHTML = '<div class="text-zinc-500 animate-pulse">Sincronizando con base de datos...</div>';
        document.getElementById('agent-modal').style.display = 'flex';
        
        // Fetch Bitácora Real via AJAX
        fetch("{{ route('owner.crm.agent-report') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ channel: name })
        })
        .then(r => r.json())
        .then(logs => {
            let logHtml = '';
            if(logs.length === 0) {
                logHtml = '<div class="text-zinc-600 italic">No hay actividad reciente registrada en este canal.</div>';
            } else {
                logs.forEach(l => {
                    logHtml += `<div class="mb-2"><span class="text-zinc-600">[${l.t}]</span> <span class="text-${color}-500">>>></span> <span class="text-zinc-300 font-bold">${l.m}</span></div>`;
                });
            }
            document.getElementById('agent-logs').innerHTML = logHtml;
        });
    }

    function forgetLead(userId) {
        if(!confirm('¿Seguro quieres olvidar este lead? Se archivará fuera del panel principal.')) return;
        fetch("{{ route('owner.crm.archive') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ user_id: userId }) })
        .then(r => r.json()).then(d => { if(d.success) document.getElementById('card-' + userId).remove(); });
    }

    function deleteLead(userId) {
        if(!confirm('¡CUIDADO! Esto borrará el usuario de forma DEFINITIVA. ¿Proceder?')) return;
        fetch("{{ route('owner.crm.delete') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ user_id: userId }) })
        .then(r => r.json()).then(d => { if(d.success) document.getElementById('card-' + userId).remove(); });
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['col-prospecto', 'col-pendiente_pago', 'col-activo'].forEach(id => {
            const el = document.getElementById(id);
            if(el) { new Sortable(el, { group:'kanban', handle:'.card-handle', animation:200, swapThreshold: 0.65, onEnd: function(evt) {
                fetch("{{ route('owner.crm.move') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ user_id: evt.item.getAttribute('data-id'), status: evt.to.getAttribute('data-status') }) });
            }}); }
        });
    });
</script>
@endsection
