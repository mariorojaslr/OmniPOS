@extends('layouts.empresa')

@section('content')

{{-- =========================================================
   ENCABEZADO
========================================================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0 fw-bold">Historial de Remitos</h2>
        <small class="text-muted">Documentos de entrega y control logístico</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('empresa.logistica.reporte') }}" class="btn btn-warning shadow-sm fw-bold">
            📊 Stock en Guarda
        </a>
        <a href="{{ route('empresa.ventas.index') }}" class="btn btn-outline-secondary">
            📋 Ver Ventas
        </a>
    </div>
</div>

{{-- =========================================================
   FILTROS
========================================================= --}}
<form method="GET" class="card mb-4 shadow-sm border-0 bg-light">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-9">
                <label class="form-label small fw-bold">Buscar por Número o Cliente</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control" placeholder="Escriba aquí para filtrar...">
            </div>
            <div class="col-md-3">
                <button class="btn btn-dark w-100">🔍 Buscar</button>
            </div>
        </div>
    </div>
</form>

{{-- =========================================================
   LISTADO
========================================================= --}}
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th width="150" class="ps-3 text-start">Fecha</th>
                        <th width="180" class="text-start">N° Remito</th>
                        <th class="text-start">Cliente</th>
                        <th width="180">Venta Asociada</th>
                        <th width="120" class="text-center">Formato</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($remitos as $remito)
                    <tr>
                        <td class="ps-3 text-start text-muted" style="font-size: 0.85rem;">
                            <span class="fw-bold text-dark">{{ $remito->created_at->format('d/m/Y') }}</span><br>
                            {{ $remito->created_at->format('H:i') }} hs
                        </td>
                        <td class="text-start align-middle">
                            <span class="fw-bold text-primary">{{ $remito->numero_remito ?: str_pad($remito->id, 8, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="text-start">
                            {{ optional($remito->cliente)->name ?? 'C. Final' }}
                        </td>
                        <td class="align-middle">
                            <a href="{{ route('empresa.ventas.show', $remito->venta_id) }}" class="badge bg-light text-dark border text-decoration-none">
                                Ver Venta #{{ $remito->venta_id }}
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('empresa.remitos.pdf', $remito->id) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-truck fs-1 d-block mb-3 opacity-25"></i>
                            Aún no se han generado documentos de entrega (Remitos).
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $remitos->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
