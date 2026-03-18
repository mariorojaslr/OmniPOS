@extends('layouts.empresa')

@section('content')

<div class="row mb-4 align-items-center">
    <div class="col">
        <h2 class="mb-0">Cuenta Corriente</h2>
        <p class="text-muted">Estado de cuenta con: <strong>{{ $supplier->name }}</strong></p>
    </div>
    <div class="col-auto">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalPago">
            💰 Registrar Pago
        </button>
        <a href="{{ route('empresa.proveedores.index') }}" class="btn btn-secondary ms-2">Volver</a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center">
                <small class="text-uppercase fw-bold text-muted">Saldo Total</small>
                <h3 class="mb-0 @if($saldo > 0) text-danger @else text-success @endif">
                    $ {{ number_format($saldo, 2, ',', '.') }}
                </h3>
            </div>
        </div>
    </div>
    @if($saldoVencido > 0)
    <div class="col-md-3">
        <div class="card h-100 shadow-sm border-0 bg-light-danger border-start border-danger border-4">
            <div class="card-body text-center">
                <small class="text-uppercase fw-bold text-danger">Saldo Vencido (+30d)</small>
                <h3 class="mb-0 text-danger">$ {{ number_format($saldoVencido, 2, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Historial de Movimientos</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th width="120">Fecha</th>
                        <th>Detalle</th>
                        <th width="140" class="text-end">Debe (Deuda)</th>
                        <th width="140" class="text-end">Haber (Pago)</th>
                        <th width="160" class="text-end">Saldo Acum.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimientos as $m)
                    <tr>
                        <td>{{ $m->created_at->format('d/m/Y') }}</td>
                        <td>{{ $m->description ?? $m->detail }}</td>
                        <td class="text-end text-danger fw-bold">
                            {{ $m->debe ? '$ '.number_format($m->debe, 2, ',', '.') : '-' }}
                        </td>
                        <td class="text-end text-success fw-bold">
                            {{ $m->haber ? '$ '.number_format($m->haber, 2, ',', '.') : '-' }}
                        </td>
                        <td class="text-end fw-bold">
                            $ {{ number_format($m->saldo_acumulado, 2, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            No hay movimientos registrados para este proveedor.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($movimientos->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $movimientos->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

{{-- MODAL REGISTRAR PAGO --}}
<div class="modal fade" id="modalPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('empresa.proveedores.pago', $supplier->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Pago a Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Monto del Pago</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="amount" class="form-control" required placeholder="0.00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Concepto / Detalle</label>
                        <input type="text" name="description" class="form-control" required 
                               placeholder="Ej: Pago de factura 0001-00000456">
                    </div>
                    <div class="alert alert-info py-2 small">
                        Este registro generará un movimiento de <strong>crédito</strong> en la cuenta corriente, disminuyendo el saldo adeudado.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success px-4">Confirmar Pago</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
