@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-0">Detalle de Pago</h2>
                    <p class="text-muted mb-0">Visualizando Recibo X - {{ str_pad($recibo->numero_recibo, 8, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('empresa.pagos.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i> Volver
                    </a>
                    <button type="button" class="btn btn-warning rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalEditReferences">
                        <i class="fas fa-edit me-2"></i> Editar Referencias
                    </button>
                    <a href="{{ route('empresa.pagos.print', $recibo->id) }}" target="_blank" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                        <i class="fas fa-print me-2"></i> Imprimir / PDF
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-5">
                    <!-- Cabecera del Recibo -->
                    <div class="row mb-4 border-bottom pb-4">
                        <div class="col-sm-6">
                            <span class="text-uppercase small fw-bold opacity-75 d-block mb-1">Cliente</span>
                            <h4 class="fw-bold mb-0 text-primary">{{ $recibo->client->name ?? 'Cliente Desconocido' }}</h4>
                            @if($recibo->client && $recibo->client->document)
                                <small class="text-muted d-block mt-1">DNI/CUIT: {{ $recibo->client->document }}</small>
                            @endif
                        </div>
                        <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                            <h1 class="fw-bold text-success mb-0">${{ number_format($recibo->monto_total, 2, ',', '.') }}</h1>
                            <span class="text-uppercase small fw-bold opacity-50 d-block mt-1">Importe Total Recibido</span>
                        </div>
                    </div>

                    <!-- Datos Generales -->
                    <div class="row g-4 mb-4">
                        <div class="col-sm-4">
                            <div class="bg-light p-3 rounded text-center border">
                                <span class="text-uppercase small opacity-75 d-block">Número</span>
                                <span class="fw-bold fs-5">#{{ str_pad($recibo->numero_recibo, 8, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="bg-light p-3 rounded text-center border">
                                <span class="text-uppercase small opacity-75 d-block">Fecha</span>
                                <span class="fw-bold fs-5">{{ $recibo->fecha ? $recibo->fecha->format('d/m/Y') : $recibo->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="bg-light p-3 rounded text-center border">
                                <span class="text-uppercase small opacity-75 d-block">Cajero / Usuario</span>
                                <span class="fw-bold fs-5">{{ $recibo->user->name ?? 'Sistema' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Composición de Medios de Pago -->
                    <h5 class="fw-bold mb-3 mt-5">Composición del Pago</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Método</th>
                                    <th>Referencia / Banco</th>
                                    <th>Fechas (Emisión / Acred.)</th>
                                    <th class="text-end">Monto Aplicado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($recibo->pagos->count() > 0)
                                    @foreach($recibo->pagos as $p)
                                    <tr>
                                        <td class="fw-semibold">
                                            @if(str_contains(strtolower($p->metodo_pago), 'efectivo'))
                                                <i class="fas fa-money-bill-wave text-success me-2"></i>
                                            @elseif(str_contains(strtolower($p->metodo_pago), 'transferencia'))
                                                <i class="fas fa-university text-primary me-2"></i>
                                            @elseif(str_contains(strtolower($p->metodo_pago), 'tarjeta'))
                                                <i class="fas fa-credit-card text-warning me-2"></i>
                                            @else
                                                <i class="fas fa-wallet text-secondary me-2"></i>
                                            @endif
                                            {{ $p->metodo_pago }}
                                        </td>
                                        <td>
                                            @if($p->referencia)
                                                <span class="badge bg-light border text-dark">{{ $p->referencia }}</span>
                                            @endif
                                            @if($p->banco)
                                                <small class="d-block mt-1 text-muted"><i class="fas fa-building me-1"></i> {{ $p->banco }}</small>
                                            @endif
                                            @if(!$p->referencia && !$p->banco)
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($p->fecha_emision || $p->fecha_acreditacion)
                                                @if($p->fecha_emision) <small class="d-block text-muted">Emi: {{ \Carbon\Carbon::parse($p->fecha_emision)->format('d/m/Y') }}</small> @endif
                                                @if($p->fecha_acreditacion) <small class="d-block fw-bold text-success">Acr: {{ \Carbon\Carbon::parse($p->fecha_acreditacion)->format('d/m/Y') }}</small> @endif
                                            @else
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold">${{ number_format($p->monto, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="fw-semibold">{{ $recibo->metodo_pago }}</td>
                                        <td>
                                            @if($recibo->referencia)
                                                <span class="badge bg-light border text-dark">{{ $recibo->referencia }}</span>
                                            @else
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">N/A</td>
                                        <td class="text-end fw-bold">${{ number_format($recibo->monto_total, 2, ',', '.') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold text-uppercase small">Total General</td>
                                    <td class="text-end fw-bold text-success fs-5">${{ number_format($recibo->monto_total, 2, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Referencias -->
@if($recibo->pagos->count() > 0)
<div class="modal fade" id="modalEditReferences" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('empresa.pagos.update_references', $recibo->id) }}" method="POST" class="modal-content border-0 shadow-lg rounded-3">
            @csrf
            <div class="modal-header border-bottom py-4 px-4 bg-light">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2 text-warning"></i> Cargar Referencias y Datos de Valores</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info border-0 shadow-sm mb-4">
                    <i class="fas fa-info-circle me-1"></i> Aquí puedes actualizar los números de comprobante, banco de los cheques y las fechas de acreditación. Los montos no se pueden alterar para mantener la integridad contable.
                </div>

                @foreach($recibo->pagos as $p)
                <div class="card mb-3 shadow-sm border-light">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <span class="fw-bold">{{ $p->metodo_pago }}</span>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success fs-6">${{ number_format($p->monto, 2, ',', '.') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small text-uppercase fw-bold opacity-75">Nro Referencia / Comprobante</label>
                                <input type="text" name="pagos[{{ $p->id }}][referencia]" class="form-control" value="{{ $p->referencia }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-uppercase fw-bold opacity-75">Banco Expidiente</label>
                                <input type="text" name="pagos[{{ $p->id }}][banco]" class="form-control" value="{{ $p->banco }}" placeholder="Ej: Banco Galicia">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-uppercase fw-bold opacity-75">Fecha de Emisión</label>
                                <input type="date" name="pagos[{{ $p->id }}][fecha_emision]" class="form-control" value="{{ $p->fecha_emision ? \Carbon\Carbon::parse($p->fecha_emision)->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-uppercase fw-bold opacity-75">Fecha de Acreditación / Venc.</label>
                                <input type="date" name="pagos[{{ $p->id }}][fecha_acreditacion]" class="form-control" value="{{ $p->fecha_acreditacion ? \Carbon\Carbon::parse($p->fecha_acreditacion)->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="modal-footer bg-light p-3 border-top">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm"><i class="fas fa-save me-2"></i> Guardar Referencias</button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection
