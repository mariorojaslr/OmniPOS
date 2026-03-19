@extends('layouts.empresa')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Hub de Etiquetas</h2>
            <small class="text-muted">Generá códigos de barras para tus productos en masa</small>
        </div>
        <a href="{{ route('empresa.products.index') }}" class="btn btn-outline-secondary btn-sm">← Volver a Productos</a>
    </div>

    <form action="{{ route('empresa.labels.generate') }}" method="POST" target="_blank">
        @csrf
        
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-muted">1. Seleccioná los productos</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table align-middle mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th width="40" class="ps-4">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Producto</th>
                                        <th>Código</th>
                                        <th class="text-end pe-4">Precio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $p)
                                        <tr>
                                            <td class="ps-4">
                                                <input type="checkbox" name="items[]" value="{{ $p->id }}" class="form-check-input item-check">
                                            </td>
                                            <td class="fw-bold">{{ $p->name }}</td>
                                            <td><code>{{ $p->barcode }}</code></td>
                                            <td class="text-end pe-4">${{ number_format($p->price, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">No hay productos con código de barras asignado.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-muted">2. Configuración de impresión</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Etiquetas por producto</label>
                            <input type="number" name="quantity" value="1" min="1" max="100" class="form-control">
                            <small class="text-muted">Dará como resultado N copias de cada producto seleccionado.</small>
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-2"></i>
                            El PDF generado está optimizado para hojas A4 (3 etiquetas por fila).
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                            <i class="bi bi-printer me-2"></i> Generar PDF de Etiquetas
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('selectAll').onclick = function() {
    let checkboxes = document.getElementsByClassName('item-check');
    for (let checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
}
</script>
@endsection
