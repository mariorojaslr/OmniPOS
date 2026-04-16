@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item small"><a href="{{ route('empresa.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item small active text-primary" aria-current="page">Proveedores</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-1 text-dark">Gestión de Proveedores</h2>
            <p class="text-muted small mb-0">Administra tu cartera de proveedores y sus estados de cuenta.</p>
        </div>

        <a href="{{ route('empresa.proveedores.create') }}" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm">
            <i class="fas fa-plus me-1"></i> Nuevo Proveedor
        </a>
    </div>

    {{-- LISTADO --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-muted text-uppercase small fw-bold">
                        <th class="ps-4 py-3">Razón Social</th>
                        <th>Contacto</th>
                        <th>Identificación</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($suppliers as $s)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">
                                    {{ substr($s->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">{{ $s->name }}</h6>
                                    <small class="text-muted">ID: #{{ $s->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small"><i class="fas fa-envelope me-1 text-muted"></i> {{ $s->email ?: 'Sin email' }}</div>
                            <div class="small"><i class="fas fa-phone me-1 text-muted"></i> {{ $s->phone ?: 'Sin teléfono' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border rounded-pill px-3">{{ $s->document ?: 'S/D' }}</span>
                        </td>
                        <td class="text-center">
                            @if($s->active)
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Activo</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('empresa.proveedores.show', $s->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">
                                    <i class="fas fa-file-invoice-dollar me-1"></i> Cuenta Corriente
                                </a>
                                @if($s->lat && $s->lng)
                                    <a href="https://www.google.com/maps?q={{ $s->lat }},{{ $s->lng }}" target="_blank" class="btn btn-sm btn-dark rounded-pill px-3 fw-bold">
                                        📍 GPS
                                    </a>
                                @endif
                                <a href="{{ route('empresa.proveedores.edit', $s->id) }}" class="btn btn-sm btn-light border rounded-pill px-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
        <div class="card-footer bg-white border-0 p-4">
            {{ $suppliers->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
