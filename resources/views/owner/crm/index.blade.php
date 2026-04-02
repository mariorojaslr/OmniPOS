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

    /* TARJETA UNIFORME - VERSION FINAL SIMETRICA */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color); 
        border-radius: 12px;
        padding: 0.85rem;
        margin-bottom: 1.5rem; 
        margin-left: 10px;
        position: relative;
        height: 125px; /* Altura fija para simetría total */
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

    .card-tab {
        position: absolute;
        left: -10px; 
        top: 0;
        bottom: 0;
        width: 10px;
        border-radius: 8px 0 0 8px;
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
        font-size: 0.8rem; 
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
        font-size: 0.55rem;
        color: #71717a;
        font-weight: 600;
        margin-top: -3px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* SISTEMA DE BOTONES 2x2 UNIFORME */
    .btn-group-master {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.4rem;
        margin-top: 0.5rem;
    }

    .btn-sci-fi {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.1);
        color: var(--accent-sky);
        padding: 5px 0;
        border-radius: 6px;
        font-size: 0.5rem;
        font-weight: 950;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-sci-fi:not(.disabled):hover { background: var(--accent-sky); color: #000; border-color: var(--accent-sky); }
    .btn-sci-fi.disabled { opacity: 0.15; cursor: not-allowed; }
    .btn-amber { color: var(--accent-amber); border-color: rgba(245, 158, 11, 0.2); }
    .btn-amber:hover:not(.disabled) { background: var(--accent-amber); color: #000; }
    .btn-emerald { color: var(--accent-emerald); border-color: rgba(16, 185, 129, 0.2); }
    .btn-emerald:hover:not(.disabled) { background: var(--accent-emerald); color: #000; }

    /* TEXTOS DE ESTADO INTERNOS CON RESPIRO */
    .card-status-label {
        font-size: 0.45rem;
        font-weight: 900;
        color: var(--accent-sky);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-top: 8px;
        padding-top: 4px;
        border-top: 1px solid rgba(255,255,255,0.05);
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
        <h1 class="text-white text-xl font-black uppercase tracking-[0.5em]">Global Control Center</h1>
        <div class="h-px bg-zinc-800 flex-1"></div>
        <div class="flex items-center gap-4 text-sky-400 font-black text-[0.6rem] uppercase tracking-widest animate-pulse">
            <i class="bi bi-cpu"></i> IA OPS ACTIVE
        </div>
    </div>
</div>

<div class="crm-container custom-scrollbar">
    
    <!-- COLUMNA 1: PROSPECTOS -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title">01 Leads</span>
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
                    <button class="btn-sci-fi" onclick="showAIData('{{ $pro->name }}')">IA DATA</button>
                    <button class="btn-sci-fi disabled">MAIL</button>
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
                    <div class="card-subtext text-amber-500/30">Pago en Proceso</div>
                </div>
                
                <div class="btn-group-master">
                    @if($pen->payment_voucher)
                        <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-sci-fi">DOC</a>
                    @else
                        <span class="btn-sci-fi disabled">NO DOC</span>
                    @endif
                    
                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="btn-sci-fi btn-amber w-full">ACT</button>
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
                    <button class="btn-sci-fi btn-emerald">PANEL</button>
                    <button class="btn-sci-fi btn-emerald disabled">STATS</button>
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

</div>

{{-- MODAL IA DATA --}}
<div class="modal fade" id="modalIA" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content ai-modal border-sky-500/50">
      <div class="modal-header border-white/5">
        <h6 class="modal-title text-sky-400 fw-black uppercase tracking-widest"><i class="bi bi-robot"></i> Inteligencia Social MultiPOS</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-zinc-300 font-monospace" style="font-size: 0.75rem;">
          <div class="mb-3 text-sky-500/80">[SYSTEM]: Analizando interacciones de <span id="ia-client-name" class="text-white"></span>...</div>
          <div class="bg-zinc-900 p-3 rounded-xl border border-white/5">
                <p class="mb-1"><span class="text-zinc-500">></span> <span class="text-sky-400">Origen:</span> Instagram Ads (Hot Lead)</p>
                <p class="mb-1"><span class="text-zinc-500">></span> <span class="text-sky-400">Pregunta IA:</span> "¿Buscás optimizar tu caja diaria?"</p>
                <p class="mb-1"><span class="text-zinc-500">></span> <span class="text-sky-400">Respuesta:</span> "Sí, tengo 3 sucursales y es un lío."</p>
                <p class="mb-0"><span class="text-zinc-500">></span> <span class="text-emerald-500 text-bold">RECOMENDACIÓN:</span> Plan Multi-Sucursal VIP.</p>
          </div>
          <div class="mt-4 flex gap-2">
                <button class="btn-sci-fi w-full py-2 bg-sky-500 text-black border-0">DISPARAR OFERTA AUTOMÁTICA</button>
          </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showAIData(name) {
        document.getElementById('ia-client-name').innerText = name;
        new bootstrap.Modal(document.getElementById('modalIA')).show();
    }

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
