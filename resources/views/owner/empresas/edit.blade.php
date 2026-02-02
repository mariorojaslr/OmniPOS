@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-6">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold">
                Editar empresa
            </div>

            <div class="card-body">
                <form method="POST"
                      action="{{ route('owner.empresas.update', $empresa) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nombre comercial</label>
                        <input type="text"
                               name="nombre_comercial"
                               class="form-control"
                               value="{{ $empresa->nombre_comercial }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Razón social</label>
                        <input type="text"
                               name="razon_social"
                               class="form-control"
                               value="{{ $empresa->razon_social }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ $empresa->email }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text"
                               name="telefono"
                               class="form-control"
                               value="{{ $empresa->telefono }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Vencimiento</label>
                        <input type="date"
                               name="fecha_vencimiento"
                               class="form-control"
                               value="{{ optional($empresa->fecha_vencimiento)->format('Y-m-d') }}">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('owner.empresas.index') }}"
                           class="btn btn-outline-secondary">
                            Cancelar
                        </a>

                        <button class="btn btn-primary">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
