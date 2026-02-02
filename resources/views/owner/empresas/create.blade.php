@extends('layouts.admin')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-6">

        <div class="card shadow border-0">
            <div class="card-header bg-white fw-bold">
                Nueva empresa
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('owner.empresas.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nombre comercial</label>
                        <input type="text" name="nombre_comercial" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha de vencimiento</label>
                        <input type="date" name="fecha_vencimiento" class="form-control">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('owner.empresas.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Crear empresa
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
