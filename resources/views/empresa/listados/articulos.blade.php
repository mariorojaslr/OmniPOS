@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-3 px-4">

    {{-- CABECERA (Se oculta en impresión si fuera necesario, pero la dejamos pulcra) --}}
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <div>
            <h4 class="fw-bold text-dark mb-0">Listado Maestro de Artículos</h4>
            <p class="text-muted small mb-0">Total de productos activos: {{ $items->count() }}</p>
        </div>
        <button onclick="window.print()" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-printer me-2"></i> IMPRIMIR LISTADO
        </button>
    </div>

    {{-- LISTADO --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <table class="table table-hover table-sm align-middle mb-0 printable-table">
                <thead class="bg-light">
                    <tr class="x-small fw-bold text-muted text-uppercase ls-1">
                        <th class="ps-3 py-2" style="width: 50px;">O</th>
                        <th class="py-2">Producto / Descripción</th>
                        <th class="py-2">Rubro</th>
                        <th class="text-end py-2">Precio Venta</th>
                        <th class="text-end py-2 px-3">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i)
                    <tr>
                        <td class="ps-3">
                            <div class="check-box border rounded d-inline-block" style="width: 18px; height: 18px;"></div>
                        </td>
                        <td>
                            <div class="small fw-bold text-dark">{{ $i->name }}</div>
                            @if($i->barcode)<div class="x-small text-muted font-monospace">{{ $i->barcode }}</div>@endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border rounded-pill x-small px-2">
                                {{ $i->rubro->nombre ?? 'General' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <span class="small fw-bold text-dark">${{ number_format($i->price, 2, ',', '.') }}</span>
                        </td>
                        <td class="text-end px-3">
                            <span class="small fw-bold {{ $i->stock <= 0 ? 'text-danger' : 'text-primary' }}">
                                {{ number_format($i->stock, 2) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
    .x-small { font-size: 0.65rem; }
    .ls-1 { letter-spacing: 1px; }
    .check-box { border: 1px solid #ccc !important; }
    
    @media print {
        .no-print, .main-sidebar, .top-bar, .btn { display: none !important; }
        .content-wrapper { margin: 0 !important; padding: 0 !important; }
        .card { box-shadow: none !important; border: none !important; }
        body { background: white !important; }
        .printable-table { width: 100% !important; }
        .printable-table th { background-color: #f0f0f0 !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection
