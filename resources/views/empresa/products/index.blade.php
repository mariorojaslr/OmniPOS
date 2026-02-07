@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Productos</h2>
            <small class="text-muted">Gestión del catálogo de la empresa</small>
        </div>

        <a href="{{ route('empresa.products.create') }}"
           class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle"></i>
            Nuevo producto
        </a>
    </div>

    {{-- BUSCADOR --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <input type="text"
                   id="buscarProducto"
                   class="form-control"
                   placeholder="Buscar producto..."
                   autocomplete="off"
                   autofocus>
        </div>
    </div>

    {{-- TABLA --}}
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
                            <tr>
                                <td class="ps-4 nombre">{{ $product->name }}</td>

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

            <div class="p-3">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>


{{-- =========================
   BUSQUEDA EN VIVO + RESALTADO
========================= --}}
<script>
const input = document.getElementById('buscarProducto');
const tabla = document.getElementById('tablaProductos');

input.addEventListener('input', function () {
    let q = this.value;

    fetch(`{{ route('empresa.products.index') }}?q=${encodeURIComponent(q)}&ajax=1`)
        .then(res => res.json())
        .then(data => {

            if (q === '') {
                location.reload();
                return;
            }

            tabla.innerHTML = '';

            data.forEach(p => {

                let nombre = p.name.replace(
                    new RegExp(`(${q})`, 'gi'),
                    `<mark style="background:#fff3cd">$1</mark>`
                );

                tabla.innerHTML += `
                    <tr>
                        <td class="ps-4">${nombre}</td>
                        <td>$${parseFloat(p.price).toFixed(2)}</td>
                        <td>${p.active ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>'}</td>
                        <td class="text-end pe-4">
                            <a href="/empresa/products/${p.id}/edit" class="btn btn-sm btn-outline-secondary">Editar</a>
                            <a href="/empresa/products/${p.id}/images/create" class="btn btn-sm btn-outline-primary">Imágenes</a>
                        </td>
                    </tr>`;
            });
        });
});
</script>



<script>
/*
======================================================
ACTUALIZACION AUTOMATICA DEL DASHBOARD (TIEMPO REAL)
- Actualiza cada 3 segundos
- Refleja ventas inmediatamente
======================================================
*/

async function actualizarDashboard() {
    try {
        const res = await fetch("{{ route('empresa.dashboard.resumen') }}");
        const data = await res.json();

        if (document.getElementById('ventasHoy'))
            document.getElementById('ventasHoy').innerText = data.ventas_hoy;

        if (document.getElementById('montoHoy'))
            document.getElementById('montoHoy').innerText = data.monto_hoy;

        if (document.getElementById('ventasSemana'))
            document.getElementById('ventasSemana').innerText = data.ventas_semana;

        if (document.getElementById('montoSemana'))
            document.getElementById('montoSemana').innerText = data.monto_semana;

        if (document.getElementById('ventasMes'))
            document.getElementById('ventasMes').innerText = data.ventas_mes;

        if (document.getElementById('montoMes'))
            document.getElementById('montoMes').innerText = data.monto_mes;

    } catch (e) {
        console.log('Error actualizando dashboard');
    }
}

// Cada 3 segundos refresca
setInterval(actualizarDashboard, 3000);

// Ejecuta una vez al cargar
actualizarDashboard();
</script>




@endsection
