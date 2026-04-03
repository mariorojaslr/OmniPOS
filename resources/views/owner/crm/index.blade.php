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
        grid-template-columns: 1fr 360px; 
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
    .card-subtext { font-size: 0.7rem; color: #52525b; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
    .btn-sci-fi { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: var(--accent-sky); padding: 7px 0; border-radius: 10px; font-size: 0.6rem; font-weight: 950; display: flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; text-transform: uppercase; }
    .btn-sci-fi:hover:not(.disabled) { background: var(--accent-sky); color: #000; }
    .btn-sci-fi.disabled { opacity: 0.1; }

    /* STATS HUB CON INTERACCIÓN */
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
    
    .channels-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .channel-block { 
        background: rgba(255,255,255,0.02); 
        border: 1px solid rgba(255,255,255,0.05); 
        border-radius: 16px; 
        padding: 1.25rem; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        gap: 0.75rem; 
        transition: 0.3s;
        cursor: pointer;
    }
    .channel-block:hover { background: rgba(56, 189, 248, 0.1); border-color: var(--accent-sky); transform: scale(1.05); }
    .channel-icon { font-size: 1.5rem; }
    .channel-val { font-size: 1.1rem; font-weight: 950; color: #fff; }
    .channel-label { font-size: 0.5rem; font-weight: 800; color: #71717a; text-transform: uppercase; }

    /* TERMINAL DE ACTIVIDAD (OVERLAY) */
    #activity-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.95); z-index: 10001; 
        display: none; align-items: center; justify-content: center; backdrop-filter: blur(10px);
    }
    .activity-log {
        width: 650px; background: #09090b; border: 2px solid var(--accent-sky); border-radius: 32px; padding: 3rem;
        box-shadow: 0 0 150px rgba(56, 189, 248, 0.3);
    }
    .log-container {
        height: 400px; overflow-y: auto; background: #000; border-radius: 20px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.05);
        font-family: 'Courier New', Courier, monospace; font-size: 0.75rem;
    }
    .log-entry { margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px dashed rgba(255,255,255,0.03); }
    .log-time { color: var(--accent-sky); margin-right: 1rem; font-weight: bold; }
    .log-user { color: var(--accent-emerald); font-weight: 900; margin-right: 0.5rem; }
    .log-msg { color: #ccc; }

    .stat-pulse { width: 10px; height: 10px; background: var(--accent-emerald); border-radius: 50%; box-shadow: 0 0 12px var(--accent-emerald); animation: pulse 1.5s infinite; }
    @keyframes pulse { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(1.3); } 100% { opacity: 1; transform: scale(1); } }
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
        @php
            $cols = [
                ['id' => 'col-prospecto', 'title' => 'Fase 01 | Leads', 'status' => 'prospecto', 'data' => $prospectos],
                ['id' => 'col-pendiente_pago', 'title' => 'Fase 02 | Validar', 'status' => 'pendiente_pago', 'data' => $pendientes],
                ['id' => 'col-activo', 'title' => 'Fase 03 | Activos', 'status' => 'activo', 'data' => $activos]
            ];
        @endphp

        @foreach($cols as $col)
        <div class="kanban-col">
            <div class="col-header" style="{{ $col['status'] !== 'prospecto' ? 'border-left-color: var(--accent-'.($col['status'] == 'activo' ? 'emerald' : 'amber').')' : '' }}">
                <span class="header-title {{ $col['status'] == 'activo' ? 'text-emerald-500' : ($col['status'] == 'pendiente_pago' ? 'text-amber-500' : '') }}">
                    {{ $col['title'] }}
                </span>
                <span class="text-white/40 text-xs font-black">{{ $col['data']->total() }}</span>
            </div>
            
            <div id="{{ $col['id'] }}" class="kanban-list" data-status="{{ $col['status'] }}">
                @foreach($col['data'] as $user)
                <div class="kanban-card" data-id="{{ $user->id }}" id="card-{{ $user->id }}">
                    <div class="card-controls">
                        @if($col['status'] == 'prospecto')
                            <button type="button" class="btn-control btn-archive" onclick="archiveLead('{{ $user->id }}')"><i class="bi bi-archive-fill"></i></button>
                            <button type="button" class="btn-control btn-trash" onclick="deleteLead('{{ $user->id }}')"><i class="bi bi-trash3-fill"></i></button>
                        @endif
                        <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                    </div>
                    <div>
                        <div class="card-name">{{ $user->name }}</div>
                        <div class="card-subtext truncate">{{ ($col['status'] == 'activo') ? ($user->empresa?->nombre_comercial ?? 'SaaS OK') : ($user->lead_source ?? 'Landing Directo') }}</div>
                    </div>
                    <div class="btn-group-card">
                        @if($col['status'] == 'prospecto')
                            <button type="button" class="btn-sci-fi" onclick="openIA('{{ $user->id }}', '{{ $user->name }}', '{{ $user->lead_source ?? 'META ADS' }}')"><i class="bi bi-robot"></i> IA DATA</button>
                            <button type="button" class="btn-sci-fi disabled"><i class="bi bi-envelope"></i> MAIL</button>
                        @elseif($col['status'] == 'pendiente_pago')
                            @if($user->payment_voucher)
                                <a href="{{ asset('storage/' . $user->payment_voucher) }}" target="_blank" class="btn-sci-fi" style="color:var(--accent-amber)"><i class="bi bi-file-earmark-pdf"></i> DOC</a>
                            @else
                                <span class="btn-sci-fi disabled">NO DOC</span>
                            @endif
                            <form action="{{ route('owner.crm.activate', $user->id) }}" method="POST" class="m-0 p-0 d-grid">@csrf<button type="submit" class="btn-sci-fi" style="color:var(--accent-amber);border-color:rgba(245,158,11,0.2);"><i class="bi bi-lightning-charge-fill"></i> ACT</button></form>
                        @else
                            <button class="btn-sci-fi" style="color:var(--accent-emerald);border-color:rgba(16,185,129,0.2);"><i class="bi bi-gear-fill"></i> PANEL</button>
                            <button class="btn-sci-fi disabled"><i class="bi bi-bar-chart-fill"></i> STATS</button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $col['data']->links() }}</div>
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
                <div class="channel-label">Cloud Msgr</div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL IA INDIVIDUAL --}}
<div id="ia-overlay">
    <div class="report-card">
        <div class="flex justify-between items-center mb-8">
            <h4 class="text-sky-400 font-black m-0 tracking-widest"><i class="bi bi-robot me-2"></i> Reporte IA</h4>
            <button onclick="closeIA()" class="bg-transparent border-0 text-white/20 hover:text-white"><i class="bi bi-x-lg fs-4"></i></button>
        </div>
        <div class="text-zinc-300 font-monospace text-[0.85rem]">
            <div class="mb-6 border-b border-white/5 pb-4">>>> ANALIZANDO: <span id="ia-name-display" class="text-white"></span></div>
            <div class="bg-black/30 p-6 rounded-3xl border border-white/5 shadow-inner mb-8">
                <div class="mb-2"><span class="text-zinc-600">CANAL:</span> <span id="ia-source-display" class="text-emerald-400"></span></div>
                <div class="text-zinc-400">"Interés activo en POS móvil. El Agente sugirió la Master Suite MultiPOS."</div>
            </div>
            <button onclick="closeIA()" class="btn-sci-fi w-full py-4 bg-sky-500 text-black border-0 fw-black">VOLVER</button>
        </div>
    </div>
</div>

{{-- MODAL ACTIVIDAD CANAL (EL FEED) --}}
<div id="activity-overlay">
    <div class="activity-log">
        <div class="flex justify-between items-center mb-8">
            <h4 class="text-sky-400 font-black m-0 tracking-widest uppercase"><i class="bi bi-activity me-2"></i> Log de Actividad: <span id="log-channel-name"></span></h4>
            <button onclick="closeActivity()" class="bg-transparent border-0 text-white/20 hover:text-white"><i class="bi bi-x-lg fs-4"></i></button>
        </div>
        <div class="log-container custom-scrollbar" id="log-feed">
            <!-- SE LLENA POR JS -->
        </div>
        <div class="mt-8">
            <button onclick="closeActivity()" class="btn-sci-fi w-full py-4 bg-sky-500 text-black border-0 fw-black">SALIR DEL TERMINAL</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    let activeUser = null;

    function openIA(id, name, source) {
        activeUser = id;
        document.getElementById('card-'+id).classList.add('active-spotlight');
        document.getElementById('ia-name-display').innerText = name;
        document.getElementById('ia-source-display').innerText = source;
        document.getElementById('ia-overlay').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeIA() {
        document.getElementById('ia-overlay').style.display = 'none';
        if(activeUser) document.getElementById('card-'+activeUser).classList.remove('active-spotlight');
        document.body.style.overflow = 'auto';
    }

    function openChannelActivity(channel) {
        document.getElementById('log-channel-name').innerText = channel;
        const feed = document.getElementById('log-feed');
        feed.innerHTML = '';
        
        // Mock de actividad para que Mario vea el sistema moviéndose
        const activities = [
            { t: '10:45 AM', u: 'Distribuidora Lujan', m: 'Escaneo de perfil completado.' },
            { t: '11:12 AM', u: 'Shop-Express BA', m: 'Respuesta detectada: "¿Tienen demo?"' },
            { t: '12:05 PM', u: 'Cafe del Sol', m: 'Invitación enviada vía '+channel },
            { t: '02:30 PM', u: 'Mario Gomez (Retail)', m: 'Nivel de interés: ALTO.' },
            { t: '03:15 PM', u: 'Kiosco Central', m: 'Convertido a Lead Fase 01.' }
        ];

        activities.forEach(a => {
            feed.innerHTML += `<div class="log-entry">
                <span class="log-time">[${a.t}]</span>
                <span class="log-user">${a.u}</span>
                <span class="log-msg">> ${a.m}</span>
            </div>`;
        });

        document.getElementById('activity-overlay').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeActivity() {
        document.getElementById('activity-overlay').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function archiveLead(id) {
        if(confirm('¿Olvidar lead?')) {
            fetch("{{ route('owner.crm.archive') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ user_id: id }) })
            .then(() => document.getElementById('card-'+id).remove());
        }
    }

    function deleteLead(id) {
        if(confirm('¿Borrar permanentemente?')) {
            fetch("{{ route('owner.crm.delete') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ user_id: id }) })
            .then(() => document.getElementById('card-'+id).remove());
        }
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
</script>
@endsection
