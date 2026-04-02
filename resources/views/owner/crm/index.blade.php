@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #fff;
        --border-color: rgba(255,255,255, 0.1);
        --accent-indigo: #6366f1;
        --accent-amber: #f59e0b;
        --accent-emerald: #10b981;
    }

    body { background-color: var(--oled-bg) !important; color: #fff; }

    .kanban-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        min-height: 85vh;
        border-top: 1px solid var(--border-color);
    }

    .kanban-col {
        border-right: 1px solid var(--border-color);
        padding: 1.5rem 1.25rem;
        background: linear-gradient(180deg, rgba(255,255,255, 0.01) 0%, transparent 100%);
    }

    .kanban-col:last-child { border-right: 0; }

    .col-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 0.5rem;
    }

    .header-pill {
        font-size: 0.65rem;
        font-weight: 900;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        padding: 6px 16px;
        border-radius: 50px;
        border: 1px solid rgba(255,255,255, 0.1);
        background: rgba(255,255,255, 0.03);
    }

    /* TARJETA COMPACTA FLOATING */
    .kanban-card {
        background: #fff;
        border-radius: 14px;
        padding: 0.7rem 0.85rem;
        margin-bottom: 1rem;
        position: relative;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.7);
        transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        cursor: grab;
        border-left: 5px solid transparent;
        overflow: hidden;
    }

    .kanban-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 25px 40px rgba(0, 0, 0, 0.9);
        z-index: 50;
    }

    /* NEON STRIPES */
    .card-prospecto { border-left-color: var(--accent-indigo) !important; }
    .card-pago { border-left-color: var(--accent-amber) !important; }
    .card-activo { border-left-color: var(--accent-emerald) !important; }

    .card-title { font-size: 0.85rem; font-weight: 800; color: #1e293b; margin-bottom: 2px; }
    .card-subtitle { font-size: 0.65rem; color: #64748b; font-weight: 500; }
    
    .card-meta {
        margin-top: 0.75rem;
        padding-top: 0.5rem;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.55rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #94a3b8;
        letter-spacing: 0.05em;
    }

    .btn-action-mini {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 4px 10px;
        border-radius: 8px;
        color: #1e293b;
        font-weight: 900;
        font-size: 0.55rem;
        transition: all 0.2s;
    }

    .btn-action-mini:hover { background: #000; color: #fff; border-color: #000; }

    .sortable-ghost { opacity: 0; }
    .sortable-drag { transform: rotate(3deg) scale(1.1); box-shadow: 0 30px 60px rgba(0,0,0,0.9); }

    /* PAGINACIÓN COMPACTA */
    .paginator-container nav svg { width: 14px; height: 14px; }
    .paginator-container nav p { display: none; }
</style>

<div class="kanban-grid">
    
    <!-- COLUMNA 1: PROSPECTOS -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-pill text-indigo-400">01. NUEVOS LEADS</span>
            <span class="text-[10px] text-zinc-600 font-black tracking-widest">{{ $prospectos->total() }}</span>
        </div>
        
        <div id="col-prospecto" class="kanban-list space-y-3" data-status="prospecto" style="min-height: 500px">
            @foreach($prospectos as $pro)
            <div class="kanban-card card-prospecto" data-id="{{ $pro->id }}">
                <div class="flex justify-between items-center opacity-40 mb-1">
                    <span class="text-[8px] font-black uppercase tracking-widest">Prospecto</span>
                    <i class="bi bi-person-plus text-[10px]"></i>
                </div>
                <div class="card-title truncate uppercase">{{ $pro->name }}</div>
                <div class="card-subtitle truncate">{{ $pro->email }}</div>
                
                <div class="card-meta">
                    <span>{{ $pro->lead_source ?? 'Organic' }}</span>
                    <span>{{ $pro->created_at ? $pro->created_at->diffForHumans() : 'Hoy' }}</span>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8 paginator-container">{{ $prospectos->links() }}</div>
    </div>

    <!-- COLUMNA 2: VALIDAR PAGO -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-pill text-amber-500">02. VALIDAR PAGO</span>
            <span class="text-[10px] text-zinc-600 font-black tracking-widest">{{ $pendientes->total() }}</span>
        </div>
        
        <div id="col-pendiente_pago" class="kanban-list space-y-3" data-status="pendiente_pago" style="min-height: 500px">
            @foreach($pendientes as $pen)
            <div class="kanban-card card-pago" data-id="{{ $pen->id }}">
                <div class="flex justify-between items-center opacity-40 mb-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-amber-600">Revisar Pago</span>
                    <i class="bi bi-wallet2 text-[10px]"></i>
                </div>
                <div class="card-title truncate uppercase">{{ $pen->name }}</div>
                
                <div class="flex gap-2 mt-4">
                    @if($pen->payment_voucher)
                        <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-action-mini flex-1 text-center">
                            Voucher <i class="bi bi-image ms-1"></i>
                        </a>
                    @endif

                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="btn-action-mini w-full bg-amber-500 text-black border-amber-500">
                           Aprobar
                        </button>
                    </form>
                </div>

                <div class="card-meta">
                    <span>Esperando OK de pago</span>
                    <span><i class="bi bi-clock-history"></i></span>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8 paginator-container">{{ $pendientes->links() }}</div>
    </div>

    <!-- COLUMNA 3: CLIENTES OK -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-pill text-emerald-400">03. ACTIVOS OK</span>
            <span class="text-[10px] text-zinc-600 font-black tracking-widest">{{ $activos->total() }}</span>
        </div>
        
        <div id="col-activo" class="kanban-list space-y-3" data-status="activo" style="min-height: 500px">
            @foreach($activos as $act)
            <div class="kanban-card card-activo" data-id="{{ $act->id }}">
                <div class="flex justify-between items-center opacity-40 mb-1">
                    <span class="text-[8px] font-black uppercase tracking-widest text-emerald-600">SaaS Live</span>
                    <i class="bi bi-shield-check text-[10px]"></i>
                </div>
                <div class="card-title truncate uppercase">{{ $act->name }}</div>
                <div class="card-subtitle truncate opacity-60">{{ $act->empresa?->nombre_comercial ?? 'Configurado' }}</div>
                
                <div class="card-meta">
                    <span>Activo</span>
                    <span><i class="bi bi-stars"></i></span>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8 paginator-container">{{ $activos->links() }}</div>
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
                animation: 350,
                easing: "cubic-bezier(0.165, 0.84, 0.44, 1)",
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                forceFallback: true,
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
