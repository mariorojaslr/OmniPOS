@extends('layouts.empresa')

@section('content')

<div class="container py-3">

    <h3 class="mb-3">Editar Proveedor</h3>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('empresa.proveedores.update',$supplier->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $supplier->email }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Documento</label>
                    <input type="text" name="document" class="form-control" value="{{ $supplier->document }}">
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="active" value="1" class="form-check-input"
                           {{ $supplier->active ? 'checked' : '' }}>
                    <label class="form-check-label">Activo</label>
                </div>

                <button class="btn btn-success">Actualizar</button>
                <a href="{{ route('empresa.proveedores.index') }}" class="btn btn-secondary">Volver</a>

            </form>

        </div>
    </div>

</div>

@endsection
