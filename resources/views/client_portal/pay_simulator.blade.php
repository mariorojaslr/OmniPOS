<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasarela de Pago | {{ $empresa->nombre_comercial }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f0f2f5; font-family: 'Inter', sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .pay-card { background: white; border-radius: 1.5rem; padding: 2.5rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); max-width: 450px; width: 100%; border-top: 6px solid #28a745; }
        .logo-placeholder { font-size: 2.5rem; color: #28a745; margin-bottom: 1.5rem; }
        .amount-display { font-size: 2.5rem; font-weight: 850; color: #1e293b; letter-spacing: -1px; margin-bottom: 0.5rem; }
        .btn-confirm { background: #28a745; color: white; border: none; padding: 1rem; border-radius: 1rem; font-weight: 700; font-size: 1.1rem; transition: all 0.2s; width: 100%; margin-top: 1.5rem; }
        .btn-confirm:hover { background: #218838; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(40, 167, 69, 0.3); }
        .secure-badge { background: #f8fafc; padding: 0.75rem; border-radius: 0.75rem; font-size: 0.75rem; color: #64748b; margin-top: 1.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
    </style>
</head>
<body>

    <div class="pay-card text-center">
        <div class="logo-placeholder">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h5 class="fw-bold mb-1">Finalizar Pago</h5>
        <p class="text-muted small mb-4">Estás por pagar a <strong>{{ $empresa->nombre_comercial }}</strong></p>

        <form action="{{ route('client.portal.index', ['token' => $token]) }}" method="GET">
            <div class="bg-light p-4 rounded-4 mb-3 text-start">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Concepto:</span>
                    <span class="fw-bold small">{{ $venta->numero_comprobante ?? 'Venta #'.$venta->id }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Cliente:</span>
                    <span class="fw-bold small">{{ $client->name }}</span>
                </div>
                <hr>
                <div class="text-center">
                    <span class="text-muted small d-block mb-2">IMPORTE A PAGAR</span>
                    <div class="input-group input-group-lg mb-2">
                        <span class="input-group-text bg-white border-end-0 text-muted">$</span>
                        @php 
                            // Buscamos el registro contable de forma manual para evitar fallos de relación
                            $ledger = \App\Models\ClientLedger::where('reference_id', $venta->id)
                                        ->where('reference_type', 'like', '%Venta')
                                        ->first();
                                        
                            $montoPendiente = $ledger ? ($ledger->amount - ($ledger->imputaciones->sum('monto_aplicado') ?? 0)) : $venta->total_con_iva;
                        @endphp
                        <input type="number" step="0.01" class="form-control border-start-0 fw-bold text-center" 
                               name="amount" id="pay_amount" 
                               value="{{ $montoPendiente }}" 
                               style="font-size: 2rem; color: #1e293b;">
                    </div>
                    <p class="x-small text-muted mb-0">Puedes editar el monto para realizar un pago parcial.</p>
                </div>
            </div>

            <p class="x-small text-muted mb-0" style="font-size: 0.7rem;">
                * Este es un simulador de pasarela de pago para validar la infraestructura del portal MultiPOS.
            </p>

            <button type="submit" class="btn-confirm">
                SIMULAR PAGO EXITOSO
            </button>
        </form>

        <div class="secure-badge">
            <i class="fas fa-lock"></i> Pago procesado de forma segura por MultiPOS Pay
        </div>

        <a href="{{ route('client.portal.index', ['token' => $token]) }}" class="text-decoration-none text-muted small d-block mt-3">
            Cancelar y volver al portal
        </a>
    </div>

</body>
</html>
