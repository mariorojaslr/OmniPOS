@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Nueva Receta (BOM)</h2>
            <p class="text-muted small">Cree una "fórmula" de materiales para sus mejores productos.</p>
        </div>
        <a href="{{ route('empresa.recipes.index') }}" class="btn btn-light border fw-bold shadow-sm">
            <i class="bi bi-chevron-left me-1"></i> VOLVER
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-dark text-uppercase">Paso 1: Seleccione el Producto Estrella</h6>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('empresa.recipes.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">¿A qué producto quieres asignarle esta receta?</label>
                            <select name="product_id" id="product_id" class="form-select form-select-lg border shadow-sm" required>
                                <option value="" selected disabled>Seleccionar producto...</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->barcode ?? 'S/C' }})</option>
                                @endforeach
                            </select>
                            @if($products->isEmpty())
                                <p class="text-danger small mt-2">No se encontraron productos para la Venta que aún no tengan receta.</p>
                                <a href="{{ route('empresa.products.create') }}" class="btn btn-outline-primary btn-sm fw-bold">CREAR PRODUCTO PARA ARMAR SU RECETA</a>
                            @endif
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold small text-muted">Nombre de la Receta (Opcional)</label>
                            <input type="text" name="name" class="form-control form-control-lg border shadow-sm" placeholder="Ej: Receta de invierno, Receta de casa...">
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm" @if($products->isEmpty()) disabled @endif>
                            CONTINUAR AL EDITOR DE INGREDIENTES <i class="bi bi-chevron-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- TOOLTIP / AYUDA TÁCTICA --}}
            <div class="card border-0 shadow-sm bg-light mb-4">
                <div class="card-body border-start border-4 border-info">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-info-circle-fill text-info fs-4 me-3"></i>
                        <h6 class="fw-bold mb-0">¿Cómo funciona el cerebro de producción?</h6>
                    </div>
                    <ul class="text-muted small mb-0 mt-3 p-0 ps-4">
                        <li><strong>Vínculo Automático:</strong> Al vender este producto, se descontará automáticamente el stock de sus componentes.</li>
                        <li><strong>Ahorro de Tiempo:</strong> Ya no tendrás que hacer bajas manuales de tus materias primas.</li>
                        <li><strong>Control de Margen:</strong> Podrás ver el costo real de fabricación frente al precio de venta.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
