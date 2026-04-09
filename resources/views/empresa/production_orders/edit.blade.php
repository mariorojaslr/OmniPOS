@extends('layouts.empresa')

@section('content')
<div class="container py-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('empresa.production_orders.index') }}" class="text-muted text-decoration-none">Órdenes de Producción</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('empresa.production_orders.show', $production_order) }}" class="text-muted text-decoration-none">Orden #{{ $production_order->id }}</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0" style="color: var(--color-primario);">Editar Orden #{{ $production_order->id }}</h2>
            <p class="text-muted small">Modifique el estado y las notas de la orden de producción.</p>
        </div>
        <a href="{{ route('empresa.production_orders.show', $production_order) }}" class="btn btn-light border fw-bold shadow-sm px-4">
            <i class="bi bi-arrow-left me-1"></i> Cancelar
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4">{{ session('error') }}</div>
    @endif

    <div class="row g-4 justify-content-center">
        <div class="col-lg-7">

            {{-- Info de la Orden (solo lectura) --}}
            <div class="card border-0 shadow-sm bg-white overflow-hidden mb-4">
                <div class="card-header bg-light border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-muted text-uppercase">
                        <i class="bi bi-info-circle me-2 opacity-50"></i> Datos del Lote (No editables)
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold d-block mb-1">Producto Fabricado</label>
                            <span class="fw-bold text-dark">{{ $production_order->recipe->product->name ?? 'N/A' }}</span>
                            <small class="text-muted d-block">{{ $production_order->recipe->name ?? '' }}</small>
                        </div>
                        <div class="col-md-3">
                            <label class="small text-muted text-uppercase fw-bold d-block mb-1">Cantidad</label>
                            <span class="fw-bold text-dark fs-5">
                                {{ number_format($production_order->quantity, 2) }}
                                <small class="fs-6 text-muted">{{ $production_order->recipe->product->unit->short_name ?? 'U' }}</small>
                            </span>
                        </div>
                        <div class="col-md-3">
                            <label class="small text-muted text-uppercase fw-bold d-block mb-1">Autorizado por</label>
                            <span class="fw-bold text-dark">{{ $production_order->user->name ?? 'Sistema' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Formulario de edición --}}
            <div class="card border-0 shadow-sm bg-white overflow-hidden">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-dark text-uppercase">
                        <i class="bi bi-pencil-square me-2 opacity-50"></i> Campos Editables
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('empresa.production_orders.update', $production_order) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- ESTADO --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-2">
                                Estado de la Orden <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex gap-3 flex-wrap">
                                @foreach(['pendiente' => ['label' => 'Pendiente', 'color' => 'warning', 'icon' => 'clock-fill'],
                                          'completada' => ['label' => 'Completada', 'color' => 'success', 'icon' => 'check-circle-fill'],
                                          'cancelada'  => ['label' => 'Cancelada',  'color' => 'danger',  'icon' => 'x-circle-fill']] as $val => $opt)
                                    <div class="form-check flex-fill">
                                        <input class="form-check-input" type="radio" name="status"
                                               id="status_{{ $val }}" value="{{ $val }}"
                                               {{ old('status', $production_order->status) === $val ? 'checked' : '' }}>
                                        <label class="form-check-label w-100 p-3 border rounded-3 cursor-pointer text-center {{ old('status', $production_order->status) === $val ? 'border-'.$opt['color'].' bg-'.$opt['color'].' bg-opacity-10' : '' }}"
                                               for="status_{{ $val }}" style="cursor:pointer; transition: all .2s"
                                               onclick="this.closest('.d-flex').querySelectorAll('label').forEach(l=>l.classList.remove('border-success','border-warning','border-danger','bg-success','bg-warning','bg-danger','bg-opacity-10','border-')); this.classList.add('border-{{ $opt['color'] }}','bg-{{ $opt['color'] }}','bg-opacity-10');">
                                            <i class="bi bi-{{ $opt['icon'] }} text-{{ $opt['color'] }} fs-4 d-block mb-1"></i>
                                            <span class="fw-bold small text-{{ $opt['color'] }}">{{ $opt['label'] }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- NOTAS --}}
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold small text-muted text-uppercase mb-2">
                                Observaciones / Notas del Lote
                            </label>
                            <textarea id="notes" name="notes" class="form-control border shadow-sm @error('notes') is-invalid @enderror"
                                      rows="4" placeholder="Ej: Lote tarde - Personal: Juancito y Ana. Observaciones de calidad...">{{ old('notes', $production_order->notes) }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Máximo 1000 caracteres.</small>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary fw-bold shadow-sm py-3 flex-fill" style="font-size: 1rem;">
                                <i class="bi bi-save me-2"></i> GUARDAR CAMBIOS
                            </button>
                            <a href="{{ route('empresa.production_orders.show', $production_order) }}" class="btn btn-light border fw-bold py-3 px-4">
                                Cancelar
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
