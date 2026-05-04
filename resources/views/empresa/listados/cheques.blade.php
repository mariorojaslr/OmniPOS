@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-3 px-4">

    {{-- CABECERA --}}
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <div>
            <h4 class="fw-bold text-dark mb-0">Cartera de Cheques de Terceros</h4>
            <p class="text-muted small mb-0">Total valores en cartera: {{ $items->count() }}</p>
        </div>
        <button onclick="window.print()" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-printer me-2"></i> IMPRIMIR CARTERA
        </button>
    </div>

    {{-- LISTADO --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <table class="table table-hover table-sm align-middle mb-0 printable-table">
                <thead class="bg-light">
                    <tr class="x-small fw-bold text-muted text-uppercase ls-1">
                        <th class="ps-3 py-2" style="width: 50px;">O</th>
                        <th class="py-2">Vencimiento</th>
                        <th class="py-2">Banco / Número</th>
                        <th class="py-2">Emisor / Cliente</th>
                        <th class="text-end py-2 px-3">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i)
                    <tr>
                        <td class="ps-3">
                            <div class="check-box border rounded d-inline-block" style="width: 18px; height: 18px;"></div>
                        </td>
                        <td>
                            <div class="small fw-bold {{ $i->fecha_pago < now() ? 'text-danger' : 'text-dark' }}">
                                {{ $i->fecha_pago->format('d/m/Y') }}
                            </div>
                            <div class="x-small text-muted">{{ $i->estado == 'depositado' ? 'Depositado' : ucfirst(str_replace('_', ' ', $i->estado)) }}</div>
                        </td>
                        <td>
                            <div class="small fw-bold text-dark">{{ $i->banco ?? '---' }}</div>
                            <div class="x-small text-muted font-monospace">N° {{ $i->numero }}</div>
                        </td>
                        <td>
                            <div class="small fw-medium text-dark">{{ $i->emisor ?? '---' }}</div>
                            <div class="x-small text-muted">Recibido de: {{ $i->client->name ?? '---' }}</div>
                        </td>
                        <td class="text-end px-3">
                            <h6 class="fw-bold mb-0 text-dark">${{ number_format($i->monto, 2, ',', '.') }}</h6>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
    .x-small { font-size: 0.65rem; }
    .ls-1 { letter-spacing: 1px; }
    .check-box { border: 1px solid #ccc !important; }
    
    @media print {
        .no-print, .main-sidebar, .top-bar, .btn { display: none !important; }
        .content-wrapper { margin: 0 !important; padding: 0 !important; }
        .card { box-shadow: none !important; border: none !important; }
        body { background: white !important; }
        .printable-table { width: 100% !important; }
        .printable-table th { background-color: #f0f0f0 !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection
