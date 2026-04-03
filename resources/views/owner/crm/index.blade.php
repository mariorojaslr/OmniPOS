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

    /* CABECERA CON ESPACIO Y PROPORCIÓN */
    .header-hub {
        padding: 2.5rem 4rem 1.5rem 4rem; /* AIRE SUPERIOR Y LATERAL */
        display: flex;
        align-items: center;
        gap: 3rem;
    }

    .crm-container {
        display: flex;
        width: 100vw;
        padding: 0 4rem;
        gap: 1.5rem;
        height: 75vh;
        overflow-x: auto;
    }

    /* COLUMNAS ALINEADAS */
    .kanban-col {
        width: 310px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        border-right: 1px dashed rgba(255,255,255,0.05);
        padding-right: 1rem;
    }

    .col-header {
        background: linear-gradient(90deg, var(--stellar-blue) 0%, transparent 100%);
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        border-left: 5px solid var(--accent-sky);
        margin-bottom: 2rem;
        height: 50px; /* ALTURA FIJA PARA ALINEACION HORIZONTAL */
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-title { font-size: 0.75rem; font-weight: 900; letter-spacing: 0.2em; text-transform: uppercase; }

    /* TARJETAS SIMÉTRICAS - LEY DE HIERRO 130PX */
    .kanban-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color); 
        border-radius: 14px;
        padding: 1rem;
        margin-bottom: 15px; /* 4mm aprox */
        height: 130px; 
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s;
        overflow: hidden;
    }

    .card-handle { position: absolute; right: 12px; top: 12px; color: rgba(255,255,255,0.1); }

    .card-name { font-size: 0.85rem; font-weight: 950; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding-right: 20px; }
    .card-subtext { font-size: 0.6rem; color: #52525b; font-weight: 700; text-transform: uppercase; }

    /* BOTONES DENTRO DE TARJETA */
    .btn-group-card { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; }
    .btn-card { 
        background: rgba(255,255,255,0.03); 
        border: 1px solid rgba(255,255,255,0.1); 
        color: var(--accent-sky); 
        padding: 6px 0; 
        border-radius: 8px; 
        font-size: 0.55rem; 
        font-weight: 900; 
        text-align: center; 
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-card:hover:not(.disabled) { background: var(--accent-sky); color: #000; }
    .btn-card.disabled { opacity: 0.1; cursor: not-allowed; }

    .card-footer-label {
        font-size: 0.5rem;
        font-weight: 900;
        color: var(--accent-sky);
        letter-spacing: 1px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(255,255,255,0.05);
        padding-top: 6px;
    }

    /* MODAL IA - CENTRADO REAL */
    .modal-content.ai-card {
        background: #0c0c0e !important;
        border: 2px solid var(--accent-sky);
        border-radius: 24px;
        box-shadow: 0 0 100px rgba(56, 189, 248, 0.3);
    }

    .active-spotlight {
        border-color: var(--accent-sky) !important;
        box-shadow: 0 0 40px rgba(56, 189, 248, 0.5) !important;
        z-index: 10;
    }
</style>

<div class="header-hub">
    <h1 class="text-white text-2xl font-black uppercase tracking-[0.5em]">Command Hub</h1>
    <div class="h-px bg-zinc-800 flex-1 opacity-20"></div>
    <div class="flex items-center gap-4 text-sky-400 font-black text-[0.8rem] uppercase tracking-widest animate-pulse">
        <i class="bi bi-robot fs-4"></i> AGENTE SOCIAL LIVE
    </div>
</div>

<div class="crm-container custom-scrollbar" id="crmContainer">
    
    <!-- FASE 01 -->
    <div class="kanban-col">
        <div class="col-header">
            <span class="header-title">Fase 01 | Leads</span>
            <span class="text-white/40 text-[10px] font-black">{{ $prospectos->total() }}</span>
        </div>
        <div id="col-prospecto" class="kanban-list" data-status="prospecto">
            @foreach($prospectos as $pro)
            <div class="kanban-card" data-id="{{ $pro->id }}" id="card-{{ $pro->id }}">
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div>
                    <div class="card-name">{{ $pro->name }}</div>
                    <div class="card-subtext">{{ $pro->lead_source ?? 'Landing Directo' }}</div>
                </div>
                <div class="btn-group-card">
                    <button type="button" class="btn-card" onclick="openIA('{{ $pro->id }}', '{{ $pro->name }}', '{{ $pro->lead_source ?? 'INSTAGRAM' }}')">IA DATA</button>
                    <button type="button" class="btn-card disabled">MAIL</button>
                </div>
                <div class="card-footer-label">
                    <span>PROSPECTO OK</span>
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-3 text-[10px]">{{ $prospectos->links() }}</div>
    </div>

    <!-- FASE 02 -->
    <div class="kanban-col">
        <div class="col-header" style="border-left-color: var(--accent-amber)">
            <span class="header-title text-amber-500">Fase 02 | Validar</span>
        </div>
        <div id="col-pendiente_pago" class="kanban-list" data-status="pendiente_pago">
            @foreach($pendientes as $pen)
            <div class="kanban-card" data-id="{{ $pen->id }}" id="card-{{ $pen->id }}">
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div>
                    <div class="card-name">{{ $pen->name }}</div>
                    <div class="card-subtext text-amber-500/40">Validación de Pago</div>
                </div>
                <div class="btn-group-card">
                    @if($pen->payment_voucher)
                        <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="btn-card text-amber-500">VOUCHER</a>
                    @else
                        <span class="btn-card disabled">SIN DOC</span>
                    @endif
                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST" class="m-0 p-0 d-grid">
                        @csrf
                        <button type="submit" class="btn-card" style="color:var(--accent-amber);border-color:rgba(245,158,11,0.2);">ACTIVAR</button>
                    </form>
                </div>
                <div class="card-footer-label" style="color: var(--accent-amber)">
                    <span>PAGO PENDIENTE</span>
                    <i class="bi bi-lightning-fill"></i>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-3 text-[10px]">{{ $pendientes->links() }}</div>
    </div>

    <!-- FASE 03 -->
    <div class="kanban-col">
        <div class="col-header" style="border-left-color: var(--accent-emerald)">
            <span class="header-title text-emerald-500">Fase 03 | Activos</span>
        </div>
        <div id="col-activo" class="kanban-list" data-status="activo">
            @foreach($activos as $act)
            <div class="kanban-card" data-id="{{ $act->id }}" id="card-{{ $act->id }}">
                <div class="card-handle"><i class="bi bi-grip-vertical"></i></div>
                <div>
                    <div class="card-name text-zinc-300">{{ $act->name }}</div>
                    <div class="card-subtext truncate">{{ $act->empresa?->nombre_comercial ?? 'Setup Finalizado' }}</div>
                </div>
                <div class="btn-group-card">
                    <button class="btn-card" style="color:var(--accent-emerald);border-color:rgba(16,185,129,0.2);">PANEL</button>
                    <button class="btn-card disabled" style="color:var(--accent-emerald);border-color:rgba(16,185,129,0.2);">ESTATS</button>
                </div>
                <div class="card-footer-label" style="color: var(--accent-emerald)">
                    <span>EMPRESA ACTIVA</span>
                    <i class="bi bi-shield-check-fill"></i>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-3 text-[10px]">{{ $activos->links() }}</div>
    </div>

</div>

{{-- MODAL IA REAL --}}
<div class="modal fade" id="modalIA" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content ai-card">
      <div class="modal-header border-0 p-4 pb-0">
        <h6 class="modal-title text-sky-400 font-black uppercase tracking-widest"><i class="bi bi-robot me-2"></i> Reporte IA</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4 pt-2 font-monospace" style="font-size: 0.75rem;">
          <div class="mb-4 text-sky-500">>>> ANALIZANDO: <span id="ia-name" class="text-white"></span></div>
          <div class="bg-black/50 p-4 rounded-xl border border-white/5 shadow-inner">
                <div class="mb-2"><span class="text-zinc-600">CANAL:</span> <span id="ia-source" class="text-emerald-400"></span></div>
                <div class="mb-2 text-zinc-400">Mensaje detectado por el Agente Social Live: "Necesito un sistema para mi local."</div>
          </div>
          <div class="mt-4">
                <button type="button" class="btn-card w-full py-3 bg-sky-500 text-black border-0 fw-black" data-bs-dismiss="modal">
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
    let currentModal = null;
    let cardActive = null;

    function openIA(id, name, source) {
        cardActive = id;
        document.getElementById('card-'+id).classList.add('active-spotlight');
        document.getElementById('ia-name').innerText = name;
        document.getElementById('ia-source').innerText = source;
        
        if(!currentModal) currentModal = new bootstrap.Modal(document.getElementById('modalIA'));
        currentModal.show();
    }

    document.getElementById('modalIA').addEventListener('hidden.bs.modal', function () {
        if(cardActive) document.getElementById('card-'+cardActive).classList.remove('active-spotlight');
        document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
        document.body.style.overflow = 'auto';
    });

    document.addEventListener('DOMContentLoaded', function() {
        ['col-prospecto', 'col-pendiente_pago', 'col-activo'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                new Sortable(el, {
                    group: 'kanban',
                    handle: '.card-handle',
                    animation: 200,
                    onEnd: function(evt) {
                        fetch("{{ route('owner.crm.move') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ user_id: evt.item.getAttribute('data-id'), status: evt.to.getAttribute('data-status') })
                        });
                    }
                });
            }
        });
    });
</script>
@endsection
