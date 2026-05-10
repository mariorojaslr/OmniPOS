<div class="receipt-instance">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="text-muted x-small fw-bold opacity-50" style="font-size: 10px;">{{ $type }}</div>
        <div class="x-small text-muted" style="font-size: 10px;">Generado: {{ date('d/m/Y H:i') }}</div>
    </div>

    <div class="receipt-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="receipt-title">RECIBO DE PAGO</h1>
            <div class="receipt-id">Liquidación #{{ str_pad($liquidacion->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="text-end">
            <div class="fw-bold text-dark" style="font-size: 14px;">{{ Auth::user()->empresa->nombre }}</div>
            <div class="x-small text-muted" style="font-size: 10px;">Comprobante Interno de Rendición</div>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-4">
            <div class="data-label">Profesional</div>
            <div class="data-value">{{ $liquidacion->profesional->name }}</div>
        </div>
        <div class="col-4 text-center">
            <div class="data-label">Periodo Liquidado</div>
            <div class="data-value">
                {{ \Carbon\Carbon::parse($liquidacion->periodo_desde)->format('d/m') }} - 
                {{ \Carbon\Carbon::parse($liquidacion->periodo_hasta)->format('d/m/Y') }}
            </div>
        </div>
        <div class="col-4 text-end">
            <div class="data-label">Fecha de Cierre</div>
            <div class="data-value">{{ $liquidacion->created_at->format('d/m/Y') }}</div>
        </div>
    </div>

    <table class="table table-compact mb-2">
        <thead>
            <tr>
                <th>FECHA</th>
                <th>CLIENTE</th>
                <th>SERVICIO</th>
                <th class="text-end">MONTO</th>
                <th class="text-end">COMISIÓN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($liquidacion->turnos as $turno)
            <tr>
                <td>{{ \Carbon\Carbon::parse($turno->fecha)->format('d/m') }}</td>
                <td>{{ $turno->cliente ? $turno->cliente->name : ($turno->cliente_nombre_manual ?? 'S/D') }}</td>
                <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    {{ $turno->servicio->nombre }}
                </td>
                <td class="text-end text-muted">${{ number_format($turno->monto, 0, ',', '.') }}</td>
                <td class="text-end fw-bold">${{ number_format($turno->comision_monto, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row align-items-center">
        <div class="col-7">
            <div class="d-flex gap-4 mt-2">
                <div class="text-center">
                    <div class="signature-line"></div>
                    <div class="x-small fw-bold mt-1" style="font-size: 9px;">Firma Profesional</div>
                </div>
                <div class="text-center">
                    <div class="signature-line"></div>
                    <div class="x-small fw-bold mt-1" style="font-size: 9px;">Sello Empresa</div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="totals-box">
                <div class="d-flex justify-content-between mb-1 opacity-75" style="font-size: 10px;">
                    <span>Servicios ({{ $liquidacion->turnos->count() }}):</span>
                    <span>${{ number_format($liquidacion->turnos->sum('monto'), 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold small">TOTAL PAGO:</span>
                    <span class="h5 mb-0 fw-bold">${{ number_format($liquidacion->monto_total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
