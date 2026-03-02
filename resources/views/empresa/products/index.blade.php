@extends('layouts.empresa')

@section('content')

<div class="container py-4">

    {{-- ======================================================
        CABECERA
    ======================================================= --}}
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


    {{-- ======================================================
        BUSCADOR PROFESIONAL (consulta real a la base)
    ======================================================= --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">

            <form method="GET" action="{{ route('empresa.products.index') }}">
                <input type="text"
                       name="q"
                       class="form-control"
                       placeholder="Buscar producto en toda la base..."
                       value="{{ $buscar ?? '' }}"
                       autofocus>
            </form>

        </div>
    </div>


    {{-- ======================================================
        TABLA DE PRODUCTOS
    ======================================================= --}}
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

                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="ps-4">
                                    {{ $product->name }}
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
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    No se encontraron productos
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN (mantiene búsqueda activa) --}}
            <div class="p-3">
                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>

</div>



{{-- ======================================================
   ACTUALIZACIÓN AUTOMÁTICA DEL DASHBOARD
   (Se mantiene intacto)
====================================================== --}}
<script>
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

setInterval(actualizarDashboard, 3000);
actualizarDashboard();
</script>

@endsection
