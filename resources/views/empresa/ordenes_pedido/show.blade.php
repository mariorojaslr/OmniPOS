@extends('layouts.empresa')

@section('styles')
<style>
    .op-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 16px;
        color: white;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .op-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        background: #fff;
    }
    .instruction-note {
        background-color: #f8fafc;
        border-left: 4px solid var(--color-primario);
        padding: 0.75rem 1rem;
        font-size: 0.8rem;
        color: #475569;
        margin-top: 0.5rem;
        border-radius: 0 8px 8px 0;
    }
    .stat-badge {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0.5rem 1rem;
        font-weight: 800;
    }
    .price-comparison-box {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 pb-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('empresa.ordenes-pedido.index') }}" class="btn btn-light btn-sm border px-3">
            <i class="bi bi-arrow-left me-1"></i> VOLVER AL LISTADO
        </a>
        <div class="d-flex gap-2">
            @if($orden->estado !== 'convertido')
                <form action="{{ route('empresa.ordenes-pedido.convertir', $orden->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success fw-bold px-4 shadow-sm">
                        <i class="bi bi-receipt me-2"></i> CONVERTIR A COMPRA REAL
                    </button>
                </form>
            @else
                <button class="btn btn-outline-success fw-bold px-4" disabled>
                    <i class="bi bi-check-circle-fill me-2"></i> ORDEN CONVERTIDA
                </button>
            @endif
            <button class="btn btn-primary fw-bold px-4" onclick="window.print()">
                <i class="bi bi-printer me-2"></i> IMPRIMIR / PDF
            </button>
        </div>
    </div>

    {{-- HEADER COMPACTO --}}
    <div class="d-flex align-items-center justify-content-between py-3 px-4 mb-4 rounded-3" style="background: linear-gradient(135deg, #1e293b, #334155); color: white;">
        <div class="d-flex align-items-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="fw-bold" style="font-size: 1.05rem; letter-spacing: -0.3px;">Orden de Pedido <span style="color: #94a3b8;">{{ $orden->numero }}</span></span>
                    @php
                        $badgeClass = match($orden->estado) {
                            'borrador'   => 'bg-warning text-dark',
                            'enviado'    => 'bg-info text-dark',
                            'convertido' => 'bg-success text-white',
                            'cancelado'  => 'bg-danger text-white',
                            default      => 'bg-secondary text-white'
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }} rounded-pill px-2 py-1" style="font-size: 0.6rem; text-transform: uppercase; letter-spacing: 1px;">{{ $orden->estado }}</span>
                </div>
                <div class="d-flex align-items-center gap-3" style="font-size: 0.78rem; opacity: 0.7;">
                    <span><i class="bi bi-truck me-1"></i>{{ $orden->proveedor->name }}</span>
                    <span><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</span>
                    <span><i class="bi bi-person me-1"></i>{{ $orden->user->name }}</span>
                </div>
            </div>
        </div>
        <div class="text-end">
            <div style="font-size: 0.65rem; opacity: 0.6; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px;">Total Estimado</div>
            <div class="fw-bold" style="font-size: 1.5rem; color: #4ade80;">$ {{ number_format($orden->total, 2, ',', '.') }}</div>
        </div>
    </div>

    <div class="row g-4">
        {{-- LISTA DE ARTÍCULOS --}}
        <div class="col-lg-8">
            <div class="card op-card">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-dark">Detalle de Requerimientos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-4">ARTÍCULO / INSTRUCCIONES</th>
                                    <th class="text-center">CANT.</th>
                                    <th class="text-end">P. UNIT.</th>
                                    <th class="text-end pe-4">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orden->items as $item)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark fs-6">{{ $item->descripcion }}</div>
                                        @if($item->product_id)
                                            <div class="x-small text-muted mb-1">SKU: {{ $item->product->sku ?? 'N/A' }}</div>
                                        @else
                                            <span class="badge-manual mb-1">Artículo Manual</span>
                                        @endif
                                        
                                        @if($item->instrucciones)
                                            <div class="instruction-note">
                                                <i class="bi bi-info-circle-fill me-1"></i>
                                                <strong>Instrucciones:</strong> {{ $item->instrucciones }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center fw-bold">{{ number_format($item->cantidad, 2, ',', '.') }}</td>
                                    <td class="text-end">
                                        <div>$ {{ number_format($item->precio_unitario, 2, ',', '.') }}</div>
                                        @if($item->precio_anterior && $item->precio_anterior > 0)
                                            @php
                                                $diff = $item->precio_unitario - $item->precio_anterior;
                                                $perc = ($diff / $item->precio_anterior) * 100;
                                            @endphp
                                            <div class="x-small {{ $diff > 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                                {{ $diff > 0 ? '▲' : '▼' }} {{ number_format(abs($perc), 1) }}% vs anterior
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4 fw-bold text-dark">$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- INFORMACIÓN ADICIONAL --}}
        <div class="col-lg-4">
            {{-- NOTAS GENERALES --}}
            <div class="card op-card mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold text-dark">Observaciones Generales</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="p-3 bg-light rounded-3 text-muted small" style="min-height: 100px;">
                        {{ $orden->notas_generales ?? 'Sin observaciones adicionales.' }}
                    </div>
                </div>
            </div>

            {{-- DATOS DEL PROVEEDOR --}}
            <div class="card op-card">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold text-dark">Datos del Proveedor</h6>
                </div>
                <div class="card-body pt-0 small">
                    <div class="mb-2"><strong>Razón Social:</strong> {{ $orden->proveedor->razon_social ?? $orden->proveedor->name }}</div>
                    <div class="mb-2"><strong>CUIT:</strong> {{ $orden->proveedor->cuit ?? '-' }}</div>
                    <div class="mb-2"><strong>Email:</strong> {{ $orden->proveedor->email ?? '-' }}</div>
                    <div class="mb-2"><strong>Teléfono:</strong> {{ $orden->proveedor->phone ?? '-' }}</div>
                    <div class="mt-3">
                        <a href="{{ route('empresa.proveedores.show', $orden->proveedor->id) }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                            VER FICHA / CTA. CTE.
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
