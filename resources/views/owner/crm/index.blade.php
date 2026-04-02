@extends('layouts.owner')

@section('content')
<div class="mb-10 px-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Pipeline Comercial <span class="text-indigo-600">MultiPOS</span></h1>
            <p class="text-gray-500 mt-2 uppercase text-[10px] tracking-[0.2em] font-bold opacity-75">Gestión de Prospectos, Pagos y Activaciones en tiempo real</p>
        </div>
        <div class="flex space-x-3">
            <div class="flex items-center space-x-2 bg-indigo-50 border border-indigo-100 px-4 py-2 rounded-2xl shadow-sm">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-500"></span>
                </span>
                <span class="text-indigo-700 text-xs font-bold uppercase tracking-wider">Modo Comando Activo</span>
            </div>
        </div>
    </div>
</div>

{{-- BACKGROUND MAESTRO --}}
<div class="bg-gray-100/50 p-6 rounded-[2.5rem] border border-gray-200/60 shadow-inner min-h-[80vh]">
    <div class="flex space-x-8 overflow-x-auto pb-10 custom-scrollbar px-2">
        
        <!-- COLUMNA 1: PROSPECTOS -->
        <div class="flex-shrink-0 w-[24rem]">
            <div class="mb-6 flex justify-between items-center px-2">
                <h2 class="font-black text-gray-600 uppercase text-[11px] tracking-[0.25em]">01. Nuevos Leads</h2>
                <div class="h-1 w-20 bg-indigo-400 rounded-full opacity-30"></div>
                <span class="bg-white border border-gray-200 text-gray-700 text-[10px] font-black px-3 py-1 rounded-full shadow-sm">{{ $prospectos->total() }}</span>
            </div>
            
            <div id="col-prospecto" class="space-y-4 min-h-[600px] transition-all duration-300" data-status="prospecto">
                @foreach($prospectos as $pro)
                    <div class="kanban-card bg-white p-5 rounded-3xl shadow-sm border border-transparent hover:border-indigo-400/50 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 cursor-grab active:cursor-grabbing" data-id="{{ $pro->id }}">
                        <div class="flex justify-between items-start mb-4">
                            <i class="bi bi-person-circle text-gray-300 fs-4"></i>
                            <span class="text-[9px] bg-gray-50 text-gray-400 px-2 py-1 rounded-full font-bold border border-gray-100 uppercase tracking-tighter">{{ $pro->created_at ? $pro->created_at->diffForHumans() : 'Reciente' }}</span>
                        </div>
                        <h3 class="font-bold text-gray-800 text-sm mb-1 leading-tight">{{ $pro->name }}</h3>
                        <p class="text-[11px] text-gray-400 truncate font-medium mb-4">{{ $pro->email }}</p>
                        
                        <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                            <div class="flex items-center text-[10px] text-indigo-500 font-bold uppercase tracking-wider">
                                <i class="bi bi-lightning-charge-fill me-1"></i> {{ $pro->lead_source ?? 'Landing' }}
                            </div>
                            <span class="text-[9px] bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full font-bold">{{ $pro->country ?? 'AR' }}</span>
                        </div>
                    </div>
                @endforeach
                @if($prospectos->isEmpty())
                    <div class="text-center py-20 bg-white/30 border-2 border-dashed border-gray-200 rounded-3xl opacity-40 italic text-xs text-gray-500">Zona de Captación Limpia</div>
                @endif
            </div>

            <div class="mt-6 px-4">
                {{ $prospectos->appends(['pendientes' => $pendientes->currentPage(), 'activos' => $activos->currentPage()])->links() }}
            </div>
        </div>

        <!-- COLUMNA 2: PENDIENTE DE PAGO -->
        <div class="flex-shrink-0 w-[24rem]">
            <div class="mb-6 flex justify-between items-center px-2">
                <h2 class="font-black text-amber-600 uppercase text-[11px] tracking-[0.25em]">02. Validar Pago</h2>
                <div class="h-1 w-20 bg-amber-400 rounded-full opacity-30"></div>
                <span class="bg-amber-600 text-white text-[10px] font-black px-3 py-1 rounded-full shadow-lg shadow-amber-200">{{ $pendientes->total() }}</span>
            </div>
            
            <div id="col-pendiente_pago" class="space-y-4 min-h-[600px]" data-status="pendiente_pago">
                @foreach($pendientes as $pen)
                    <div class="kanban-card bg-amber-50/50 p-6 rounded-3xl shadow-md border-2 border-amber-200 hover:border-amber-400 hover:shadow-2xl hover:shadow-amber-500/10 transition-all duration-300 cursor-grab active:cursor-grabbing" data-id="{{ $pen->id }}">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-2xl bg-amber-200 flex items-center justify-center text-amber-700">
                                <i class="bi bi-wallet2 fs-5"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-gray-800 text-sm leading-none">{{ $pen->name }}</h3>
                                <p class="text-[10px] text-amber-600 font-bold mt-1 uppercase tracking-tight">Voucher Recibido</p>
                            </div>
                        </div>
                        
                        @if($pen->payment_voucher)
                            <div class="mb-4">
                                <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="block bg-white rounded-2xl p-3 text-center text-xs text-indigo-700 font-black hover:bg-black hover:text-white border border-amber-200 shadow-sm transition-all duration-300 uppercase tracking-widest">
                                    <i class="bi bi-image me-2"></i> REVISAR PAGO
                                </a>
                            </div>
                        @endif

                        <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('¿Confirmas el pago?')" 
                                    class="w-full bg-black text-white text-[11px] font-black py-4 rounded-2xl hover:bg-amber-600 shadow-lg shadow-black/5 transition-all active:scale-95 text-uppercase tracking-[0.1em]">
                                <i class="bi bi-check-all me-2 fs-5"></i> ACTIVAR AHORA
                            </button>
                        </form>
                    </div>
                @endforeach
                @if($pendientes->isEmpty())
                    <div class="text-center py-20 bg-white/20 border-2 border-dashed border-amber-200/50 rounded-3xl opacity-40 italic text-xs text-amber-800">Esperando comprobantes...</div>
                @endif
            </div>

            <div class="mt-6 px-4">
                {{ $pendientes->appends(['prospectos' => $prospectos->currentPage(), 'activos' => $activos->currentPage()])->links() }}
            </div>
        </div>

        <!-- COLUMNA 3: ACTIVOS RECIENTES -->
        <div class="flex-shrink-0 w-[24rem]">
            <div class="mb-6 flex justify-between items-center px-2">
                <h2 class="font-black text-emerald-600 uppercase text-[11px] tracking-[0.25em]">03. Activos OK</h2>
                <div class="h-1 w-20 bg-emerald-400 rounded-full opacity-30"></div>
                <span class="bg-emerald-600 text-white text-[10px] font-black px-3 py-1 rounded-full shadow-lg shadow-emerald-200">{{ $activos->total() }}</span>
            </div>
            
            <div id="col-activo" class="space-y-4 min-h-[600px]" data-status="activo">
                @foreach($activos as $act)
                    <div class="kanban-card bg-white p-5 rounded-3xl shadow-sm border border-emerald-100 flex items-center justify-between group hover:bg-emerald-50/30 transition-all duration-300 cursor-grab" data-id="{{ $act->id }}">
                        <div class="flex-1 truncate">
                            <h3 class="font-black text-gray-800 text-xs">{{ $act->name }}</h3>
                            <p class="text-[10px] text-emerald-600 font-bold truncate opacity-80 uppercase tracking-tighter">{{ $act->empresa?->nombre_comercial ?? 'Configurando Empresa' }}</p>
                            <small class="text-[9px] text-gray-300 font-medium">ID: #{{ str_pad($act->id, 5, '0', STR_PAD_LEFT) }}</small>
                        </div>
                        <div class="ms-3 bg-emerald-50 text-emerald-500 rounded-2xl p-2.5 transition-colors group-hover:bg-emerald-600 group-hover:text-white">
                            <i class="bi bi-shield-check fs-5"></i>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 px-4">
                {{ $activos->appends(['prospectos' => $prospectos->currentPage(), 'pendientes' => $pendientes->currentPage()])->links() }}
            </div>
        </div>

    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 10px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; border: 3px solid #f9fafb; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    .kanban-card.sortable-ghost { opacity: 0.3; transform: scale(0.95); }
    .kanban-card.sortable-chosen { cursor: grabbing; box-shadow: 0 30px 60px -12px rgba(50, 50, 93, 0.25); }
</style>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columns = ['prospecto', 'pendiente_pago', 'activo'];
        
        columns.forEach(status => {
            const el = document.getElementById('col-' + status);
            new Sortable(el, {
                group: 'kanban',
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
                if (data.success) {
                    console.log('Movimiento exitoso: ' + data.message);
                } else {
                    alert('Error al mover usuario');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                location.reload();
            });
        }
    });
</script>
@endsection
