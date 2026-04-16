@extends('layouts.empresa')

@section('content')

<div class="container py-3">

    <h3 class="mb-3">Nuevo Proveedor</h3>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('empresa.proveedores.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="phone" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Documento / CUIT</label>
                    <input type="text" name="document" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control">
                </div>

                <div class="row gx-3 text-start">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Latitud (GPS)</label>
                        <input type="text" name="lat" class="form-control" placeholder="-34...">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Longitud (GPS)</label>
                        <input type="text" name="lng" class="form-control" placeholder="-58...">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-primary">PLUS CODE 🌐</label>
                        <input type="text" name="plus_code" class="form-control" placeholder="8GV2+M9">
                    </div>
                </div>

                <button class="btn btn-success">Guardar proveedor</button>
                <a href="{{ route('empresa.proveedores.index') }}" class="btn btn-secondary">Volver</a>

            </form>

        </div>
    </div>

</div>

@endsection
