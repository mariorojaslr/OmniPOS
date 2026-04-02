@extends('layouts.owner')

@section('content')
<style>
    :root {
        --oled-bg: #000000;
        --card-bg: #09090b;
        --border-color: rgba(255, 255, 255, 0.08);
        --accent-indigo: #818cf8;
        --accent-amber: #fbbf24;
        --accent-emerald: #34d399;
    }

    body { background-color: var(--oled-bg) !important; }
    
    .kanban-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        padding: 1rem;
    }

    .kanban-column {
        background: transparent;
        border-radius: 24px;
        min-height: 80vh;
    }

    .column-header {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-tag {
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 50px;
        background: rgba(255, 255, 255, 0.05);
    }

    .kanban-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 0.85rem;
        margin-bottom: 1rem;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.6);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: grab;
    }

    .kanban-card:hover {
        transform: translateY(-4px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.8);
        background: #111111;
    }

    .card-id { font-size: 0.6rem; color: #52525b; font-weight: 700; }
    .card-name { font-size: 0.85rem; font-weight: 700; color: #fafafa; margin: 4px 0; }
    .card-email { font-size: 0.7rem; color: #71717a; }
    
    .card-footer {
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.03);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .tag-mini {
        font-size: 0.55rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.03);
        color: #a1a1aa;
    }

    .sortable-ghost { opacity: 0; }
    .sortable-chosen { box-shadow: 0 20px 40px rgba(129, 140, 248, 0.2) !important; }

    /* Custom Scrollbar for OLED */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #27272a; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #3f3f46; }
</style>

<div class="px-6 py-6 pb-20">
    
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-white text-3xl font-extrabold tracking-tight">Ventas & CRM</h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[0.6rem] text-zinc-500 font-bold uppercase tracking-widest">Master Dashboard Monitoring</span>
            </div>
        </div>
        <div class="text-right">
            <div class="text-white font-black text-xs opacity-40 uppercase tracking-widest">MultiPOS Central Suite</div>
        </div>
    </div>

    <div class="kanban-container">
        
        <!-- COLUMNA 1: PROSPECTOS -->
        <div class="kanban-column">
            <div class="column-header">
                <div>
                    <span class="header-tag text-indigo-400">01. NUEVOS LEADS</span>
                </div>
                <span class="text-[10px] text-zinc-500 font-bold">{{ $prospectos->total() }}</span>
            </div>
            
            <div id="col-prospecto" class="kanban-list" data-status="prospecto" style="min-height: 500px">
                @foreach($prospectos as $pro)
                <div class="kanban-card" data-id="{{ $pro->id }}">
                    <div class="flex justify-between items-start">
                        <span class="card-id">#PRO-{{ $pro->id }}</span>
                        <span class="text-[9px] text-zinc-600 font-bold">{{ $pro->created_at ? $pro->created_at->diffForHumans() : 'Hoy' }}</span>
                    </div>
                    <div class="card-name">{{ $pro->name }}</div>
                    <div class="card-email truncate">{{ $pro->email }}</div>
                    
                    <div class="card-footer">
                        <span class="tag-mini">{{ $pro->lead_source ?? 'Landing' }}</span>
                        <i class="bi bi-person-plus text-indigo-400" style="font-size: 0.8rem"></i>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $prospectos->links() }}</div>
        </div>

        <!-- COLUMNA 2: PENDIENTE DE PAGO -->
        <div class="kanban-column">
            <div class="column-header" style="border-bottom-color: rgba(251, 191, 36, 0.1)">
                <div>
                    <span class="header-tag text-amber-400">02. VALIDAR PAGO</span>
                </div>
                <span class="text-[10px] text-zinc-500 font-bold">{{ $pendientes->total() }}</span>
            </div>
            
            <div id="col-pendiente_pago" class="kanban-list" data-status="pendiente_pago" style="min-height: 500px">
                @foreach($pendientes as $pen)
                <div class="kanban-card" data-id="{{ $pen->id }}" style="border-left: 2px solid var(--accent-amber)">
                    <div class="flex justify-between items-start">
                        <span class="card-id">#WAIT-{{ $pen->id }}</span>
                        <span class="text-[9px] text-amber-500/50 font-bold uppercase tracking-widest">Pendiente</span>
                    </div>
                    <div class="card-name">{{ $pen->name }}</div>
                    
                    @if($pen->payment_voucher)
                        <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="block w-full py-2 mt-3 bg-zinc-900 border border-zinc-800 rounded-lg text-center text-[10px] font-bold text-white hover:bg-amber-500 hover:text-black transition-all uppercase tracking-widest">
                            Ver Voucher <i class="bi bi-image"></i>
                        </a>
                    @endif

                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full py-2 bg-amber-500 rounded-lg border-0 text-black text-[10px] font-black uppercase tracking-widest hover:bg-white transition-all shadow-lg active:scale-95">
                           Activar
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $pendientes->links() }}</div>
        </div>

        <!-- COLUMNA 3: ACTIVOS -->
        <div class="kanban-column">
            <div class="column-header" style="border-bottom-color: rgba(52, 211, 153, 0.1)">
                <div>
                    <span class="header-tag text-emerald-400">03. CLIENTES OK</span>
                </div>
                <span class="text-[10px] text-zinc-500 font-bold">{{ $activos->total() }}</span>
            </div>
            
            <div id="col-activo" class="kanban-list" data-status="activo" style="min-height: 500px">
                @foreach($activos as $act)
                <div class="kanban-card" data-id="{{ $act->id }}" style="border-left: 2px solid var(--accent-emerald)">
                    <div class="flex justify-between items-start">
                        <span class="card-id text-emerald-500/50">#LIVE-{{ $act->id }}</span>
                        <i class="bi bi-shield-check text-emerald-400" style="font-size: 0.7rem"></i>
                    </div>
                    <div class="card-name">{{ $act->name }}</div>
                    <div class="card-email truncate opacity-50">{{ $act->empresa?->nombre_comercial ?? 'Setup Completo' }}</div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $activos->links() }}</div>
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
                animation: 250,
                ghostClass: 'sortable-ghost',
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
                    alert('Error en movimiento');
                    location.reload();
                }
            });
        }
    });
</script>
@endsection
