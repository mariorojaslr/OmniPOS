@extends('layouts.owner')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pipeline de Ventas (CRM)</h1>
            <p class="text-gray-500 mt-1 uppercase text-xs tracking-widest font-semibold opacity-75">Control de Prospectos y Activaciones Manuales</p>
        </div>
        <div class="flex space-x-2">
            <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-pill text-xs font-bold border border-indigo-200">
                <i class="bi bi-shield-check me-1"></i> Modo Seguro Activo
            </span>
        </div>
    </div>
</div>

<div class="flex space-x-6 overflow-x-auto pb-10 custom-scrollbar">
    
    <!-- COLUMNA 1: PROSPECTOS -->
    <div class="flex-shrink-0 w-80">
        <div class="bg-gray-100 p-4 rounded-t-2xl border border-gray-200 border-b-2 border-b-indigo-400 flex justify-between items-center">
            <h2 class="font-bold text-gray-600 uppercase text-[10px] tracking-widest">1. Nuevos Leads</h2>
            <span class="bg-gray-500 text-white text-[10px] px-2 py-0.5 rounded-full shadow-sm">{{ $prospectos->count() }}</span>
        </div>
        <div class="bg-gray-50/50 p-4 rounded-b-2xl border-x border-b border-gray-200 min-h-[600px] space-y-4">
            @foreach($prospectos as $pro)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:border-indigo-300 transition-all duration-300 cursor-default">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-pill font-bold border border-indigo-100">{{ $pro->country ?? 'AR' }}</span>
                        <span class="text-[9px] text-gray-400">{{ $pro->created_at->diffForHumans() }}</span>
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm mb-1">{{ $pro->name }}</h3>
                    <p class="text-[11px] text-gray-400 truncate mb-3">{{ $pro->email }}</p>
                    
                    <div class="pt-3 border-t border-gray-50 flex items-center text-[10px] text-gray-400">
                        <i class="bi bi-lightning-fill text-yellow-500 me-2"></i> 
                        Origen: <span class="text-gray-600 font-bold ms-1">{{ $pro->lead_source ?? 'Landing' }}</span>
                    </div>
                </div>
            @endforeach
            @if($prospectos->isEmpty())
                <div class="text-center py-10 opacity-30 italic text-sm">No hay prospectos fríos</div>
            @endif
        </div>
    </div>

    <!-- COLUMNA 2: PENDIENTE DE PAGO (VOUCHERS) -->
    <div class="flex-shrink-0 w-80">
        <div class="bg-amber-100 p-4 rounded-t-2xl border border-amber-200 border-b-2 border-b-amber-500 flex justify-between items-center text-amber-800">
            <h2 class="font-bold uppercase text-[10px] tracking-widest">2. Validar Pago</h2>
            <span class="bg-amber-600 text-white text-[10px] px-2 py-0.5 rounded-full shadow-sm">{{ $pendientes->count() }}</span>
        </div>
        <div class="bg-amber-50/30 p-4 rounded-b-2xl border-x border-b border-amber-200 min-h-[600px] space-y-4">
            @foreach($pendientes as $pen)
                <div class="bg-white p-5 rounded-xl shadow-md border-2 border-amber-200 hover:shadow-lg transition-all duration-300">
                    <h3 class="font-bold text-gray-800 text-sm mb-1">{{ $pen->name }}</h3>
                    <p class="text-[10px] text-amber-600 font-bold mb-4 uppercase tracking-tighter">{{ $pen->crm_notes ?? 'Plan Premium Seleccionado' }}</p>
                    
                    @if($pen->payment_voucher)
                        <div class="mb-4">
                            <a href="{{ asset('storage/' . $pen->payment_voucher) }}" target="_blank" class="block bg-indigo-50 rounded-xl p-3 text-center text-xs text-indigo-700 font-bold hover:bg-indigo-100 border border-indigo-200 shadow-sm transition">
                                <i class="bi bi-file-earmark-image me-2 fs-6"></i> REVISAR COMPROBANTE
                            </a>
                        </div>
                    @endif

                    <form action="{{ route('owner.crm.activate', $pen->id) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('¿Confirmas que el pago es válido? Esto le dará acceso total inmediato.')" 
                                class="w-full bg-black text-white text-[11px] font-bold py-3 rounded-xl hover:bg-gray-800 shadow-md transition-all active:scale-95 text-uppercase">
                            <i class="bi bi-check2-circle me-2"></i> Activar Empresa
                        </button>
                    </form>
                </div>
            @endforeach
            @if($pendientes->isEmpty())
                <div class="text-center py-10 opacity-30 italic text-sm">Sin comprobantes por revisar</div>
            @endif
        </div>
    </div>

    <!-- COLUMNA 3: ACTIVOS RECIENTES -->
    <div class="flex-shrink-0 w-80">
        <div class="bg-emerald-100 p-4 rounded-t-2xl border border-emerald-200 border-b-2 border-b-emerald-500 flex justify-between items-center text-emerald-800">
            <h2 class="font-bold uppercase text-[10px] tracking-widest">3. Activos Recientes</h2>
            <span class="bg-emerald-600 text-white text-[10px] px-2 py-0.5 rounded-full shadow-sm">{{ $activos->count() }}</span>
        </div>
        <div class="bg-emerald-50/20 p-4 rounded-b-2xl border-x border-b border-emerald-200 min-h-[600px] space-y-4">
            @foreach($activos as $act)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100 flex items-center justify-between hover:bg-emerald-50/20 transition">
                    <div class="flex-1 truncate">
                        <h3 class="font-bold text-gray-800 text-xs">{{ $act->name }}</h3>
                        <p class="text-[10px] text-gray-400 font-medium truncate">{{ $act->empresa->nombre_comercial ?? 'Configurando empresa...' }}</p>
                    </div>
                    <div class="ms-3 bg-emerald-100 text-emerald-600 rounded-full p-1.5 leading-none">
                        <i class="bi bi-check-lg fs-6"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .text-pill { border-radius: 50px; }
</style>
@endsection
