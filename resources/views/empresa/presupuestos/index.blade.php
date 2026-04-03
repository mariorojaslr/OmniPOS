@extends('layouts.empresa')

@section('styles')
<style>
/* ESTÉTICA OLED CENTRAL UNIFICADA */
body { background: #000 !important; }

.glass-card-oled {
    background: rgba(20, 20, 25, 0.7) !important;
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    padding: 1.5rem;
}

.stat-box-oled {
    background: #000;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 18px;
    padding: 1.2rem;
    text-align: center;
    transition: 0.3s;
}
.stat-box-oled:hover { border-color: var(--color-primario); box-shadow: 0 0 20px rgba(var(--color-primario-rgb), 0.2); }

.oled-title { color: #fff; font-weight: 900; letter-spacing: -1px; text-transform: uppercase; }
.oled-text-muted { color: rgba(255,255,255,0.4); font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; }

.table-oled { background: transparent !important; }
.table-oled thead th { 
    background: rgba(255,255,255,0.03); 
    color: #fff; 
    font-size: 0.65rem; 
    font-weight: 900; 
    text-transform: uppercase; 
    letter-spacing: 2px;
    border-bottom: 2px solid rgba(255,255,255,0.05);
    padding: 1.2rem;
}
.table-oled tbody td { 
    padding: 1.2rem; 
    color: #e5e7eb; 
    border-bottom: 1px solid rgba(255,255,255,0.03); 
}

.oled-badge {
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 0.6rem;
    font-weight: 950;
    text-transform: uppercase;
    letter-spacing: 1px;
}
</style>
@endsection

@section('content')
<div class="p-3">
    
    {{-- HEADER OLED --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="oled-title mb-1" style="font-size: 2.2rem;">Gestión de Presupuestos</h1>
            <p class="oled-text-muted mb-0">SEGUIMIENTO COMERCIAL EN TIEMPO REAL • MULTIPOS CENTRAL</p>
        </div>
        <div class="d-flex gap-3">
            <a href="{{ route('empresa.presupuestos.create') }}" class="btn btn-primary fw-black rounded-pill px-4 py-2 text-uppercase" style="font-size: 0.75rem;">
                <i class="bi bi-plus-lg me-2"></i> Nueva Cotización
            </a>
            <button class="btn btn-dark border-secondary fw-bold rounded-pill px-4 py-2" style="font-size: 0.75rem;">
                <i class="bi bi-file-earmark-pdf me-2"></i> Reportes
            </button>
        </div>
    </div>

    {{-- INDICADORES OLED --}}
    <div class="row g-4 mb-5">
        @foreach([
            ['L'=>'TOTAL EMITIDOS', 'V'=>$stats['total'], 'C'=>'#fff'],
            ['L'=>'PENDIENTES', 'V'=>$stats['pendientes'], 'C'=>'#f59e0b'],
            ['L'=>'ACEPTADOS', 'V'=>$stats['aceptados'], 'C'=>'#10b981'],
            ['L'=>'VENCIDOS', 'V'=>$stats['vencidos'], 'C'=>'#ef4444']
        ] as $s)
        <div class="col-md-3">
            <div class="stat-box-oled">
                <div class="oled-text-muted mb-2">{{ $s['L'] }}</div>
                <div class="fw-black fs-2" style="color: {{ $s['C'] }}">{{ $s['V'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- TABLA OLED --}}
    <div class="glass-card-oled p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-oled table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>REF #</th>
                        <th>CLIENTE / PROSPECTO</th>
                        <th>EMISIÓN</th>
                        <th>VENCIMIENTO</th>
                        <th class="text-end">TOTAL</th>
                        <th class="text-center">ESTADO</th>
                        <th class="text-end pe-4">GESTIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($presupuestos as $presu)
                    <tr>
                        <td><span class="fw-black text-white bg-white/5 px-3 py-1 rounded-pill">#{{ $presu->numero }}</span></td>
                        <td>
                            <div class="fw-bold text-white fs-6">{{ $presu->client->name ?? 'Cliente Ocasional' }}</div>
                            <div class="oled-text-muted small">{{ $presu->client->email ?? '-' }}</div>
                        </td>
                        <td class="small">{{ $presu->fecha ? $presu->fecha->format('d M, Y') : '-' }}</td>
                        <td class="small">
                            @if($presu->vencimiento && $presu->vencimiento < now() && $presu->estado == 'pendiente')
                                <span class="text-danger fw-bold">{{ $presu->vencimiento->format('d/m/Y') }}</span>
                            @else
                                {{ $presu->vencimiento ? $presu->vencimiento->format('d/m/Y') : '-' }}
                            @endif
                        </td>
                        <td class="text-end fw-black fs-5 text-success">$ {{ number_format($presu->total, 2, ',', '.') }}</td>
                        <td class="text-center">
                            @php
                                $badgeStyle = match($presu->estado) {
                                    'pendiente' => 'background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid #f59e0b;',
                                    'aceptado'  => 'background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid #10b981;',
                                    'vencido'   => 'background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid #ef4444;',
                                    default     => 'background: rgba(255,255,255,0.05); color: #fff; border: 1px solid #fff;'
                                };
                            @endphp
                            <span class="oled-badge" style="{{ $badgeStyle }}">{{ $presu->estado }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-dark border-secondary rounded-pill btn-sm px-4 fw-black text-uppercase" style="font-size: 0.6rem;">
                                Gestionar
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-folder2-open fs-1 text-white/5"></i>
                            <div class="oled-text-muted mt-3">NO HAY PRESUPUESTOS EMITIDOS HASTA EL MOMENTO</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

