@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #0c0c0e;
        --border-color: rgba(255, 255, 255, 0.8);
        --accent-indigo: #6366f1;
        --accent-amber: #f59e0b;
        --accent-emerald: #10b981;
        --accent-sky: #38bdf8;
        --stellar-blue: rgba(30, 64, 175, 0.4); /* Azul profundo espacial */
    }

    body { background-color: var(--oled-bg) !important; color: #fff; overflow-x: hidden; }

    .crm-wrapper {
        display: flex;
        gap: 1.5rem;
        padding: 2.5rem;
        width: 100vw;
        overflow-x: auto;
        align-items: flex-start;
    }

    .kanban-col {
        width: 300px;
        flex-shrink: 0;
        background: rgba(255,255,255, 0.01);
        border-radius: 20px;
        padding: 1.25rem;
        border: 1px solid rgba(255,255,255,0.03);
    }

    .col-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .header-title {
        font-size: 0.65rem;
        font-weight: 900;
        letter-spacing: 0.35em;
        text-transform: uppercase;
        color: #3f3f46;
    }

    /* TARJETA CYBERNETIC POST-IT */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid #ffffff; /* BORDE 1PX SOLICITADO */
        border-radius: 12px;
        padding: 0.7rem 0.85rem;
        margin-bottom: 1.25rem;
        margin-left: 10px; /* Espacio para el Post-it exterior */
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        cursor: grab;
        
        /* SOMBRA AZUL ESTELAR */
        box-shadow: 0 15px 40px -5px var(--stellar-blue), 0 5px 15px rgba(0,0,0,0.8);
    }

    .kanban-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 30px 60px -10px rgba(56, 189, 248, 0.3), 0 10px 20px rgba(0,0,0,0.9);
        border-color: var(--accent-sky);
        z-index: 50;
    }

    /* PESTAÑA POST-IT EXTERIOR */
    .cyber-tab {
        position: absolute;
        left: -10px; 
        top: 15%;
        bottom: 15%;
        width: 10px;
        border-radius: 6px 0 0 6px;
        box-shadow: -4px 0 15px rgba(0,0,0,0.5);
    }
    .tab-prospecto { background: var(--accent-indigo); }
    .tab-pago { background: var(--accent-amber); }
    .tab-activo { background: var(--accent-emerald); }

    .card-handle {
        position: absolute;
        right: 8px;
        top: 8px;
        color: rgba(255,255,255,0.05);
        font-size: 1rem;
    }
    .kanban-card:hover .card-handle { color: var(--accent-sky); }

    .card-name { 
        font-size: 0.75rem; 
        font-weight: 800; 
        color: #fff; 
        text-transform: uppercase; 
        letter-spacing: 0.3px;
        padding-right: 15px;
    }

    /* DETALLES EN CELESTE SKY */
    .card-meta { 
        font-size: 0.55rem; 
        font-weight: 800; 
        color: var(--accent-sky); 
        display: flex; 
        justify-content: space-between;
        align-items: center;
        text-transform: uppercase;
        margin-top: 4px;
        padding-top: 4px;
        border-top: 1px solid rgba(255,255,255,0.02);
    }

    .card-subtext {
        font-size: 0.6rem;
        color: #52525b;
        font-weight: 600;
    }

    /* BOTONES AI COMPACTOS */
    .btn-cyber-ai {
        background: rgba(56, 189, 248, 0.05);
        border: 1px solid rgba(56, 189, 248, 0.1);
        color: var(--accent-sky);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.5rem;
        font-weight: 900;
    }
    .btn-cyber-ai:hover { background: var(--accent-sky); color: #000; }

    .sortable-chosen { 
        background: #000 !important;
        border-color: var(--accent-sky) !important;
        box-shadow: 0 40px 100px rgba(30, 64, 175, 0.8) !important; 
    }
    
    ::-webkit-scrollbar-thumb { background: #1e1e24; border-radius: 20px; }
</style>

<div class="px-10 py-6">
    <div class="flex items-center gap-6">
        <h1 class="text-white text-xl font-black uppercase tracking-[0.5em] border-r border-white/10 pr-6">Central Hub</h1>
        <span class="text-[0.6rem] text-sky-400 font-black uppercase tracking-widest opacity-60">
            <i class="bi bi-cpu-fill me-2"></i> Optimización de Flujo SaaS v6.0
        </span>
    </div>
</div>

<div class="crm-wrapper custom-scrollbar">
    
    <!-- COLUMNA 1: PROSPECTOS -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title">Fase 01 | Leads</span>
        </div>
        
        <div id="col-prospecto" class="kanban-list" data-status="prospecto" style="min-height: 600px">
            @foreach($prospectos as $pro)
            <div class="kanban-card" data-id="{{ $pro->id }}">
                <div class="cyber-tab tab-prospecto"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div class="card-name truncate">{{ $pro->name }}</div>
                <div class="card-subtext truncate">{{ $pro->email }}</div>
                <div class="card-meta">
                    <span>{{ $pro->lead_source ?? 'Organic' }}</span>
                    <button class="btn-cyber-ai"><i class="bi bi-robot"></i> INFO</button>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $prospectos->links() }}</div>
    </div>

    <!-- COLUMNA 2: PENDIENTE DE PAGO -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title text-amber-500/50">Fase 02 | Pagos</span>
        </div>
        
        <div id="col-pendiente_pago" class="kanban-list" data-status="pendiente_pago" style="min-height: 600px">
            @foreach($pendientes as $pen)
            <div class="kanban-card" data-id="{{ $pen->id }}">
                <div class="cyber-tab tab-pago"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div class="card-name truncate">{{ $pen->name }}</div>
                
                <div class="flex gap-2 my-1">
                    <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-cyber-ai flex-1 text-center py-1">
                        VOUCHER
                    </a>
                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="btn-cyber-ai w-full bg-amber-500 text-black border-amber-500 py-1">
                           OK
                        </button>
                    </form>
                </div>

                <div class="card-meta">
                    <span class="text-amber-500/60">Waiting Payment</span>
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $pendientes->links() }}</div>
    </div>

    <!-- COLUMNA 3: ACTIVOS -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title text-emerald-500/50">Fase 03 | Activos</span>
        </div>
        
        <div id="col-activo" class="kanban-list" data-status="activo" style="min-height: 600px">
            @foreach($activos as $act)
            <div class="kanban-card" data-id="{{ $act->id }}">
                <div class="cyber-tab tab-activo"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div class="card-name truncate text-zinc-300">{{ $act->name }}</div>
                <div class="card-subtext truncate opacity-50">{{ $act->empresa?->nombre_comercial ?? 'Setup Complete' }}</div>
                <div class="card-meta">
                    <span class="text-emerald-500/80">SaaS Live</span>
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $activos->links() }}</div>
    </div>

    {{-- COLUMNA DE MONITOREO --}}
    <div class="kanban-col opacity-30 border-2 border-dashed border-zinc-800 flex items-center justify-center">
        <div class="text-center">
            <div class="spinner-border spinner-border-sm text-sky-500 mb-4" role="status"></div>
            <p class="text-[0.6rem] font-black uppercase tracking-[0.3em] text-zinc-500">AI Signal Scanner...</p>
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
                animation: 300,
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
