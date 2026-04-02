@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #0c0c0e;
        --border-color: rgba(255, 255, 255, 1);
        --accent-indigo: #6366f1;
        --accent-amber: #f59e0b;
        --accent-emerald: #10b981;
        --accent-sky: #38bdf8;
        --stellar-blue: rgba(30, 58, 138, 0.7);
    }

    body { background-color: var(--oled-bg) !important; color: #fff; overflow-x: hidden; }

    .crm-container {
        display: flex;
        width: 100vw;
        padding: 2rem;
        gap: 0.5rem;
        height: 85vh;
        overflow-x: auto;
    }

    .kanban-col {
        width: 300px;
        flex-shrink: 0;
        background: transparent;
        border-right: 1px dashed rgba(255,255,255,0.06);
        padding: 0 1rem;
        display: flex;
        flex-direction: column;
    }

    .col-header {
        background: linear-gradient(90deg, var(--stellar-blue) 0%, transparent 100%);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        border-left: 5px solid var(--accent-sky);
        margin-bottom: 2.5rem; /* MAS ESPACIO PARA EL HEADER */
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 15px 30px rgba(0,0,0,0.6);
    }

    .header-title {
        font-size: 0.7rem;
        font-weight: 900;
        letter-spacing: 0.3em;
        text-transform: uppercase;
        color: #fff;
    }

    /* TARJETA UNIFORME - CON MAS AIRE VERTICAL */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color); 
        border-radius: 12px;
        padding: 0.85rem;
        margin-bottom: 1.5rem; /* ESPACIADO INCREMENTADO 33% MAS */
        margin-left: 10px;
        position: relative;
        height: 110px; /* UN POQUITO MAS DE ALTURA PARA EL TEXTO INTERNO */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        cursor: grab;
        box-shadow: 0 20px 40px -10px var(--stellar-blue);
    }

    .kanban-card:hover {
        transform: translateY(-5px);
        border-color: var(--accent-sky);
        box-shadow: 0 40px 80px -10px rgba(56, 189, 248, 0.4);
        z-index: 50;
    }

    .card-tab {
        position: absolute;
        left: -10px; 
        top: 0;
        bottom: 0;
        width: 10px;
        border-radius: 8px 0 0 8px;
        box-shadow: -4px 0 15px rgba(0,0,0,0.4);
    }
    .tab-prospecto { background: var(--accent-indigo); }
    .tab-pago { background: var(--accent-amber); }
    .tab-activo { background: var(--accent-emerald); }

    .card-handle {
        position: absolute;
        right: 10px;
        top: 10px;
        color: rgba(255,255,255,0.05);
        font-size: 1rem;
    }

    .card-name { 
        font-size: 0.85rem; 
        font-weight: 900; 
        color: #fff; 
        text-transform: uppercase; 
        letter-spacing: 0.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-right: 15px;
    }

    .card-subtext {
        font-size: 0.6rem;
        color: #71717a;
        font-weight: 600;
        margin-top: -3px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-group-master {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .btn-sci-fi {
        flex: 1;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.1);
        color: var(--accent-sky);
        padding: 5px 0;
        border-radius: 6px;
        font-size: 0.5rem;
        font-weight: 950;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        transition: all 0.2s;
    }

    .btn-sci-fi:hover { background: var(--accent-sky); color: #000; border-color: var(--accent-sky); }
    .btn-amber { color: var(--accent-amber); border-color: rgba(245, 158, 11, 0.2); }
    .btn-amber:hover { background: var(--accent-amber); }

    /* TEXTOS EN ESPAÑOL DENTRO DE LA TARJETA */
    .card-status-label {
        font-size: 0.5rem;
        font-weight: 800;
        color: var(--accent-sky);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-top: 6px;
        padding-top: 4px;
        border-top: 1px solid rgba(255,255,255,0.02);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sortable-ghost { opacity: 0; }
    .sortable-chosen { 
        background: #000 !important;
        box-shadow: 0 40px 100px var(--stellar-blue) !important; 
    }
</style>

<div class="px-10 py-4">
    <div class="flex items-center gap-6">
        <h1 class="text-white text-xl font-black uppercase tracking-[0.5em]">Consola de Mando</h1>
        <div class="h-px bg-zinc-800 flex-1"></div>
        <div class="flex items-center gap-4 text-sky-400 font-black text-[0.6rem] uppercase tracking-widest animate-pulse">
            <i class="bi bi-cpu"></i> IA Monitoreando...
        </div>
    </div>
</div>

<div class="crm-container custom-scrollbar">
    
    <!-- COLUMNA 1: PROSPECTOS -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title">01 Prospectos</span>
            <span class="text-white/40 text-[9px] font-black">{{ $prospectos->total() }}</span>
        </div>
        
        <div id="col-prospecto" class="kanban-list" data-status="prospecto" style="min-height: 500px">
            @foreach($prospectos as $pro)
            <div class="kanban-card" data-id="{{ $pro->id }}">
                <div class="card-tab tab-prospecto"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div>
                    <div class="card-name">{{ $pro->name }}</div>
                    <div class="card-subtext">{{ $pro->lead_source ?? 'Landing Directo' }}</div>
                </div>
                
                <div class="btn-group-master">
                    <button class="btn-sci-fi w-full"><i class="bi bi-robot"></i> INFO AGENTE</button>
                </div>

                <div class="card-status-label">
                    <span>PROSPECTO OK</span>
                    <i class="bi bi-person-plus-fill"></i>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $prospectos->links() }}</div>
    </div>

    <!-- COLUMNA 2: VALIDAR PAGO -->
    <div class="kanban-col">
        <div class="col-header" style="border-left-color: var(--accent-amber)">
            <span class="header-title text-amber-500">02 Validar</span>
            <span class="text-amber-500/20 text-[9px] font-black">{{ $pendientes->total() }}</span>
        </div>
        
        <div id="col-pendiente_pago" class="kanban-list" data-status="pendiente_pago" style="min-height: 500px">
            @foreach($pendientes as $pen)
            <div class="kanban-card" data-id="{{ $pen->id }}">
                <div class="card-tab tab-pago"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div>
                    <div class="card-name">{{ $pen->name }}</div>
                    <div class="card-subtext text-amber-500/50">Pago en Proceso</div>
                </div>
                
                <div class="btn-group-master">
                    <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-sci-fi">VOUCHER</a>
                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="btn-sci-fi btn-amber w-full">ACTIVAR</button>
                    </form>
                </div>

                <div class="card-status-label" style="color: var(--accent-amber)">
                    <span>VALIDAR PAGO</span>
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $pendientes->links() }}</div>
    </div>

    <!-- COLUMNA 3: ACTIVOS -->
    <div class="kanban-col">
        <div class="col-header" style="border-left-color: var(--accent-emerald)">
            <span class="header-title text-emerald-500">03 Activos</span>
            <span class="text-emerald-500/20 text-[9px] font-black">{{ $activos->total() }}</span>
        </div>
        
        <div id="col-activo" class="kanban-list" data-status="activo" style="min-height: 500px">
            @foreach($activos as $act)
            <div class="kanban-card" data-id="{{ $act->id }}">
                <div class="card-tab tab-activo"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div>
                    <div class="card-name text-zinc-300">{{ $act->name }}</div>
                    <div class="card-subtext truncate">{{ $act->empresa?->nombre_comercial ?? 'Consola Activa' }}</div>
                </div>

                <div class="btn-group-master">
                    <button class="btn-sci-fi w-full">PANEL DE EMPRESA</button>
                </div>

                <div class="card-status-label" style="color: var(--accent-emerald)">
                    <span>SAAS ACTIVO OK</span>
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $activos->links() }}</div>
    </div>

    {{-- COLUMNA PROACTIVA --}}
    <div class="kanban-col opacity-20 flex items-center justify-center">
        <div class="text-center py-20 border-2 border-dashed border-zinc-900 rounded-3xl w-full">
             <i class="bi bi-robot fs-1 d-block mb-3 text-sky-500"></i>
             <p class="text-[0.4rem] font-black uppercase tracking-[0.4em] text-zinc-600">IA Social Scanner</p>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columns = ['col-prospecto', 'col-pendiente_pago', 'col-activo'];
        
        columns.forEach(id => {
            const el = document.getElementById(id);
            new Sortable(el, {
                group: 'kanban',
                handle: '.card-handle',
                animation: 250,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function(evt) {
                    const userId = evt.item.getAttribute('data-id');
                    const newStatus = evt.to.getAttribute('data-status');
                    if (evt.from !== evt.to) {
                        moveUser(userId, newStatus);
                    }
                }
            });
        });

        function moveUser(userId, newStatus) {
            fetch("{{ route('owner.crm.move') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ user_id: userId, status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    location.reload();
                }
            });
        }
    });
</script>
@endsection
