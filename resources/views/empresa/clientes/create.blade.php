@extends('layouts.empresa')

@section('content')

<div class="container-fluid py-3">

    {{-- CABECERA --}}
    <div class="mb-3">
        <h2 class="fw-bold mb-0">Nuevo Cliente</h2>
        <small class="text-muted">Alta de cliente en el sistema</small>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST" action="{{ route('empresa.clientes.store') }}">
                @csrf

                <div class="row g-3">

                    {{-- NOMBRE --}}
                    <div class="col-md-6">
                        <label class="form-label">Nombre / Razón social *</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               required
                               value="{{ old('name') }}">
                    </div>

                    {{-- DOCUMENTO --}}
                    <div class="col-md-3">
                        <label class="form-label">Documento / CUIT</label>
                        <input type="text"
                               name="document"
                               class="form-control"
                               value="{{ old('document') }}">
                    </div>

                    {{-- TELEFONO --}}
                    <div class="col-md-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone') }}">
                    </div>

                    {{-- EMAIL --}}
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email') }}">
                    </div>

                    {{-- CONDICION IVA --}}
                    <div class="col-md-3">
                        <label class="form-label">Condición fiscal *</label>
                        <select name="type" class="form-select" required>
                            <option value="consumidor_final">Consumidor Final</option>
                            <option value="responsable_inscripto">Responsable Inscripto</option>
                            <option value="monotributo">Monotributo</option>
                            <option value="exento">Exento</option>
                        </select>
                    </div>

                    {{-- LIMITE CREDITO --}}
                    <div class="col-md-3">
                        <label class="form-label">Límite crédito</label>
                        <input type="number"
                               step="0.01"
                               name="credit_limit"
                               class="form-control"
                               value="{{ old('credit_limit',0) }}">
                    </div>

                </div>

                {{-- BOTONES --}}
                <div class="mt-4 d-flex gap-2">

                    <button type="submit" class="btn btn-success">
                        Guardar cliente
                    </button>

                    <a href="{{ route('empresa.clientes.index') }}"
                       class="btn btn-secondary">
                       Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection
