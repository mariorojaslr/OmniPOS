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
        margin-bottom: 2rem;
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

    .badge-count { font-size: 0.6rem; color: #fff; opacity: 0.4; font-weight: 800; }

    /* TARJETA UNIFORME - ALTA DENSIDAD */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color); /* BORDE 1PX BLANCO */
        border-radius: 12px;
        padding: 0.85rem;
        margin-bottom: 0.85rem;
        margin-left: 10px;
        position: relative;
        height: 105px; /* ALTURA UNIFORME CALCULADA PARA 2 BOTONES */
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
        box-shadow: 0 35px 70px -10px rgba(56, 189, 248, 0.4);
        z-index: 50;
    }

    /* PESTAÑA POST-IT 100% ALTURA */
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
    .kanban-card:hover .card-handle { color: var(--accent-sky); }

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
        font-size: 0.65rem;
        color: #71717a;
        font-weight: 600;
        margin-top: -3px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* SISTEMA DE BOTONES UNIFORME */
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
        font-size: 0.55rem;
        font-weight: 950;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        transition: all 0.2s;
    }

    .btn-sci-fi:hover { background: var(--accent-sky); color: #000; border-color: var(--accent-sky); }

    .btn-amber { color: var(--accent-amber); border-color: rgba(245, 158, 11, 0.2); }
    .btn-amber:hover { background: var(--accent-amber); }

    .card-meta-sky {
        font-size: 0.55rem;
        font-weight: 800;
        color: var(--accent-sky);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 4px;
        display: flex;
        justify-content: space-between;
    }

    .sortable-ghost { opacity: 0; }
    .sortable-chosen { 
        background: #000 !important;
        box-shadow: 0 40px 100px var(--stellar-blue) !important; 
    }
</style>

<div class="px-10 py-4">
    <div class="flex items-center gap-6">
        <h1 class="text-white text-xl font-black uppercase tracking-[0.5em]">Command Hub</h1>
        <div class="h-px bg-zinc-800 flex-1"></div>
        <div class="flex items-center gap-4">
            <span class="text-[0.6rem] text-sky-400 font-black uppercase tracking-widest animate-pulse">Syncing...</span>
        </div>
    </div>
</div>

<div class="crm-container custom-scrollbar">
    
    <!-- COLUMNA 1: PROSPECTOS -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title">01 Leads</span>
            <span class="badge-count">{{ $prospectos->total() }}</span>
        </div>
        
        <div id="col-prospecto" class="kanban-list" data-status="prospecto" style="min-height: 500px">
            @foreach($prospectos as $pro)
            <div class="kanban-card" data-id="{{ $pro->id }}">
                <div class="card-tab tab-prospecto"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div>
                    <div class="card-name">{{ $pro->name }}</div>
                    <div class="card-subtext">{{ $pro->lead_source ?? 'Organic' }}</div>
                </div>
                
                <div class="btn-group-master">
                    <button class="btn-sci-fi w-full"><i class="bi bi-robot"></i> IA DATA</button>
                </div>

                <div class="card-meta-sky">
                    <span>Prospecto OK</span>
                    <span><i class="bi bi-person-plus-fill"></i></span>
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
            <span class="badge-count">{{ $pendientes->total() }}</span>
        </div>
        
        <div id="col-pendiente_pago" class="kanban-list" data-status="pendiente_pago" style="min-height: 500px">
            @foreach($pendientes as $pen)
            <div class="kanban-card" data-id="{{ $pen->id }}">
                <div class="card-tab tab-pago"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div>
                    <div class="card-name">{{ $pen->name }}</div>
                    <div class="card-subtext">Waiting Payment</div>
                </div>
                
                <div class="btn-group-master">
                    <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-sci-fi">DOC</a>
                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="btn-sci-fi btn-amber w-full">ACT</button>
                    </form>
                </div>

                <div class="card-meta-sky">
                    <span class="text-amber-500/50 italic">Verify Voucher</span>
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
            <span class="badge-count">{{ $activos->total() }}</span>
        </div>
        
        <div id="col-activo" class="kanban-list" data-status="activo" style="min-height: 500px">
            @foreach($activos as $act)
            <div class="kanban-card" data-id="{{ $act->id }}">
                <div class="card-tab tab-activo"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div>
                    <div class="card-name">{{ $act->name }}</div>
                    <div class="card-subtext">{{ $act->empresa?->nombre_comercial ?? 'Setup Complete' }}</div>
                </div>

                <div class="btn-group-master">
                    <button class="btn-sci-fi w-full">SAAS DASHBOARD</button>
                </div>

                <div class="card-meta-sky">
                    <span class="text-emerald-500/50">SaaS Live OK</span>
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $activos->links() }}</div>
    </div>

    {{-- COLUMNA PROACTIVA --}}
    <div class="kanban-col opacity-20 flex items-center justify-center">
        <div class="text-center py-20 border-2 border-dashed border-zinc-800 rounded-3xl">
             <i class="bi bi-robot fs-1 d-block mb-3 text-sky-500"></i>
             <p class="text-[0.6rem] font-black uppercase tracking-widest text-zinc-500">AI Social Scanner...</p>
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
