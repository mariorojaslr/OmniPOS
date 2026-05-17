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
                <i class="bi bi-receipt me-1"></i> GENERAR CUOTAS
            </button>
            <a href="{{ route('empresa.affiliates.export') }}" class="btn btn-outline-primary fw-bold">
                <i class="bi bi-file-earmark-excel me-1"></i> PLANTILLA / EXPORTAR
            </a>
            <button class="btn btn-light fw-bold" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
                <i class="bi bi-cloud-arrow-up me-1"></i> IMPORTAR EXCEL
            </button>
            <a href="{{ route('empresa.affiliates.create') }}" class="btn btn-success px-4 fw-bold shadow-sm">
                <i class="bi bi-person-plus me-1"></i> NUEVO AFILIADO
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4">AFILIADO</th>
                        <th>DOCUMENTO / PLAN</th>
                        <th>OBRA SOCIAL</th>
                        <th>ESTADO</th>
                        <th>CUOTA</th>
                        <th class="text-end pe-4">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($affiliates as $aff)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                @if($aff->photo_path)
                                    @if(str_starts_with($aff->photo_path, 'local:'))
                                        <img src="{{ asset('storage/' . str_replace('local:', '', $aff->photo_path)) }}" class="rounded-circle me-3" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #16a34a;">
                                    @elseif(str_starts_with($aff->photo_path, 'bunny:'))
                                        <img src="{{ config('filesystems.disks.bunny_storage.url') }}/{{ str_replace('bunny:', '', $aff->photo_path) }}" class="rounded-circle me-3" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #16a34a;">
                                    @else
                                        <img src="{{ config('filesystems.disks.bunny_storage.url') }}/{{ $aff->photo_path }}" class="rounded-circle me-3" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #16a34a;">
                                    @endif
                                @else
                                    <div class="rounded-circle me-3 bg-light d-flex align-items-center justify-content-center text-muted" style="width: 45px; height: 45px; font-weight: bold;">{{ substr($aff->name, 0, 1) }}</div>
                                @endif
                                <div>
                                    <a href="{{ route('empresa.affiliates.show', $aff->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">{{ $aff->name }}</a>
                                    <div class="text-muted small">ID: {{ $aff->affiliate_number ?? 'S/N' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $aff->document }}</div>
                            <div class="text-success small fw-bold">{{ $aff->affiliatePlan->name ?? 'Sin Plan' }}</div>
                        </td>
                        <td>
                            @if($aff->obraSocial)
                                <div class="small fw-bold">{{ $aff->obraSocial->nombre }}</div>
                                <div class="text-muted extra-small">CUI: {{ $aff->obraSocial->cuit ?? 'N/A' }}</div>
                            @else
                                <span class="text-muted small">Ninguna</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusColors = ['active' => 'success', 'inactive' => 'secondary', 'overdue' => 'danger'];
                                $color = $statusColors[$aff->affiliate_status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-3 rounded-pill">{{ strtoupper($aff->affiliate_status) }}</span>
                        </td>
                        <td><div class="fw-bold text-dark">${{ number_format($aff->monthly_fee ?? ($aff->affiliatePlan->price ?? 0), 2) }}</div></td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('empresa.affiliates.account', $aff->id) }}" class="btn btn-sm btn-outline-secondary px-3">Cuenta</a>
                                <a href="{{ route('empresa.affiliates.edit', $aff->id) }}" class="btn btn-sm btn-outline-success px-3">Editar</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">No hay afiliados registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $affiliates->links() }}
        </div>
    </div>
</div>

<!-- Modal Importar Excel -->
<div class="modal fade" id="modalImportExcel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Importar Afiliados desde Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('empresa.affiliates.import_excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 text-center">
                    <p class="text-muted small mb-4">Descarga la plantilla patrón, complétala y súbela aquí para importar de forma masiva.</p>
                    <a href="#" class="btn btn-light rounded-pill mb-4"><i class="bi bi-download me-2"></i> Descargar Plantilla Patrón</a>
                    
                    <div class="mb-3 text-start">
                        <label class="form-label text-muted small fw-bold">Seleccionar Archivo Excel</label>
                        <input type="file" name="excel_file" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Procesar Importación</button>
                </div>
            </form>
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
