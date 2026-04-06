@extends('layouts.empresa')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-0" style="color: var(--color-primario);">Módulo de Producción</h2>
            <p class="text-muted small">Cree una "fórmula" de materiales para sus mejores productos.</p>
        </div>
        <a href="{{ route('empresa.recipes.index') }}" class="btn btn-light border fw-bold shadow-sm px-4">
            <i class="bi bi-chevron-left me-2"></i> VOLVER
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-header bg-white border-bottom py-4 d-flex align-items-center">
                    <i class="bi bi-plus-circle-dotted fs-4 text-muted me-3"></i>
                    <h6 class="fw-bold mb-0 text-dark text-uppercase">Paso 1: Seleccione el Producto Estrella</h6>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('empresa.recipes.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-3">Producto Principal</label>
                            <select name="product_id" id="product_id" class="form-select form-select-lg border shadow-sm rounded-3" required>
                                <option value="" selected disabled>Seleccionar producto...</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->barcode ?? 'S/C' }})</option>
                                @endforeach
                            </select>
                            @if($products->isEmpty())
                                <div class="alert alert-warning border-0 mt-3 small opacity-75">No se encontraron productos para la Venta que aún no tengan receta configurada.</div>
                            @endif
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-3">Nombre Opcional (Especifique la versión)</label>
                            <input type="text" name="name" class="form-control form-control-lg border shadow-sm rounded-3" placeholder="Ej: Standard, Gourmet, XL...">
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm py-3 transition-hover" @if($products->isEmpty()) disabled @endif>
                            PASAR AL ARMADO DE LA RECETA <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- TOOLTIP / AYUDA TÁCTICA --}}
            <div class="card border-0 shadow-sm bg-white mb-4">
                <div class="card-body border-start border-4 rounded-3" style="border-color: var(--color-primario) !important;">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-info-circle-fill text-muted fs-4 me-3"></i>
                        <h6 class="fw-bold mb-0 text-dark">¿Cómo funciona el cerebro de producción?</h6>
                    </div>
                    <ul class="text-muted small mb-0 mt-0 p-0 ps-4">
                        <li class="mb-2"><strong>Descuento en Cascada:</strong> Al vender este producto, se descontará automáticamente el stock de sus ingredientes.</li>
                        <li class="mb-2"><strong>Gestión Centralizada:</strong> Ya no tendrás que hacer bajas manuales de materias primas tras cada venta.</li>
                        <li><strong>Precisión de Margen:</strong> Analizaremos el costo real frente a tu precio de venta final.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
<style>
    .transition-hover { transition: all 0.3s ease; }
    .transition-hover:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
</style>
@endsection
