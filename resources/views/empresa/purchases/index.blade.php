@extends('layouts.empresa')

@section('content')

{{-- =========================================================
   ENCABEZADO
========================================================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Compras</h2>
        <small class="text-muted">Control profesional de compras</small>
    </div>

    <a href="{{ route('empresa.compras.create') }}" class="btn btn-primary">
        Nueva compra
    </a>
</div>

{{-- =========================================================
   KPIs
========================================================= --}}
<div class="row mb-4">

    <div class="col-md-2">
        <div class="card text-center border-success">
            <div class="card-body">
                <small>Hoy</small>
                <h6 class="mb-0">$ {{ number_format($kpiToday ?? 0,2,',','.') }}</h6>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card text-center border-info">
            <div class="card-body">
                <small>Semana</small>
                <h6 class="mb-0">$ {{ number_format($kpiWeek ?? 0,2,',','.') }}</h6>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card text-center border-primary">
            <div class="card-body">
                <small>Mes</small>
                <h6 class="mb-0">$ {{ number_format($kpiMonth ?? 0,2,',','.') }}</h6>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center border-warning">
            <div class="card-body">
                <small>Crédito</small>
                <h6 class="mb-0">$ {{ number_format($kpiCredito ?? 0,2,',','.') }}</h6>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center border-success">
            <div class="card-body">
                <small>Contado</small>
                <h6 class="mb-0">$ {{ number_format($kpiContado ?? 0,2,',','.') }}</h6>
            </div>
        </div>
    </div>

</div>

{{-- =========================================================
   FILTROS
========================================================= --}}
<form method="GET" class="card mb-4">
    <div class="card-body">
        <div class="row">

            <div class="col-md-4">
                <label>Buscar (fecha o comprobante)</label>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       class="form-control"
                       placeholder="Ej: 0002-00000125 o 2026-02-26">
            </div>

            <div class="col-md-2">
                <label>Desde</label>
                <input type="date"
                       name="from"
                       value="{{ request('from') }}"
                       class="form-control">
            </div>

            <div class="col-md-2">
                <label>Hasta</label>
                <input type="date"
                       name="to"
                       value="{{ request('to') }}"
                       class="form-control">
            </div>

            <div class="col-md-2">
                <label>Tipo pago</label>
                <select name="payment" class="form-control">
                    <option value="">Todos</option>
                    <option value="contado" @selected(request('payment')=='contado')>Contado</option>
                    <option value="credito" @selected(request('payment')=='credito')>Crédito</option>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-dark w-100">
                    Filtrar
                </button>
            </div>

        </div>
    </div>
</form>

{{-- =========================================================
   LISTADO
========================================================= --}}
<div class="card">

    {{-- PAGINADOR + SELECTOR --}}
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">

        <div>
            {{ $purchases->links('pagination::bootstrap-5') }}
        </div>

        <form method="GET" class="d-flex align-items-center gap-2">

            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="from" value="{{ request('from') }}">
            <input type="hidden" name="to" value="{{ request('to') }}">
            <input type="hidden" name="payment" value="{{ request('payment') }}">

            <label class="mb-0 small text-muted">Mostrar</label>

            <select name="per_page"
                    class="form-select form-select-sm"
                    onchange="this.form.submit()"
                    style="width:90px;">

                @foreach([10,25,50,100] as $size)
                    <option value="{{ $size }}"
                        {{ request('per_page',15)==$size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach

            </select>
        </form>

    </div>

    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th width="130">Fecha</th>
                    <th width="180">Comprobante</th>
                    <th>Proveedor</th>
                    <th width="160">Total</th>
                    <th width="120">Tipo</th>
                    <th width="180">Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($purchases as $purchase)
                <tr>

                    <td>
                        {{ $purchase->purchase_date
                            ? \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y')
                            : '-' }}
                    </td>

                    <td>
                        {{ $purchase->invoice_number
                            ? ($purchase->invoice_type.' '.$purchase->invoice_number)
                            : '-' }}
                    </td>

                    <td>
                        {{ $purchase->supplier->name ?? '-' }}
                    </td>

                    <td>
                        $ {{ number_format($purchase->total,2,',','.') }}
                    </td>

                    <td>
                        @if($purchase->payment_type == 'contado')
                            <span class="badge bg-success">Contado</span>
                        @else
                            <span class="badge bg-warning text-dark">Crédito</span>
                        @endif
                    </td>

                    <td class="d-flex gap-2">

                        <a href="{{ route('empresa.compras.show', $purchase->id) }}"
                           class="btn btn-sm btn-outline-primary">
                           Ver
                        </a>

                        <form method="POST"
                              action="{{ route('empresa.compras.destroy', $purchase->id) }}"
                              onsubmit="return confirm('¿Eliminar esta compra y revertir stock/pago?');">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-outline-danger">
                                Borrar
                            </button>
                        </form>

                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No hay resultados
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>

    </div>

    <div class="p-3 border-top">
        {{ $purchases->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection
