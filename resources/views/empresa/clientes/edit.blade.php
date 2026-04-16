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

                    {{-- Condición --}}
                    <div class="col-md-3">
                        <label class="form-label">Condición Fiscal</label>
                        <select name="tax_condition" class="form-select">
                            <option value="consumidor_final" {{ $cliente->tax_condition=='consumidor_final'?'selected':'' }}>Consumidor Final</option>
                            <option value="responsable_inscripto" {{ $cliente->tax_condition=='responsable_inscripto'?'selected':'' }}>Responsable Inscripto</option>
                            <option value="monotributo" {{ $cliente->tax_condition=='monotributo'?'selected':'' }}>Monotributo</option>
                            <option value="exento" {{ $cliente->tax_condition=='exento'?'selected':'' }}>Exento</option>
                        </select>
                    </div>

                    {{-- Tipo --}}
                    <div class="col-md-3">
                        <label class="form-label">Tipo de Cliente</label>
                        <select name="type" class="form-select">
                            <option value="consumidor_final" {{ $cliente->type=='consumidor_final'?'selected':'' }}>Consumidor Final</option>
                            <option value="minorista" {{ $cliente->type=='minorista'?'selected':'' }}>Minorista</option>
                            <option value="mayorista" {{ $cliente->type=='mayorista'?'selected':'' }}>Mayorista</option>
                            <option value="revendedor" {{ $cliente->type=='revendedor'?'selected':'' }}>Revendedor</option>
                            <option value="amigo" {{ $cliente->type=='amigo'?'selected':'' }}>Amigo / VIP</option>
                        </select>
                    </div>

                    {{-- Dirección --}}
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $cliente->address) }}">
                    </div>

                    {{-- GPS --}}
                    <div class="col-md-2">
                        <label class="form-label">Latitud (GPS)</label>
                        <input type="text" name="lat" class="form-control" value="{{ old('lat', $cliente->lat) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Longitud (GPS)</label>
                        <input type="text" name="lng" class="form-control" value="{{ old('lng', $cliente->lng) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-primary fw-bold text-uppercase" style="font-size: 0.75rem;">Plus Code 🌐</label>
                        <input type="text" name="plus_code" class="form-control" value="{{ old('plus_code', $cliente->plus_code) }}" placeholder="8GV2+M9">
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
