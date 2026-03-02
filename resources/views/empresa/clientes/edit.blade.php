@extends('layouts.empresa')

@section('content')

<div class="container py-4">

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h3 class="fw-bold mb-4">Editar cliente</h3>

            <form method="POST" action="{{ route('empresa.clientes.update', $cliente->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Nombre --}}
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $cliente->name) }}"
                               required>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $cliente->email) }}">
                    </div>

                    {{-- Teléfono --}}
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone', $cliente->phone) }}">
                    </div>

                    {{-- Documento --}}
                    <div class="col-md-6">
                        <label class="form-label">Documento</label>
                        <input type="text"
                               name="document"
                               class="form-control"
                               value="{{ old('document', $cliente->document) }}">
                    </div>

                    {{-- Tipo --}}
                    <div class="col-md-6">
                        <label class="form-label">Condición</label>
                        <select name="type" class="form-select">
                            <option value="consumidor_final" {{ $cliente->type=='consumidor_final'?'selected':'' }}>Consumidor Final</option>
                            <option value="responsable_inscripto" {{ $cliente->type=='responsable_inscripto'?'selected':'' }}>Responsable Inscripto</option>
                            <option value="monotributo" {{ $cliente->type=='monotributo'?'selected':'' }}>Monotributo</option>
                        </select>
                    </div>

                    {{-- Límite crédito --}}
                    <div class="col-md-6">
                        <label class="form-label">Límite de crédito</label>
                        <input type="number"
                               step="0.01"
                               name="credit_limit"
                               class="form-control"
                               value="{{ old('credit_limit', $cliente->credit_limit) }}">
                    </div>

                    {{-- Activo --}}
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="active" class="form-select">
                            <option value="1" {{ $cliente->active?'selected':'' }}>Activo</option>
                            <option value="0" {{ !$cliente->active?'selected':'' }}>Inactivo</option>
                        </select>
                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('empresa.clientes.index') }}" class="btn btn-outline-secondary">
                        Volver
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Guardar cambios
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection
