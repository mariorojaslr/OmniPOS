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

    {{-- Buscador --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <input type="text"
                   id="buscarProducto"
                   class="form-control"
                   placeholder="Buscar producto...">
        </div>
    </div>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabla --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

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

                    <tbody id="tablaProductos">
                        @foreach($products as $product)
                            <tr class="fila-producto">
                                <td class="ps-4 nombre-producto">
                                    <strong>{{ $product->name }}</strong>
                                </td>

                                <td>
                                    ${{ number_format($product->price, 2, ',', '.') }}
                                </td>

                                <td>
                                    @if($product->active)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>

                                <td class="text-end pe-4">
                                    <a href="{{ route('empresa.products.edit', $product) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        Editar
                                    </a>

                                    <a href="{{ route('empresa.products.images.create', $product) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Imágenes
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINADOR --}}
            <div class="p-3">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>

</div>

{{-- BUSCADOR EN TIEMPO REAL --}}
<script>
document.getElementById('buscarProducto').addEventListener('input', function () {
    let filtro = this.value.toLowerCase();

    document.querySelectorAll('.fila-producto').forEach(function (row) {
        let nombre = row.querySelector('.nombre-producto').innerText.toLowerCase();

        if (filtro === '') {
            row.style.display = '';
            row.querySelector('.nombre-producto').innerHTML = row.querySelector('.nombre-producto').innerText;
            return;
        }

        if (nombre.includes(filtro)) {
            row.style.display = '';

            let original = row.querySelector('.nombre-producto').innerText;
            let regex = new RegExp('(' + filtro + ')', 'gi');
            row.querySelector('.nombre-producto').innerHTML =
                original.replace(regex, '<mark style="background: #fff3cd;">$1</mark>');
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

@endsection
