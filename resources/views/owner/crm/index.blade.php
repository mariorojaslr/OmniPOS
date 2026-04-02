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
        transition: opacity 0.4s;
    }

    /* ATENUACION SIN DESENFOQUE COMO PEDISTE */
    body.modal-open .crm-container { opacity: 0.3; }

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

    .header-title { font-size: 0.7rem; font-weight: 900; letter-spacing: 0.3em; text-transform: uppercase; color: #fff; }

    /* TARJETA UNIFORME - ALTURA 130PX Y MARGEN 15PX (4mm) */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color); 
        border-radius: 12px;
        padding: 0.85rem;
        margin-bottom: 15px; /* DENSIDAD EXTREMA */
        margin-left: 10px;
        position: relative;
        height: 130px; /* ALINEACION PERFECTA */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: border-color 0.3s, box-shadow 0.3s;
        cursor: grab;
        box-shadow: 0 5px 15px -5px var(--stellar-blue);
    }

    /* CLASE FOCO (SOLO ILUMINACION) */
    .kanban-card.active-spotlight {
        z-index: 2000 !important;
        border-color: var(--accent-sky) !important;
        box-shadow: 0 0 100px var(--accent-sky) !important;
        pointer-events: none;
    }

    .kanban-card:hover:not(.active-spotlight) {
        border-color: var(--accent-sky);
        box-shadow: 0 35px 70px -10px rgba(56, 189, 248, 0.4);
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

    .card-handle { position: absolute; right: 10px; top: 10px; color: rgba(255,255,255,0.05); font-size: 1rem; }

    .card-name { font-size: 0.85rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding-right: 15px; }
    .card-subtext { font-size: 0.55rem; color: #71717a; font-weight: 600; text-transform: uppercase; }

    .btn-group-master { display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem; margin-top: 0.5rem; }
    .btn-sci-fi { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); color: var(--accent-sky); padding: 5px 0; border-radius: 6px; font-size: 0.5rem; font-weight: 950; text-align: center; text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s; text-decoration: none; }
    .btn-sci-fi:not(.disabled):hover { background: var(--accent-sky); color: #000; border-color: var(--accent-sky); }
    .btn-sci-fi.disabled { opacity: 0.15; cursor: not-allowed; }
    .btn-amber { color: var(--accent-amber); border-color: rgba(245, 158, 11, 0.2); }
    .btn-amber:hover:not(.disabled) { background: var(--accent-amber); color: #000; }
    .btn-emerald { color: var(--accent-emerald); border-color: rgba(16, 185, 129, 0.2); }
    .btn-emerald:hover:not(.disabled) { background: var(--accent-emerald); color: #000; }

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

    /* MODAL IA EXCLUSIVO - CENTRO ABSOLUTO GEOMETRICO */
    #modalIA .modal-dialog {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.9) !important;
        margin: 0;
        max-width: 500px;
        width: 90%;
        z-index: 3000 !important;
    }
    #modalIA.show .modal-dialog {
        transform: translate(-50%, -50%) scale(1) !important;
    }
    .ai-modal {
        background: #09090b !important;
        border: 2px solid var(--accent-sky);
        box-shadow: 0 0 100px rgba(56, 189, 248, 0.5);
        border-radius: 24px;
    }
    .modal-backdrop.show { opacity: 0.85; background: #000; }
</style>

<div class="px-10 py-4">
    <div class="flex items-center gap-6">
        <h1 class="text-white text-xl font-black uppercase tracking-[0.5em]">Command Hub</h1>
        <div class="h-px bg-zinc-800 flex-1"></div>
        <div class="flex items-center gap-4 text-sky-400 font-black text-[0.6rem] uppercase tracking-widest animate-pulse">
            <i class="bi bi-robot"></i> AGENTE SOCIAL LIVE
        </div>
    </div>
</div>

<div class="crm-container custom-scrollbar" id="crmContainer">
    
    <!-- COLUMNA 1: LEADS -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title">Fase 01 | Leads</span>
            <span class="text-white/40 text-[9px] font-black">{{ $prospectos->total() }}</span>
        </div>
        
        <div id="col-prospecto" class="kanban-list" data-status="prospecto" style="min-height: 500px">
            @foreach($prospectos as $pro)
            <div class="kanban-card" data-id="{{ $pro->id }}" id="card-{{ $pro->id }}">
                <div class="card-tab tab-prospecto"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div>
                    <div class="card-name">{{ $pro->name }}</div>
                    <div class="card-subtext">{{ $pro->lead_source ?? 'Landing Directo' }}</div>
                </div>
                
                <div class="btn-group-master">
                    <button class="btn-sci-fi" onclick="showAISpotlight('{{ $pro->id }}', '{{ $pro->name }}', '{{ $pro->lead_source ?? 'INSTAGRAM' }}')">IA DATA</button>
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
            <span class="header-title text-amber-500">Fase 02 | Validar</span>
        </div>
        
        <div id="col-pendiente_pago" class="kanban-list" data-status="pendiente_pago" style="min-height: 500px">
            @foreach($pendientes as $pen)
            <div class="kanban-card" data-id="{{ $pen->id }}" id="card-{{ $pen->id }}">
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
            <span class="header-title text-emerald-500">Fase 03 | Activos</span>
        </div>
        
        <div id="col-activo" class="kanban-list" data-status="activo" style="min-height: 500px">
            @foreach($activos as $act)
            <div class="kanban-card" data-id="{{ $act->id }}" id="card-{{ $act->id }}">
                <div class="card-tab tab-activo"></div>
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div>
                    <div class="card-name text-zinc-300">{{ $act->name }}</div>
                    <div class="card-subtext truncate">{{ $act->empresa?->nombre_comercial ?? 'Setup Complete' }}</div>
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

{{-- MODAL IA DATA - FOCO DINAMICO --}}
<div class="modal fade" id="modalIA" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content ai-modal">
      <div class="modal-header border-white/5">
        <h6 class="modal-title text-sky-400 fw-black uppercase tracking-[0.2em]"><i class="bi bi-robot me-2"></i> Reporte Agente IA</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="clearSpotlight()"></button>
      </div>
      <div class="modal-body text-zinc-300 font-monospace p-4" style="font-size: 0.75rem;">
          <div class="mb-4 text-sky-500">>>> ANALIZANDO CLIENTE: <span id="ia-client-name" class="text-white text-bold"></span></div>
          <div class="bg-zinc-900/80 p-4 rounded-2xl border border-sky-500/20 shadow-inner">
                <div class="mb-3"><span class="text-zinc-600">ORIGEN:</span> <span id="ia-client-source" class="text-emerald-400"></span></div>
                <div class="mb-3"><span class="text-zinc-600">MENSAJE IA:</span> <br> "¿Cómo te gustaría automatizar tu recaudación?"</div>
                <div class="mb-1"><span class="text-zinc-600">RESPUESTA:</span> <br> "Busco un sistema rápido y que ande en el celu."</div>
          </div>
          <div class="mt-5">
                <button class="btn-sci-fi w-full py-3 bg-sky-500 text-black border-0 fw-black shadow-lg" data-bs-dismiss="modal" onclick="clearSpotlight()">
                    VOLVER A LA CONSOLA
                </button>
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
    let activeCardId = null;

    function showAISpotlight(id, name, source) {
        // Iluminar Tarjeta
        activeCardId = id;
        const card = document.getElementById('card-' + id);
        if(card) card.classList.add('active-spotlight');

        // Cargar Datos
        document.getElementById('ia-client-name').innerText = name;
        document.getElementById('ia-client-source').innerText = source;

        // Mostrar Modal
        new bootstrap.Modal(document.getElementById('modalIA')).show();
    }

    function clearSpotlight() {
        if(activeCardId) {
            const card = document.getElementById('card-' + activeCardId);
            if(card) card.classList.remove('active-spotlight');
            activeCardId = null;
        }
    }

    // Cerrar spotlight si tocan fuera del modal
    document.getElementById('modalIA').addEventListener('hidden.bs.modal', function () {
        clearSpotlight();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const columns = ['col-prospecto', 'col-pendiente_pago', 'col-activo'];
        columns.forEach(id => {
            const el = document.getElementById(id);
            if(el) {
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
            }
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
