@extends('layouts.empresa')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-0" style="color: var(--color-primario);">Unidades de Medida</h2>
            <p class="text-muted small">Estandarice las porciones y pesos de sus insumos y recetas.</p>
        </div>
        <button type="button" class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createUnitModal">
            <i class="bi bi-plus-circle me-2"></i> NUEVA UNIDAD
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4">{{ session('error') }}</div>
    @endif

    <div class="row">
        @foreach($units as $u)
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card border-0 shadow-sm h-100 bg-white">
                    <div class="card-body p-4 text-center">
                        <div class="bg-light rounded-circle p-4 mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <span class="fw-bold fs-4" style="color: var(--color-primario);">{{ $u->short_name }}</span>
                        </div>
                        <h6 class="fw-bold mb-1 text-dark text-uppercase">{{ $u->name }}</h6>
                        
                        @if($u->empresa_id)
                            <span class="badge bg-light text-primary border border-primary small opacity-75">Personalizada</span>
                        @else
                            <span class="badge bg-light text-muted border small opacity-75">Sistema Global</span>
                        @endif

                        @if($u->empresa_id)
                        <div class="mt-4">
                            <form action="{{ route('empresa.units.destroy', $u) }}" method="POST" onsubmit="return confirm('¿Seguro que desea eliminar esta unidad?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0 small">Eliminar Unidad</button>
                            </form>
                        </div>
                        @else
                            <div class="mt-4">
                                <span class="text-muted small italic">Sólo lectura</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

{{-- MODAL CREACIÓN --}}
<div class="modal fade" id="createUnitModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-0 py-4 ps-4">
                <h5 class="modal-title fw-bold text-dark">NUEVA UNIDAD DE MEDIDA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('empresa.units.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-3">Nombre Largo (Ej: Kilogramos)</label>
                        <input type="text" name="name" class="form-control form-control-lg border shadow-sm rounded-3" placeholder="Ej: Centímetros Cúbicos" required>
                    </div>
                    <div>
                        <label class="form-label fw-bold small text-muted text-uppercase mb-3">Sigla / Corto (Ej: KG)</label>
                        <input type="text" name="short_name" class="form-control form-control-lg border shadow-sm rounded-3" placeholder="Ej: CC, ML, GR" maxlength="10" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-2">
                    <button type="button" class="btn btn-light fw-bold" data-bs-view="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">GUARDAR UNIDAD</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
