@extends('layouts.owner')

@section('content')
<div class="mb-12 px-6">
    <div class="flex items-center justify-between">
        <div class="animate-fade-in-left">
            <h1 class="text-5xl font-black text-slate-800 tracking-tighter">Command <span class="text-indigo-600">Center</span> CRM</h1>
            <p class="text-slate-400 mt-2 uppercase text-[12px] tracking-[0.4em] font-bold opacity-60">Gestión de Prospectos y Activaciones VIP</p>
        </div>
        <div class="bg-indigo-600 text-white px-6 py-3 rounded-2xl shadow-xl shadow-indigo-200 animate-bounce-subtle">
            <span class="font-black text-xs uppercase tracking-widest">Master Control v2.0</span>
        </div>
    </div>
</div>

{{-- KANBAN CONTAINER --}}
<div class="bg-slate-900 p-10 rounded-[4rem] border-8 border-slate-800 shadow-[inset_0_10px_50px_rgba(0,0,0,0.5)] min-h-[90vh]">
    <div class="flex space-x-12 overflow-x-auto pb-12 custom-scrollbar px-4">
        
        <!-- COLUMNA 1: PROSPECTOS -->
        <div class="flex-shrink-0 w-[24rem]">
            <div class="mb-10 flex justify-between items-center px-6">
                <div class="flex flex-col">
                    <span class="text-indigo-400 font-black text-xs uppercase tracking-[0.3em] mb-1">Fase 1</span>
                    <h2 class="font-black text-white text-lg tracking-tight">Nuevos Leads</h2>
                </div>
                <span class="bg-white/10 text-indigo-400 text-xs font-black px-4 py-2 rounded-2xl border border-white/5">{{ $prospectos->total() }}</span>
            </div>
            
            <div id="col-prospecto" class="kanban-column space-y-6 min-h-[600px]" data-status="prospecto">
                @foreach($prospectos as $pro)
                    <div class="kanban-card floating-tag bg-white p-6 rounded-[2.5rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] hover:shadow-indigo-500/20 border-2 border-transparent hover:border-indigo-400 transition-all duration-500 cursor-grab active:cursor-grabbing transform hover:-translate-y-3" data-id="{{ $pro->id }}">
                        <div class="flex justify-between items-center mb-5">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-lg">#</div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest opacity-60">{{ $pro->created_at ? $pro->created_at->diffForHumans() : 'Reciente' }}</span>
                        </div>
                        <h3 class="font-black text-slate-800 text-md mb-2 leading-none uppercase tracking-tight">{{ $pro->name }}</h3>
                        <p class="text-[11px] text-slate-400 font-bold mb-6 opacity-80">{{ $pro->email }}</p>
                        
                        <div class="flex items-center justify-between pt-5 border-t border-slate-50">
                            <span class="text-[10px] bg-slate-100 text-slate-500 px-3 py-1 rounded-full font-black uppercase tracking-wider">{{ $pro->lead_source ?? 'DIRECTO' }}</span>
                            <span class="text-[11px] font-black text-indigo-600">AR <i class="bi bi-geo-alt-fill ms-1"></i></span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $prospectos->appends(['pendientes' => $pendientes->currentPage(), 'activos' => $activos->currentPage()])->links() }}
            </div>
        </div>

        <!-- COLUMNA 2: PAGO PENDIENTE -->
        <div class="flex-shrink-0 w-[24rem]">
            <div class="mb-10 flex justify-between items-center px-6">
                <div class="flex flex-col">
                    <span class="text-amber-400 font-black text-xs uppercase tracking-[0.3em] mb-1">Fase 2</span>
                    <h2 class="font-black text-white text-lg tracking-tight">Validar Pago</h2>
                </div>
                <span class="bg-amber-500 text-black text-xs font-black px-4 py-2 rounded-2xl shadow-lg shadow-amber-500/20">{{ $pendientes->total() }}</span>
            </div>
            
            <div id="col-pendiente_pago" class="kanban-column space-y-6 min-h-[600px]" data-status="pendiente_pago">
                @foreach($pendientes as $pen)
                    <div class="kanban-card floating-tag bg-white p-6 rounded-[2.5rem] shadow-[0_25px_50px_-12px_rgba(251,191,36,0.3)] border-4 border-amber-200 hover:border-amber-400 transition-all duration-500 cursor-grab active:cursor-grabbing transform hover:-translate-y-3" data-id="{{ $pen->id }}">
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-14 h-14 rounded-3xl bg-amber-500 text-white flex items-center justify-center shadow-xl shadow-amber-500/30">
                                <i class="bi bi-wallet2 fs-2"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-slate-800 text-md leading-none uppercase tracking-tighter">{{ $pen->name }}</h3>
                                <div class="mt-2 text-[10px] font-black text-amber-600 uppercase tracking-widest">PENDIENTE DE VALIDACIÓN</div>
                            </div>
                        </div>

                        @if($pen->payment_voucher)
                            <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="block w-full bg-slate-900 text-white text-[11px] font-black py-4 rounded-2xl mb-4 text-center hover:bg-amber-500 transition-colors uppercase tracking-[0.2em] shadow-xl">
                                REVISAR VOUCHER <i class="bi bi-eye-fill ms-2"></i>
                            </a>
                        @endif

                        <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('¿Confirmas este pago?')" 
                                    class="w-full bg-indigo-600 text-white text-[11px] font-black py-5 rounded-3xl hover:bg-black transition-all shadow-2xl active:scale-95 uppercase tracking-[0.25em]">
                                <i class="bi bi-check-circle-fill me-2 fs-5"></i> ACTIVAR CLIENTE
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $pendientes->appends(['prospectos' => $prospectos->currentPage(), 'activos' => $activos->currentPage()])->links() }}
            </div>
        </div>

        <!-- COLUMNA 3: ACTIVOS -->
        <div class="flex-shrink-0 w-[24rem]">
            <div class="mb-10 flex justify-between items-center px-6">
                <div class="flex flex-col">
                    <span class="text-emerald-400 font-black text-xs uppercase tracking-[0.3em] mb-1">Fase 3</span>
                    <h2 class="font-black text-white text-lg tracking-tight">Activos OK</h2>
                </div>
                <div class="flex items-center gap-2 bg-emerald-500/20 px-4 py-2 rounded-2xl border border-emerald-500/30">
                    <span class="text-emerald-400 text-xs font-black">{{ $activos->total() }}</span>
                </div>
            </div>
            
            <div id="col-activo" class="kanban-column space-y-6 min-h-[600px]" data-status="activo">
                @foreach($activos as $act)
                    <div class="kanban-card floating-tag bg-slate-800 p-6 rounded-[2.5rem] shadow-2xl border-2 border-emerald-500/20 hover:border-emerald-500 transition-all duration-500 flex items-center gap-5 cursor-grab group" data-id="{{ $act->id }}">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-2xl font-black group-hover:bg-emerald-500 group-hover:text-white transition-all">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div class="flex-1 truncate">
                            <h3 class="font-black text-white text-sm uppercase tracking-tight">{{ $act->name }}</h3>
                            <p class="text-[10px] text-emerald-400 font-bold uppercase tracking-widest opacity-80 mt-1">{{ $act->empresa?->nombre_comercial ?? 'SaaS Configurado' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $activos->appends(['prospectos' => $prospectos->currentPage(), 'pendientes' => $pendientes->currentPage()])->links() }}
            </div>
        </div>

    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 12px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.03); border-radius: 20px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(99, 102, 241, 0.3); border-radius: 20px; border: 4px solid #0f172a; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(99, 102, 241, 0.6); }

    .animate-fade-in-left { animation: fadeInRight 0.8s ease-out; }
    @keyframes fadeInRight { from { opacity:0; transform: translateX(-30px); } to { opacity:1; transform: translateX(0); } }

    .animate-bounce-subtle { animation: bounceSubtle 3s infinite ease-in-out; }
    @keyframes bounceSubtle { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }

    .floating-tag { position: relative; z-index: 10; }
    .floating-tag:hover { z-index: 50; }
    
    .sortable-ghost { opacity: 0.2; transform: scale(0.9); filter: blur(5px); }
    .sortable-chosen { box-shadow: 0 40px 80px rgba(99, 102, 241, 0.4) !important; cursor: grabbing !important; }
</style>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('CRM Command Center Inicializado...');
        
        const columnIds = ['col-prospecto', 'col-pendiente_pago', 'col-activo'];
        
        columnIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                new Sortable(el, {
                    group: 'kanban',
                    animation: 300,
                    easing: "cubic-bezier(0.165, 0.84, 0.44, 1)",
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    onEnd: function(evt) {
                        const userId = evt.item.getAttribute('data-id');
                        const newStatus = evt.to.getAttribute('data-status');
                        
                        if (evt.from !== evt.to) {
                            console.log('Moviendo Usuario: ' + userId + ' a ' + newStatus);
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
                if (data.success) {
                    console.log('✅ ' + data.message);
                } else {
                    alert('⚠️ Error de Red: Recargando...');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('❌ Error Crítico:', error);
                location.reload();
            });
        }
    });
</script>
@endsection
