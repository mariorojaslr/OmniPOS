@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #0c0c0e;
        --border-color: #ffffff;
        --accent-indigo: #6366f1;
        --accent-amber: #f59e0b;
        --accent-emerald: #10b981;
        --accent-sky: #38bdf8;
    }

    body { background-color: var(--oled-bg) !important; color: #fff; overflow-x: hidden; }

    .crm-wrapper {
        display: flex;
        gap: 1.5rem;
        padding: 2rem;
        width: 100vw;
        overflow-x: auto;
        align-items: flex-start;
    }

    .kanban-col {
        width: 300px;
        flex-shrink: 0;
        background: rgba(255,255,255, 0.02);
        border-radius: 24px;
        padding: 1.25rem;
        border: 1px solid rgba(255,255,255,0.05);
    }

    .col-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding-bottom: 1rem;
    }

    .header-title {
        font-size: 0.7rem;
        font-weight: 900;
        letter-spacing: 0.3em;
        text-transform: uppercase;
        color: #71717a;
    }

    /* TARJETA PRECISIÓN TITANIUM */
    .kanban-card {
        background: var(--card-bg);
        border: 2px solid #ffffff; /* BORDE BLANCO TOTAL SOLICITADO */
        border-radius: 20px;
        padding: 0.75rem 1rem;
        margin-bottom: 1.25rem;
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        cursor: grab;
        box-shadow: 0 15px 35px rgba(0,0,0,0.8), 0 5px 15px rgba(255,255,255,0.05);
        overflow: hidden;
    }

    .kanban-card:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 40px 60px rgba(0,0,0,0.9), 0 10px 20px rgba(255,255,255,0.1);
        z-index: 50;
        border-color: var(--accent-sky);
    }

    /* NEON STRIPES LADO IZQUIERDO */
    .stripe {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
    }
    .stripe-prospecto { background: var(--accent-indigo); }
    .stripe-pago { background: var(--accent-amber); }
    .stripe-activo { background: var(--accent-emerald); }

    .card-handle {
        position: absolute;
        right: 12px;
        top: 12px;
        color: rgba(255,255,255,0.1);
        font-size: 1.2rem;
        cursor: grab;
    }
    .kanban-card:hover .card-handle { color: var(--accent-sky); }

    .card-name { 
        font-size: 0.8rem; 
        font-weight: 900; 
        color: #fff; 
        text-transform: uppercase; 
        letter-spacing: 0.5px;
        padding-right: 20px;
    }

    /* DETALLES EN CELESTE NEÓN */
    .card-meta { 
        font-size: 0.6rem; 
        font-weight: 800; 
        color: var(--accent-sky); 
        display: flex; 
        justify-content: space-between;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .card-subtext {
        font-size: 0.65rem;
        color: #52525b;
        font-weight: 600;
        margin-top: -2px;
    }

    /* ACCIONES RÁPIDAS */
    .btn-ai-detail {
        background: rgba(56, 189, 248, 0.1);
        border: 1px solid rgba(56, 189, 248, 0.2);
        color: var(--accent-sky);
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.55rem;
        font-weight: 900;
        transition: all 0.2s;
    }
    .btn-ai-detail:hover { background: var(--accent-sky); color: #000; }

    .sortable-chosen { 
        opacity: 0.8 !important;
        border-color: var(--accent-sky) !important;
        box-shadow: 0 50px 80px rgba(56, 189, 248, 0.3) !important; 
    }
</style>

<div class="px-8 py-6">
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-4">
            <h1 class="text-white text-2xl font-black uppercase tracking-[0.4em]">Control Room</h1>
            <div class="h-px w-20 bg-gradient-to-r from-zinc-800 to-transparent"></div>
            <span class="text-[0.6rem] text-sky-400 font-black uppercase tracking-widest animate-pulse">Agente IA Monitoreando...</span>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right">
                <span class="text-[0.5rem] text-zinc-500 font-bold block uppercase tracking-tighter">Sesión de Mando</span>
                <span class="text-[0.7rem] text-white font-black">{{ auth()->user()->name }}</span>
            </div>
            <div class="w-10 h-10 rounded-full bg-zinc-900 border border-white/10 flex items-center justify-center">
                <i class="bi bi-person-circle text-zinc-500"></i>
            </div>
        </div>
    </div>
</div>

<div class="crm-wrapper custom-scrollbar">
    
    <!-- COLUMNA 1: PROSPECTOS -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title">01 Prospectos</span>
            <span class="bg-zinc-900 text-white text-[9px] font-black px-2 py-1 rounded-md border border-white/5">{{ $prospectos->total() }}</span>
        </div>
        
        <div id="col-prospecto" class="kanban-list" data-status="prospecto" style="min-height: 600px">
            @foreach($prospectos as $pro)
            <div class="kanban-card" data-id="{{ $pro->id }}">
                <div class="stripe stripe-prospecto"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div class="card-name truncate">{{ $pro->name }}</div>
                <div class="card-subtext truncate">{{ $pro->email }}</div>
                <div class="card-meta">
                    <span>{{ $pro->lead_source ?? 'DIRECTO' }}</span>
                    <button class="btn-ai-detail"><i class="bi bi-eye"></i> IA INFO</button>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $prospectos->links() }}</div>
    </div>

    <!-- COLUMNA 2: VALIDAR PAGO -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title text-amber-500">02 Validar</span>
            <span class="bg-amber-500/20 text-amber-500 text-[9px] font-black px-2 py-1 rounded-md border border-amber-500/20">{{ $pendientes->total() }}</span>
        </div>
        
        <div id="col-pendiente_pago" class="kanban-list" data-status="pendiente_pago" style="min-height: 600px">
            @foreach($pendientes as $pen)
            <div class="kanban-card" data-id="{{ $pen->id }}">
                <div class="stripe stripe-pago"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div class="card-name truncate">{{ $pen->name }}</div>
                
                <div class="flex gap-2 my-1">
                    <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-ai-detail flex-1 text-center bg-zinc-900 border-zinc-800">
                        Voucher
                    </a>
                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="btn-ai-detail w-full bg-amber-500 text-black border-amber-500">
                           Aprobar
                        </button>
                    </form>
                </div>

                <div class="card-meta">
                    <span class="text-amber-500">Esperando OK</span>
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $pendientes->links() }}</div>
    </div>

    <!-- COLUMNA 3: ACTIVOS -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title text-emerald-400">03 Activos</span>
            <span class="bg-emerald-500/20 text-emerald-500 text-[9px] font-black px-2 py-1 rounded-md border border-emerald-500/20">{{ $activos->total() }}</span>
        </div>
        
        <div id="col-activo" class="kanban-list" data-status="activo" style="min-height: 600px">
            @foreach($activos as $act)
            <div class="kanban-card" data-id="{{ $act->id }}">
                <div class="stripe stripe-activo"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div class="card-name truncate">{{ $act->name }}</div>
                <div class="card-subtext truncate">{{ $act->empresa?->nombre_comercial ?? 'Setup OK' }}</div>
                <div class="card-meta">
                    <span class="text-emerald-500">SaaS Live</span>
                    <i class="bi bi-stars"></i>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $activos->links() }}</div>
    </div>

    {{-- COLUMNA PROACTIVA --}}
    <div class="kanban-col bg-zinc-900/30 border-dashed border-zinc-800 border-2">
        <div class="col-header border-0">
             <span class="header-title text-zinc-700">AI AGENTS</span>
        </div>
        <div class="p-4 text-center">
            <i class="bi bi-robot text-zinc-700 fs-1 mb-3 d-block"></i>
            <p class="text-[0.6rem] text-zinc-700 font-bold uppercase tracking-widest">Escaneando Redes Sociales...</p>
            <div class="mt-4 bg-zinc-900 border border-white/5 p-3 rounded-2xl opacity-40">
                <span class="text-[0.5rem] text-zinc-500 block">Lead Finder:</span>
                <span class="text-[0.6rem] text-zinc-600 font-black">Waiting for Next Session</span>
            </div>
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
