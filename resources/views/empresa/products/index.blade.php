@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Productos</h2>
            <small class="text-muted">
                Gestión del catálogo de la empresa
            </small>
        </div>

        <a href="{{ route('empresa.products.create') }}"
           class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle"></i>
            Nuevo producto
        </a>
    </div>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card contenedora --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            @if($products->isEmpty())
                {{-- Estado vacío --}}
                <div class="text-center p-5">
                    <i class="bi bi-box-seam fs-1 text-muted"></i>
                    <h5 class="mt-3">No hay productos cargados</h5>
                    <p class="text-muted mb-3">
                        Empezá creando el primer producto del catálogo.
                    </p>
                    <a href="{{ route('empresa.products.create') }}"
                       class="btn btn-outline-primary">
                        Crear producto
                    </a>
                </div>
            @else

                {{-- Tabla --}}
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Producto</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold">
                                            {{ $product->name }}
                                        </div>
                                    </td>

                                    <td>
                                        <span class="fw-bold">
                                            ${{ number_format($product->price, 2, ',', '.') }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($product->active)
                                            <span class="badge bg-success-subtle text-success">
                                                Activo
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="{{ route('empresa.products.edit', $product) }}"
                                               class="btn btn-sm btn-outline-secondary">
                                                Editar
                                            </a>

                                            <a href="{{ route('empresa.products.images.create', $product) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                Imágenes
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @endif

        </div>
    </div>

</div>
@endsection
