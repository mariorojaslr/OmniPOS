@extends('layouts.owner')

@section('content')
<div class="container-fluid py-4" style="padding-left: 30px; padding-right: 30px;">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="letter-spacing: -1px;">Gestión de Revendedores</h2>
            <p class="text-secondary mb-0">Control de red de agentes comerciales y partners.</p>
        </div>
        <a href="{{ route('owner.resellers.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
            <i class="bi bi-plus-lg me-2"></i> Nuevo Revendedor
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-success bg-opacity-10 text-success border-success border-opacity-25 rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-danger border-opacity-25 rounded-4 mb-4">
            <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif

    <!-- TABLA DE REVENDEDORES -->
    <div class="card bg-transparent border-0">
        <div class="card-body p-0">
            <div class="table-responsive rounded-4" style="border: 1px solid rgba(255,255,255,0.05); background: rgba(255,255,255,0.02);">
                <table class="table table-dark table-hover mb-0" style="background: transparent;">
                    <thead>
                        <tr>
                            <th class="border-bottom border-secondary border-opacity-25 text-uppercase" style="font-size: 11px; letter-spacing: 1px; color: #64748b; padding: 15px 20px;">Revendedor / Partner</th>
                            <th class="border-bottom border-secondary border-opacity-25 text-uppercase" style="font-size: 11px; letter-spacing: 1px; color: #64748b; padding: 15px 20px;">Email</th>
                            <th class="border-bottom border-secondary border-opacity-25 text-uppercase text-center" style="font-size: 11px; letter-spacing: 1px; color: #64748b; padding: 15px 20px;">Empresas Asignadas</th>
                            <th class="border-bottom border-secondary border-opacity-25 text-uppercase text-center" style="font-size: 11px; letter-spacing: 1px; color: #64748b; padding: 15px 20px;">Estado</th>
                            <th class="border-bottom border-secondary border-opacity-25 text-uppercase text-end" style="font-size: 11px; letter-spacing: 1px; color: #64748b; padding: 15px 20px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resellers as $reseller)
                            <tr>
                                <td class="align-middle border-bottom border-secondary border-opacity-10" style="padding: 15px 20px;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary" style="width: 40px; height: 40px; font-weight: bold;">
                                            {{ substr($reseller->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $reseller->name }}</h6>
                                            <small class="text-secondary">Se unió: {{ $reseller->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle border-bottom border-secondary border-opacity-10" style="padding: 15px 20px;">
                                    {{ $reseller->email }}
                                </td>
                                <td class="align-middle border-bottom border-secondary border-opacity-10 text-center" style="padding: 15px 20px;">
                                    @php
                                        $empresasCount = \App\Models\Empresa::where('reseller_id', $reseller->id)->count();
                                    @endphp
                                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fs-6">{{ $empresasCount }}</span>
                                </td>
                                <td class="align-middle border-bottom border-secondary border-opacity-10 text-center" style="padding: 15px 20px;">
                                    @if($reseller->activo)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1">Activo</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1">Inactivo</span>
                                    @endif
                                </td>
                                <td class="align-middle border-bottom border-secondary border-opacity-10 text-end" style="padding: 15px 20px;">
                                    <div class="d-flex justify-content-end gap-2">
                                        <!-- Botón Editar -->
                                        <a href="{{ route('owner.resellers.edit', $reseller) }}" class="btn btn-sm btn-outline-light rounded-3" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <!-- Botón Entrar (Mimetización) -->
                                        <a href="{{ route('owner.resellers.impersonate', $reseller) }}" class="btn btn-sm btn-primary rounded-3" title="Entrar como Revendedor">
                                            <i class="bi bi-box-arrow-in-right"></i> Entrar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 border-0">
                                    <i class="bi bi-person-x opacity-25" style="font-size: 3rem;"></i>
                                    <p class="text-secondary mt-3">No hay revendedores registrados todavía.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $resellers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
