@extends('layouts.empresa')

@section('content')

<style>

/* ===============================
   TABLA CONTABLE ERP
   =============================== */

.table-erp td,
.table-erp th {
    white-space: nowrap !important;
    vertical-align: middle;
}

/* columnas */
.col-fecha { width:110px; }
.col-comp  { min-width:220px; }
.col-det   { min-width:260px; }
.col-debe  { width:120px; text-align:right; }
.col-haber { width:120px; text-align:right; }
.col-saldo { width:140px; text-align:right; font-weight:600; }

.table-scroll {
    overflow-x:auto;
    overflow-y:hidden;
}

/* colores contables */
.debe { color:#dc3545; font-weight:500; }
.haber { color:#198754; font-weight:500; }
.saldo { font-weight:700; }

.table-erp tbody tr:hover {
    background:#f6f9ff;
}

</style>

<div class="container-fluid py-4">

    {{-- ================= ENCABEZADO ================= --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div>
                <h4 class="fw-bold mb-0">Cuenta Corriente</h4>
                <small class="text-muted">
                    Cliente: {{ $cliente->name }}
                </small>
            </div>

            <div class="text-end">
                <div class="small text-muted">Saldo Actual</div>
                <div class="fs-4 fw-bold {{ $saldo > 0 ? 'text-danger' : 'text-success' }}">
                    ${{ number_format($saldo,2,',','.') }}
                </div>
            </div>

        </div>
    </div>


    {{-- ================= FILTROS + EXPORT ================= --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">

            <form method="GET" class="row g-2 align-items-end">

                <div class="col-auto">
                    <label class="small">Tipo</label>
                    <select name="tipo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="debit" {{ request('tipo')=='debit'?'selected':'' }}>Facturas</option>
                        <option value="credit" {{ request('tipo')=='credit'?'selected':'' }}>Recibos</option>
                    </select>
                </div>

                <div class="col-auto">
                    <label class="small">Desde</label>
                    <input type="date" name="desde" class="form-control form-control-sm"
                           value="{{ request('desde') }}">
                </div>

                <div class="col-auto">
                    <label class="small">Hasta</label>
                    <input type="date" name="hasta" class="form-control form-control-sm"
                           value="{{ request('hasta') }}">
                </div>

                <div class="col-auto">
                    <button class="btn btn-sm btn-primary">Filtrar</button>
                </div>

                <div class="col-auto ms-auto">
                    <a href="{{ request()->fullUrlWithQuery(['export'=>'pdf']) }}"
                       class="btn btn-sm btn-outline-danger">
                       Exportar PDF
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['export'=>'excel']) }}"
                       class="btn btn-sm btn-outline-success">
                       Exportar Excel
                    </a>
                </div>

            </form>

        </div>
    </div>


    {{-- ================= TABLA ================= --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-scroll">

                <table class="table table-sm table-bordered mb-0 table-erp align-middle">

                    <thead class="table-light">
                        <tr>
                            <th class="col-fecha">Fecha</th>
                            <th class="col-comp">Comprobante</th>
                            <th class="col-det">Detalle</th>
                            <th class="col-debe">Debe</th>
                            <th class="col-haber">Haber</th>
                            <th class="col-saldo">Saldo</th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach($movimientos as $m)

                        @php
                            $numero = str_pad($m->id,8,'0',STR_PAD_LEFT);
                            $comprobante = $m->type === 'debit'
                                ? "FAC B 0001-{$numero}"
                                : "REC 0001-{$numero}";
                        @endphp

                        <tr>

                            <td>{{ $m->created_at->format('d/m/Y') }}</td>

                            <td class="fw-semibold">{{ $comprobante }}</td>

                            <td>{{ $m->description ?? 'Movimiento' }}</td>

                            <td class="col-debe debe">
                                {{ $m->type=='debit' ? number_format($m->amount,2,',','.') : '' }}
                            </td>

                            <td class="col-haber haber">
                                {{ $m->type=='credit' ? number_format($m->amount,2,',','.') : '' }}
                            </td>

                            <td class="col-saldo saldo">
                                {{ number_format($m->saldo_acumulado,2,',','.') }}
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    {{-- ================= PAGINADOR ================= --}}
    <div class="mt-3">
        {{ $movimientos->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection
