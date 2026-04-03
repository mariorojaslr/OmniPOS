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
        gap: 2rem;
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

    .header-title { font-size: 0.8rem; font-weight: 950; letter-spacing: 0.25em; text-transform: uppercase; }

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
        transition: 0.3s;
    }
    .kanban-card:hover { border-color: var(--accent-sky); box-shadow: 0 15px 40px -10px rgba(56, 189, 248, 0.4); transform: translateY(-3px); }

    .card-controls { position: absolute; right: 12px; top: 10px; display: flex; gap: 10px; }
    .btn-control { background: transparent; border: 0; padding: 0; font-size: 0.95rem; opacity: 0.2; color: #fff; transition: 0.2s; }
    .kanban-card:hover .btn-control { opacity: 0.6; }
    .btn-control:hover { opacity: 1 !important; transform: scale(1.1); }
    .btn-archive:hover { color: var(--accent-amber); }
    .btn-trash:hover { color: #ef4444; }

    .card-name { font-size: 0.95rem; font-weight: 950; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff; padding-right: 55px; }

    /* STATS HUB CON BOTÓN DE PROTOCOLO */
    .stats-hub {
        background: rgba(12, 12, 14, 0.85);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(56, 189, 248, 0.2);
        border-radius: 28px;
        padding: 2rem;
        height: min-content;
        position: sticky;
        top: 2rem;
    }
    .stats-title { font-size: 0.65rem; font-weight: 950; color: var(--accent-sky); letter-spacing: 3px; text-transform: uppercase; margin-bottom: 2rem; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 0.75rem; }
    
    .channels-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem; }
    .channel-block { 
        background: rgba(255,255,255,0.02); 
        border: 1px solid rgba(255,255,255,0.05); 
        border-radius: 16px; 
        padding: 1rem; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        gap: 0.5rem; 
        transition: 0.3s;
        cursor: pointer;
    }
    .channel-block:hover { background: rgba(56, 189, 248, 0.1); border-color: var(--accent-sky); transform: scale(1.05); }
    .channel-icon { font-size: 1.3rem; }
    .channel-val { font-size: 1.1rem; font-weight: 950; color: #fff; }
    .channel-label { font-size: 0.45rem; font-weight: 800; color: #71717a; text-transform: uppercase; }

    .btn-protocol {
        width: 100%;
        background: #111;
        border: 1px solid rgba(56, 189, 248, 0.3);
        color: var(--accent-sky);
        padding: 12px;
        border-radius: 14px;
        font-size: 0.65rem;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-protocol:hover { background: var(--accent-sky); color: #000; box-shadow: 0 0 20px rgba(56, 189, 248, 0.4); }

    /* MODAL DE PROTOCOLO TÉCNICO */
    #protocol-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.97); z-index: 10002;
        display: none; align-items: center; justify-content: center; backdrop-filter: blur(15px);
    }
    .protocol-card {
        width: 750px; background: #000; border: 1px solid var(--accent-sky); border-radius: 40px; padding: 4rem;
        box-shadow: 0 0 200px rgba(56, 189, 248, 0.2);
    }
    .protocol-step { margin-bottom: 2rem; border-left: 2px solid var(--accent-sky); padding-left: 2rem; }
    .step-num { font-size: 0.6rem; color: var(--accent-sky); font-weight: 950; text-transform: uppercase; display: block; margin-bottom: 0.5rem; }
    .step-title { font-size: 1.1rem; font-weight: 950; color: #fff; margin-bottom: 0.5rem; text-transform: uppercase; }
    .step-desc { font-size: 0.85rem; color: #71717a; line-height: 1.6; }

    /* TERMINAL DE ACTIVIDAD */
    #activity-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.95); z-index: 10001; 
        display: none; align-items: center; justify-content: center; backdrop-filter: blur(10px);
    }
    .activity-log { width: 650px; background: #09090b; border: 2px solid var(--accent-sky); border-radius: 32px; padding: 3rem; }
</style>

<div class="header-hub">
    <h1 class="text-white text-3xl font-black uppercase tracking-[0.6em]">Command Hub</h1>
    <div class="h-px bg-zinc-800 flex-1 opacity-20"></div>
    <div class="flex items-center gap-6 text-sky-400 font-black text-xs uppercase tracking-[0.3em] animate-pulse">
        <i class="bi bi-robot fs-2"></i> AGENTE SOCIAL LIVE
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
                                <button type="button" class="btn-control btn-archive" onclick="archiveLead('{{ $u->id }}')"><i class="bi bi-archive-fill"></i></button>
                                <button type="button" class="btn-control btn-trash" onclick="deleteLead('{{ $u->id }}')"><i class="bi bi-trash3-fill"></i></button>
                            @endif
                            <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                        </div>
                        <div>
                            <div class="card-name">{{ $u->name }}</div>
                            <div class="text-[0.65rem] text-zinc-600 fw-black uppercase">{{ $c['st'] == 'activo' ? ($u->empresa?->nombre_comercial ?? 'Setup OK') : ($u->lead_source ?? 'Landing Directo') }}</div>
                        </div>
                        <div class="flex gap-2 mt-4">
                            @if($c['st'] == 'prospecto')
                                <button onclick="openIA('{{ $u->id }}', '{{ $u->name }}')" class="btn-sci-fi w-full"><i class="bi bi-robot"></i> IA</button>
                            @elseif($c['st'] == 'pendiente_pago')
                                <form action="{{ route('owner.crm.activate', $u->id) }}" method="POST" class="w-full">@csrf<button type="submit" class="btn-sci-fi w-full text-amber-500"><i class="bi bi-lightning-fill"></i> ACT</button></form>
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
        <button onclick="openProtocol()" class="btn-protocol"><i class="bi bi-shield-lock-fill"></i> Protocolo Técnico</button>
    </div>
</div>

{{-- MODAL PROTOCOLO --}}
<div id="protocol-overlay">
    <div class="protocol-card">
        <div class="flex justify-between items-center mb-16">
            <h2 class="text-white text-2xl font-black uppercase tracking-[0.4em]">Mecánica del Agente</h2>
            <button onclick="closeProtocol()" class="bg-transparent border-0 text-white/20 hover:text-white"><i class="bi bi-x-lg fs-3"></i></button>
        </div>
        
        <div class="protocol-step">
            <span class="step-num">Paso 01 | El Origen</span>
            <h3 class="step-title">Pool de Números Maestros</h3>
            <p class="step-desc">El sistema opera mediante una API Cloud con rotación de números certificados. No usamos chips físicos; usamos una infraestructura virtual que garantiza disponibilidad 24/7 sin bloqueos.</p>
        </div>

        <div class="protocol-step">
            <span class="step-num">Paso 02 | El Contacto</span>
            <h3 class="step-title">Mensajería Predictiva</h3>
            <p class="step-desc">El robot no envía spam masivo. Inicia conversaciones personalizadas basadas en el rubro del prospecto, actuando como un asistente comercial inteligente de marca blanca.</p>
        </div>

        <div class="protocol-step">
            <span class="step-num">Paso 03 | La Entrega</span>
            <h3 class="step-title">Inyección Automática</h3>
            <p class="step-desc">Una vez que el prospecto responde positivamente, la IA lo inyecta directamente en tu Fase 01, con todo el historial de la conversación listo para que cierres la venta.</p>
        </div>

        <button onclick="closeProtocol()" class="btn-sci-fi w-full py-5 bg-sky-500 text-black border-0 fw-black mt-10">ENTENDIDO</button>
    </div>
</div>

{{-- MODAL ACTIVIDAD --}}
<div id="activity-overlay">
    <div class="activity-log">
        <div class="flex justify-between items-center mb-8">
            <h4 class="text-sky-400 font-black m-0 uppercase tracking-widest">Actividad: <span id="log-channel-name"></span></h4>
            <button onclick="closeActivity()" class="bg-transparent border-0 text-white/20 hover:text-white"><i class="bi bi-x-lg fs-4"></i></button>
        </div>
        <div id="log-feed" class="bg-black p-6 rounded-2xl font-monospace text-[0.75rem] h-[300px] overflow-y-auto border border-white/5">
            <!-- Feed JS -->
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openProtocol() { document.getElementById('protocol-overlay').style.display = 'flex'; document.body.style.overflow = 'hidden'; }
    function closeProtocol() { document.getElementById('protocol-overlay').style.display = 'none'; document.body.style.overflow = 'auto'; }
    
    function openChannelActivity(ch) {
        document.getElementById('log-channel-name').innerText = ch;
        const feed = document.getElementById('log-feed');
        feed.innerHTML = `<div class='text-zinc-600 mb-2'>[09:12] Sincronizando con ${ch} Master Pool...</div>`;
        ['Pizza Express', 'Gimnasio Zeus', 'Tienda Mia'].forEach((n,i) => {
            feed.innerHTML += `<div class='mb-2'><span class='text-sky-400'>[10:${i}2 AM]</span> <span class='text-emerald-400'>${n}</span>: Intención detectada.</div>`;
        });
        document.getElementById('activity-overlay').style.display = 'flex';
    }
    function closeActivity() { document.getElementById('activity-overlay').style.display = 'none'; }

    // (Otras funciones de Borrar/Olvidar/IA se mantienen iguales para el flujo técnico)
    function archiveLead(id) { fetch("{{ route('owner.crm.archive') }}", { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{csrf_token()}}'}, body:JSON.stringify({user_id:id}) }).then(()=>document.getElementById('card-'+id).remove()); }
    function deleteLead(id) { fetch("{{ route('owner.crm.delete') }}", { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{csrf_token()}}'}, body:JSON.stringify({user_id:id}) }).then(()=>document.getElementById('card-'+id).remove()); }
</script>
@endsection
