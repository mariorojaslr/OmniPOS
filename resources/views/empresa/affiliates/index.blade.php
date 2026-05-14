@extends('layouts.empresa')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: #16a34a;">Gestión de Afiliados</h2>
            <p class="text-muted small">Módulo de Plan Propio "Med Plus".</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalGenerateFees">
                <i class="bi bi-receipt me-2"></i> GENERAR CUOTAS MES
            </button>
            <a href="{{ route('empresa.clientes.index') }}" class="btn btn-success px-4 fw-bold shadow-sm">
                <i class="bi bi-person-plus me-2"></i> NUEVO AFILIADO
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4">AFILIADO</th>
                        <th>N° CARNET</th>
                        <th>ESTADO</th>
                        <th>CUOTA MENSUAL</th>
                        <th class="text-end pe-4">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($affiliates as $aff)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $aff->name }}</div>
                            <div class="text-muted small">Desde: {{ optional($aff->affiliate_since)->format('d/m/Y') ?? 'N/A' }}</div>
                        </td>
                        <td><span class="font-monospace fw-bold">{{ $aff->affiliate_number ?? 'PENDIENTE' }}</span></td>
                        <td>
                            @php
                                $statusColors = ['active' => 'success', 'inactive' => 'secondary', 'overdue' => 'danger'];
                                $color = $statusColors[$aff->affiliate_status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-3">{{ strtoupper($aff->affiliate_status) }}</span>
                        </td>
                        <td><div class="fw-bold text-success">${{ number_format($aff->monthly_fee, 2) }}</div></td>
                        <td class="text-end pe-4">
                            <a href="{{ route('empresa.affiliates.account', $aff->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">CTA. CTE.</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No hay afiliados registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $affiliates->links() }}
        </div>
    </div>
</div>

<!-- Modal Generar Cuotas -->
<div class="modal fade" id="modalGenerateFees" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generación Masiva de Cuotas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('empresa.affiliates.generate_fees') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Periodo (Año-Mes)</label>
                        <input type="month" name="period" class="form-control" value="{{ date('Y-m') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de Vencimiento</label>
                        <input type="date" name="due_date" class="form-control" value="{{ date('Y-m-10') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-modal="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold">PROCESAR FACTURACIÓN</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
