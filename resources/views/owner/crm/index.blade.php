@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #09090b;
        --border-color: rgba(255, 255, 255, 0.4);
        --accent-indigo: #6366f1;
        --accent-amber: #f59e0b;
        --accent-emerald: #10b981;
        --accent-sky: #38bdf8;
        --stellar-blue: rgba(30, 58, 138, 0.8);
    }

    body { background-color: var(--oled-bg) !important; color: #fff; overflow-x: hidden; }

    .crm-container {
        display: flex;
        width: 100vw;
        padding: 1.5rem;
        gap: 0.75rem; /* Espaciado ultra compacto */
        height: 90vh;
        overflow-x: auto;
    }

    /* COLUMNA SCI-FI */
    .kanban-col {
        width: 280px;
        flex-shrink: 0;
        background: rgba(255,255,255, 0.01);
        border-right: 1px dashed rgba(255,255,255,0.08); /* Separación visual clara */
        padding-right: 0.75rem;
        display: flex;
        flex-direction: column;
    }

    .kanban-col:last-child { border-right: 0; }

    /* CABECERA ESTELAR */
    .col-header {
        background: linear-gradient(90deg, var(--stellar-blue) 0%, transparent 100%);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border-left: 4px solid var(--accent-sky);
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 20px rgba(0,0,0,0.5);
    }

    .header-title {
        font-size: 0.65rem;
        font-weight: 900;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: #fff;
    }

    /* TARJETA COMPACTA CYBERNETIC */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid rgba(255,255,255,1); /* Borde 1px blanco sólido */
        border-radius: 10px;
        padding: 0.5rem 0.75rem;
        margin-bottom: 0.6rem; /* Espacio reducido entre tarjetas */
        margin-left: 8px;
        position: relative;
        transition: all 0.3s cubic-bezier(0.2, 1, 0.3, 1);
        cursor: grab;
        box-shadow: 0 10px 25px -5px rgba(30, 58, 138, 0.6);
    }

    .kanban-card:hover {
        transform: translateY(-4px) scale(1.02);
        border-color: var(--accent-sky);
        box-shadow: 0 20px 40px -10px var(--accent-sky);
        z-index: 50;
    }

    /* PESTAÑA POST-IT MAGNÉTICA */
    .card-tab {
        position: absolute;
        left: -8px; 
        top: 20%;
        bottom: 20%;
        width: 8px;
        border-radius: 4px 0 0 4px;
        box-shadow: -4px 0 10px rgba(0,0,0,0.5);
    }
    .tab-prospecto { background: var(--accent-indigo); }
    .tab-pago { background: var(--accent-amber); }
    .tab-activo { background: var(--accent-emerald); }

    .card-handle {
        position: absolute;
        right: 6px;
        top: 6px;
        color: rgba(255,255,255,0.05);
        font-size: 0.9rem;
    }

    .card-name { 
        font-size: 0.7rem; 
        font-weight: 900; 
        color: #fff; 
        text-transform: uppercase; 
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-right: 15px;
    }

    .card-meta { 
        font-size: 0.5rem; 
        font-weight: 800; 
        color: var(--accent-sky); 
        display: flex; 
        justify-content: space-between;
        align-items: center;
        text-transform: uppercase;
        margin-top: 2px;
    }

    /* BOTONES AI NEÓN */
    .btn-ai-sm {
        background: rgba(56, 189, 248, 0.1);
        border: 1px solid rgba(56, 189, 248, 0.2);
        color: var(--accent-sky);
        padding: 1px 6px;
        border-radius: 4px;
        font-size: 0.45rem;
        font-weight: 950;
        transition: 0.2s;
    }
    .btn-ai-sm:hover { background: var(--accent-sky); color: #000; }

    .sortable-ghost { opacity: 0; }
    .sortable-chosen { 
        background: #111 !important;
        border-color: var(--accent-sky) !important;
        box-shadow: 0 50px 100px var(--stellar-blue) !important; 
    }
</style>

<div class="px-8 py-3">
    <div class="flex items-center gap-4">
        <h1 class="text-white text-lg font-black uppercase tracking-[0.4em]">Master Control</h1>
        <div class="h-px bg-zinc-800 flex-1"></div>
        <div class="flex items-center gap-4">
            <span class="text-[0.55rem] text-zinc-500 font-bold uppercase tracking-widest">AI Status:</span>
            <span class="text-[0.55rem] text-sky-400 font-black animate-pulse">Scanning Grid...</span>
        </div>
    </div>
</div>

<div class="crm-container custom-scrollbar">
    
    <!-- COLUMNA 1: PROSPECTOS -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title">01 Leads</span>
            <span class="text-[9px] font-black opacity-40">{{ $prospectos->total() }}</span>
        </div>
        
        <div id="col-prospecto" class="kanban-list" data-status="prospecto" style="min-height: 500px">
            @foreach($prospectos as $pro)
            <div class="kanban-card" data-id="{{ $pro->id }}">
                <div class="card-tab tab-prospecto"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div class="card-name">{{ $pro->name }}</div>
                <div class="card-meta">
                    <span>{{ $pro->lead_source ?? 'Organic' }}</span>
                    <button class="btn-ai-sm"><i class="bi bi-robot"></i> IA DATA</button>
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
        </div>
        
        <div id="col-pendiente_pago" class="kanban-list" data-status="pendiente_pago" style="min-height: 500px">
            @foreach($pendientes as $pen)
            <div class="kanban-card" data-id="{{ $pen->id }}">
                <div class="card-tab tab-pago"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div class="card-name">{{ $pen->name }}</div>
                
                <div class="flex gap-1 my-1">
                    <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-ai-sm flex-1 text-center py-1">
                        DOC
                    </a>
                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="btn-ai-sm w-full bg-amber-500 text-black border-amber-500 py-1">
                           ACT
                        </button>
                    </form>
                </div>

                <div class="card-meta">
                    <span class="text-amber-500/50">Wait Payout</span>
                    <i class="bi bi-lightning-fill"></i>
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
        </div>
        
        <div id="col-activo" class="kanban-list" data-status="activo" style="min-height: 500px">
            @foreach($activos as $act)
            <div class="kanban-card" data-id="{{ $act->id }}">
                <div class="card-tab tab-activo"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div class="card-name">{{ $act->name }}</div>
                <div class="card-meta">
                    <span class="text-emerald-500/60">{{ $act->empresa?->nombre_comercial ?? 'Setup OK' }}</span>
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $activos->links() }}</div>
    </div>

    {{-- COLUMNA PROACTIVA --}}
    <div class="kanban-col opacity-20 flex items-center justify-center border-0">
        <div class="text-center">
             <i class="bi bi-robot fs-1 d-block mb-3"></i>
             <div class="spinner-grow spinner-grow-sm text-sky-500" role="status"></div>
             <p class="text-[0.5rem] font-black uppercase mt-4 tracking-widest text-zinc-500">Agent Signal Search</p>
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
