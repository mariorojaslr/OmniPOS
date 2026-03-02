@extends('layouts.empresa')

@section('content')

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-0">Proveedores</h2>
            <small class="text-muted">Gestión de proveedores y cuentas corrientes</small>
        </div>

        <a href="{{ route('empresa.proveedores.create') }}" class="btn btn-primary">
            Nuevo proveedor
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Documento</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($suppliers as $s)
                    <tr>
                        <td class="fw-semibold">{{ $s->name }}</td>
                        <td>{{ $s->email }}</td>
                        <td>{{ $s->phone }}</td>
                        <td>{{ $s->document }}</td>

                        <td>
                            @if($s->active)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <a href="{{ route('empresa.proveedores.edit',$s->id) }}"
                               class="btn btn-sm btn-outline-primary">Editar</a>

                            <a href="{{ route('empresa.proveedores.show',$s->id) }}"
                               class="btn btn-sm btn-outline-success">Cuenta corriente</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-3">
                {{ $suppliers->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>

@endsection
