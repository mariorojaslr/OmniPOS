@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #0c0c0e;
        --border-color: rgba(255, 255, 255, 0.05);
        --accent-indigo: #6366f1;
        --accent-amber: #f59e0b;
        --accent-emerald: #10b981;
    }

    body { background-color: var(--oled-bg) !important; color: #fff; overflow-x: hidden; }

    .crm-wrapper {
        display: flex;
        gap: 1rem;
        padding: 1.5rem;
        width: 100vw;
        overflow-x: auto;
        align-items: flex-start;
    }

    .kanban-col {
        width: 280px;
        flex-shrink: 0;
        background: rgba(255,255,255, 0.02);
        border-radius: 20px;
        padding: 1rem;
        border: 1px solid var(--border-color);
    }

    .col-header {
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 0.75rem;
    }

    .header-title {
        font-size: 0.65rem;
        font-weight: 900;
        letter-spacing: 0.25em;
        text-transform: uppercase;
        color: #52525b;
    }

    .badge-count {
        font-size: 0.6rem;
        background: #18181b;
        color: #fff;
        padding: 2px 8px;
        border-radius: 6px;
        font-weight: 800;
    }

    /* TARJETA ULTRA COMPACTA */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 0.6rem 0.75rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 10px rgba(0,0,0,0.5);
        border-left: 3px solid transparent;
    }

    .kanban-card:hover {
        background: #141417;
        border-color: rgba(255,255,255,0.15);
        transform: scale(1.02);
        box-shadow: 0 20px 30px rgba(0,0,0,0.8);
        z-index: 50;
    }

    .card-handle {
        cursor: grab;
        color: #27272a;
        font-size: 1.1rem;
        transition: color 0.2s;
    }

    .kanban-card:hover .card-handle { color: #52525b; }

    .card-content { flex: 1; min-width: 0; }
    .card-name { font-size: 0.75rem; font-weight: 800; color: #e4e4e7; margin-bottom: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .card-meta { font-size: 0.6rem; color: #52525b; font-weight: 600; display: flex; justify-content: space-between; }

    /* NEON ACCENTS */
    .card-prospecto { border-left-color: var(--accent-indigo) !important; }
    .card-pago { border-left-color: var(--accent-amber) !important; }
    .card-activo { border-left-color: var(--accent-emerald) !important; }

    /* SORTABLE STYLES */
    .sortable-ghost { opacity: 0.1; filter: blur(4px); }
    .sortable-chosen { 
        background: #18181b !important; 
        box-shadow: 0 30px 60px rgba(99, 102, 241, 0.3) !important; 
        border-color: var(--accent-indigo) !important;
        transform: rotate(1deg) scale(1.05) !important;
    }

    /* MINI ACTIONS FOR PAYMENT PHASE */
    .btn-approve {
        background: var(--accent-amber);
        color: #000;
        font-size: 0.5rem;
        font-weight: 900;
        padding: 2px 6px;
        border-radius: 4px;
        text-transform: uppercase;
        border: 0;
    }
</style>

<div class="px-6 py-4">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-white text-xl font-black uppercase tracking-[0.2em]">CRM Pipeline</h1>
            <p class="text-[0.6rem] text-zinc-500 font-bold uppercase tracking-widest mt-0.5">Gestión de Prospectos de Alta Densidad</p>
        </div>
        <div class="bg-zinc-900 border border-white/5 p-2 px-4 rounded-xl flex items-center gap-4">
             <div class="flex flex-col text-right">
                <span class="text-[0.5rem] text-zinc-500 font-bold uppercase">Estado Sistema</span>
                <span class="text-[0.7rem] text-emerald-500 font-black">ONLINE OK</span>
             </div>
        </div>
    </div>
</div>

<div class="crm-wrapper custom-scrollbar">
    
    <!-- COLUMNA 1: PROSPECTOS -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title">01 Prospectos</span>
            <span class="badge-count">{{ $prospectos->total() }}</span>
        </div>
        
        <div id="col-prospecto" class="kanban-list" data-status="prospecto" style="min-height: 500px">
            @foreach($prospectos as $pro)
            <div class="kanban-card card-prospecto" data-id="{{ $pro->id }}">
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div class="card-content">
                    <div class="card-name">{{ $pro->name }}</div>
                    <div class="card-meta">
                        <span>{{ $pro->lead_source ?? 'Organic' }}</span>
                        <span class="opacity-40">{{ $pro->created_at ? $pro->created_at->format('d/m') : '' }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $prospectos->links() }}</div>
    </div>

    <!-- COLUMNA 2: PAGO PENDIENTE -->
    <div class="kanban-col" style="border-color: rgba(245, 158, 11, 0.1)">
        <div class="col-header">
            <span class="header-title text-amber-500/50">02 Validar Pago</span>
            <span class="badge-count bg-amber-500/20 text-amber-500">{{ $pendientes->total() }}</span>
        </div>
        
        <div id="col-pendiente_pago" class="kanban-list" data-status="pendiente_pago" style="min-height: 500px">
            @foreach($pendientes as $pen)
            <div class="kanban-card card-pago" data-id="{{ $pen->id }}">
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div class="card-content">
                    <div class="flex justify-between items-center">
                        <div class="card-name">{{ $pen->name }}</div>
                        <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-approve">OK</button>
                        </form>
                    </div>
                    <div class="card-meta mt-1">
                        <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="text-amber-500 flex items-center gap-1">
                           <i class="bi bi-image"></i> Voucher
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $pendientes->links() }}</div>
    </div>

    <!-- COLUMNA 3: ACTIVOS -->
    <div class="kanban-col" style="border-color: rgba(16, 185, 129, 0.1)">
        <div class="col-header">
            <span class="header-title text-emerald-500/50">03 Clientes OK</span>
            <span class="badge-count bg-emerald-500/20 text-emerald-500">{{ $activos->total() }}</span>
        </div>
        
        <div id="col-activo" class="kanban-list" data-status="activo" style="min-height: 500px">
            @foreach($activos as $act)
            <div class="kanban-card card-activo" data-id="{{ $act->id }}">
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div class="card-content">
                    <div class="card-name text-zinc-300">{{ $act->name }}</div>
                    <div class="card-meta">
                        <span class="text-emerald-500">{{ $act->empresa?->nombre_comercial ?? 'Active' }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $activos->links() }}</div>
    </div>

    {{-- COLUMNA DE FUTURO (ESPERA) --}}
    <div class="kanban-col opacity-20 border-dashed border-2 flex items-center justify-center">
        <div class="text-center">
            <i class="bi bi-plus-circle fs-3 mb-2 d-block"></i>
            <span class="text-[0.6rem] font-black uppercase tracking-widest">Nuevos Módulos</span>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statuses = ['prospecto', 'pendiente_pago', 'activo'];
        
        statuses.forEach(status => {
            const el = document.getElementById('col-' + status);
            new Sortable(el, {
                group: 'kanban',
                handle: '.card-handle', // Solo se puede arrastrar desde el icono de grip
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
