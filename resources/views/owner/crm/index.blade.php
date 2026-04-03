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

    .master-layout {
        display: grid;
        grid-template-columns: 1fr 340px; 
        gap: 2.5rem;
        padding: 0 5% 4rem 5%;
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
        border-radius: 14px;
        border-left: 6px solid var(--accent-sky);
        margin-bottom: 2.5rem;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-title { font-size: 0.8rem; font-weight: 950; letter-spacing: 0.35em; text-transform: uppercase; }

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
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .kanban-card:hover { border-color: var(--accent-sky); box-shadow: 0 15px 50px -10px rgba(56, 189, 248, 0.4); transform: translateY(-3px); }

    .card-controls { position: absolute; right: 12px; top: 12px; display: flex; gap: 10px; }
    .btn-control { background: transparent; border: 0; padding: 0; font-size: 0.95rem; opacity: 0.3; color: #fff; transition: 0.2s; }
    .kanban-card:hover .btn-control { opacity: 0.8; }
    .btn-control:hover { transform: scale(1.1); }
    .btn-archive:hover { color: var(--accent-amber); }
    .btn-trash:hover { color: #ef4444; }

    .card-name { font-size: 0.95rem; font-weight: 950; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff; padding-right: 55px; }

    /* STATS HUB CON AVISO DE SIMULACIÓN */
    .stats-hub {
        background: rgba(12, 12, 14, 0.85);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(56, 189, 248, 0.2);
        border-radius: 28px;
        padding: 2rem;
        height: min-content;
        position: sticky;
        top: 2rem;
    }
    .stats-title { font-size: 0.65rem; font-weight: 950; color: var(--accent-sky); letter-spacing: 3px; text-transform: uppercase; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 0.75rem; }
    
    .simulation-badge {
        font-size: 0.5rem;
        font-weight: 950;
        background: rgba(245, 158, 11, 0.1);
        color: var(--accent-amber);
        padding: 4px 10px;
        border-radius: 8px;
        margin-bottom: 2rem;
        border: 1px solid rgba(245, 158, 11, 0.2);
        display: inline-block;
        letter-spacing: 1px;
    }

    .channels-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem; }
    .channel-block { 
        background: rgba(255,255,255,0.02); 
        border: 1px solid rgba(255,255,255,0.05); 
        border-radius: 16px; 
        padding: 1.25rem; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        gap: 0.5rem; 
        transition: 0.3s;
        cursor: pointer;
    }
    .channel-block:hover { background: rgba(56, 189, 248, 0.1); border-color: var(--accent-sky); transform: scale(1.05); }
    .channel-icon { font-size: 1.4rem; }
    .channel-val { font-size: 1.1rem; font-weight: 950; color: #fff; }
    .channel-label { font-size: 0.45rem; font-weight: 800; color: #71717a; text-transform: uppercase; }

    .btn-protocol {
        width: 100%;
        background: #111;
        border: 1px solid rgba(56, 189, 248, 0.2);
        color: var(--accent-sky);
        padding: 12px;
        border-radius: 14px;
        font-size: 0.6rem;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: 0.3s;
    }
    .btn-protocol:hover { background: var(--accent-sky); color: #000; }

</style>

<div class="header-hub">
    <h1 class="text-white text-3xl font-black uppercase tracking-[0.6em]">Command Hub</h1>
    <div class="h-px bg-zinc-800 flex-1 opacity-20"></div>
    <div class="flex items-center gap-6 text-sky-400 font-black text-xs uppercase tracking-[0.3em] animate-pulse">
        <i class="bi bi-robot fs-2 text-sky-400"></i> AGENTE SOCIAL LIVE
    </div>
</div>

<div class="master-layout">
    <div class="crm-container custom-scrollbar">
        @foreach([['total' => $prospectos, 'st' => 'prospecto', 't' => 'Fase 01 | Leads'], ['total' => $pendientes, 'st' => 'pendiente_pago', 't' => 'Fase 02 | Validar'], ['total' => $activos, 'st' => 'activo', 't' => 'Fase 03 | Activos']] as $c)
            <div class="kanban-col">
                <div class="col-header" style="{{ $c['st'] == 'activo' ? 'border-left-color:var(--accent-emerald)' : ($c['st'] == 'pendiente_pago' ? 'border-left-color:var(--accent-amber)' : '') }}">
                    <span class="header-title {{ $c['st'] == 'activo' ? 'text-emerald-500' : ($c['st'] == 'pendiente_pago' ? 'text-amber-500' : '') }}">{{ $c['t'] }}</span>
                    <span class="text-white/40 text-xs font-black">{{ $c['total']->total() }}</span>
                </div>
                <div id="col-{{ $c['st'] }}" class="kanban-list" data-status="{{ $c['st'] }}">
                    @foreach($c['total'] as $u)
                    <div class="kanban-card" data-id="{{ $u->id }}" id="card-{{ $u->id }}">
                        <div class="card-controls">
                            @if($c['st'] == 'prospecto')
                                <button type="button" class="btn-control btn-archive" title="Olvidar" onclick="archiveLead('{{ $u->id }}')"><i class="bi bi-archive-fill"></i></button>
                                <button type="button" class="btn-control btn-trash" title="Borrar" onclick="deleteLead('{{ $u->id }}')"><i class="bi bi-trash3-fill"></i></button>
                            @endif
                            <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                        </div>
                        <div>
                            <div class="card-name">{{ $u->name }}</div>
                            <div class="text-[0.65rem] text-zinc-600 fw-black uppercase">{{ $c['st'] == 'activo' ? ($u->empresa?->nombre_comercial ?? 'Setup OK') : ($u->lead_source ?? 'Landing Directo') }}</div>
                        </div>
                        <div class="flex gap-2 mt-4">
                            @if($c['st'] == 'prospecto')
                                <button onclick="openIA('{{ $u->id }}', '{{ $u->name }}')" class="btn-sci-fi w-full"><i class="bi bi-robot"></i> IA DATA</button>
                            @elseif($c['st'] == 'pendiente_pago')
                                <form action="{{ route('owner.crm.activate', $u->id) }}" method="POST" class="w-full">@csrf<button type="submit" class="btn-sci-fi w-full text-amber-500"><i class="bi bi-lightning-fill"></i> ACTIVAR</button></form>
                            @else
                                <button class="btn-sci-fi w-full text-emerald-400"><i class="bi bi-gear-fill"></i> PANEL</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- STATS HUB -->
    <div class="stats-hub">
        <div class="flex justify-between items-center stats-title">
            <span>Social Live Ops</span>
            <div class="stat-pulse"></div>
        </div>
        
        <div class="simulation-badge"><i class="bi bi-eye-fill me-2"></i> MODO SIMULACIÓN ACTIVA</div>

        <div class="channels-grid">
            <div class="channel-block" onclick="openChannelActivity('LinkedIn')">
                <i class="bi bi-linkedin channel-icon text-sky-500"></i>
                <div class="channel-val">58</div>
                <div class="channel-label">LinkedIn</div>
            </div>
            <div class="channel-block" onclick="openChannelActivity('Instagram')">
                <i class="bi bi-instagram channel-icon text-pink-500"></i>
                <div class="channel-val">42</div>
                <div class="channel-label">Instagram</div>
            </div>
            <div class="channel-block" onclick="openChannelActivity('Facebook')">
                <i class="bi bi-facebook channel-icon text-blue-600"></i>
                <div class="channel-val">31</div>
                <div class="channel-label">Facebook</div>
            </div>
            <div class="channel-block" onclick="openChannelActivity('WhatsApp/Tg')">
                <i class="bi bi-chat-dots-fill channel-icon text-emerald-500"></i>
                <div class="channel-val">11</div>
                <div class="channel-label">Cloud</div>
            </div>
        </div>
        <button onclick="openProtocol()" class="btn-protocol"><i class="bi bi-shield-lock-fill me-2"></i> Protocolo Técnico</button>
    </div>
</div>

{{-- MODALES SE MANTIENEN IGUAL --}}
<div id="protocol-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.95); z-index:10002; align-items:center; justify-content:center; backdrop-filter:blur(15px);">
    <div style="width:700px; background:#000; border:1px solid var(--accent-sky); border-radius:40px; padding:4rem;">
        <h2 class="text-white text-xl font-black uppercase mb-10 tracking-[0.3em]">Protocolo Técnico Agent</h2>
        <div class="text-zinc-400 text-sm leading-relaxed mb-10">
            Este módulo integra la infraestructura de números maestros y rotación de APIs locales para garantizar el contacto sin bloqueos. Una vez finalizada la etapa de diseño, se conectará el motor de scraping real a estos indicadores.
        </div>
        <button onclick="document.getElementById('protocol-overlay').style.display='none'" class="btn-sci-fi w-full py-4 bg-sky-500 text-black border-0 fw-black">ENTENDIDO</button>
    </div>
</div>

<div id="activity-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.95); z-index:10001; align-items:center; justify-content:center; backdrop-filter:blur(15px);">
    <div style="width:600px; background:#09090b; border:1px solid var(--accent-sky); border-radius:32px; padding:3rem;">
        <h4 class="text-sky-400 font-black tracking-widest uppercase mb-6">Log operativo: <span id="ch-name"></span></h4>
        <div id="log-list" class="bg-black p-6 rounded-2xl h-[300px] overflow-y-auto border border-white/5 font-mono text-[0.75rem]"></div>
        <button onclick="document.getElementById('activity-overlay').style.display='none'" class="btn-sci-fi w-full mt-8 py-4 bg-sky-500 text-black border-0 fw-black">SALIR</button>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openProtocol() { document.getElementById('protocol-overlay').style.display='flex'; }
    function openChannelActivity(ch) { 
        document.getElementById('ch-name').innerText = ch;
        const list = document.getElementById('log-list');
        list.innerHTML = `<div class='text-zinc-600 mb-2'>[SIMULACIÓN] Conectando con API de ${ch}...</div>`;
        list.innerHTML += `<div class='mb-2 text-white'>[10:42 AM] Prospecto 'Casa Lopez' detectado.</div>`;
        list.innerHTML += `<div class='mb-2 text-white'>[11:15 AM] Mensaje enviado desde pool local.</div>`;
        document.getElementById('activity-overlay').style.display='flex';
    }
</script>
@endsection
