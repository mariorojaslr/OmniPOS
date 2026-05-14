@extends('layouts.empresa')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: #0D6EFD;">Historias Clínicas</h2>
            <p class="text-muted small">Gestión de atenciones y registros médicos.</p>
        </div>
        <a href="{{ route('empresa.medical_records.create') }}" class="btn btn-primary px-4 fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> NUEVA ATENCIÓN
        </a>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
        <div class="card-header bg-white py-3">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-start-0" placeholder="Buscar paciente...">
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4">PACIENTE</th>
                        <th>ESPECIALIDAD</th>
                        <th>FECHA</th>
                        <th>MÉDICO</th>
                        <th class="text-end pe-4">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $record->patient->name }}</div>
                            <div class="text-muted small">{{ $record->patient->documento ?? 'Sin documento' }}</div>
                        </td>
                        <td><span class="badge bg-info bg-opacity-10 text-info px-3">{{ $record->specialty ?? 'General' }}</span></td>
                        <td>{{ $record->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $record->doctor->name }}</td>
                        <td class="text-end pe-4">
                            <a href="{{ route('empresa.medical_records.show', $record->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">VER DETALLE</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-file-earmark-medical text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 text-muted">No hay registros médicos cargados aún.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $records->links() }}
        </div>
    </div>
</div>
@endsection
