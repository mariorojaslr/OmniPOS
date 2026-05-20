@extends('layouts.empresa')

@section('styles')
<style>
    .metric-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08) !important;
    }
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    @media print {
        #sidebar, .top-bar, .no-print, #help-trigger-fixed {
            display: none !important;
        }
        body {
            background: white !important;
            color: black !important;
            padding: 0 !important;
        }
        .container-fluid {
            padding: 0 !important;
        }
        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Encabezado / Migas de Pan -->
    <div class="mb-4 d-flex justify-content-between align-items-center no-print">
        <div>
            <a href="{{ route('empresa.affiliates.show', $client->id) }}" class="text-muted text-decoration-none small fw-bold">
                <i class="bi bi-arrow-left me-1"></i> VOLVER A LA FICHA
            </a>
            <h2 class="fw-bold mt-2 mb-0 text-dark">Estado de Cuenta</h2>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-primary fw-bold rounded-pill shadow-sm">
                <i class="bi bi-printer me-2"></i> IMPRIMIR
            </button>
            <a href="{{ route('empresa.affiliates.show', $client->id) }}" class="btn btn-light border fw-bold rounded-pill shadow-sm">
                <i class="bi bi-person me-2"></i> VER PERFIL
            </a>
        </div>
    </div>

    <!-- Sección de Impresión (Header solo para el PDF/Impresora) -->
    <div class="d-none d-print-block mb-5 border-bottom pb-3">
        <div class="row align-items-center">
            <div class="col-6">
                <h3 class="fw-bold mb-1 text-primary">{{ auth()->user()->empresa->nombre_comercial ?? 'OmniPOS MedPlus' }}</h3>
                <p class="text-muted small mb-0">CUIT: {{ auth()->user()->empresa->cuit ?? 'N/A' }}</p>
                <p class="text-muted small mb-0">Reporte de Estado de Cuenta de Afiliado</p>
            </div>
            <div class="col-6 text-end">
                <p class="small mb-1 fw-bold">Fecha de Emisión: {{ now()->format('d/m/Y H:i') }}</p>
                <p class="small text-muted mb-0">Emitido por: {{ auth()->user()->name }}</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Tarjeta de Perfil Rápido -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-body p-0">
                    <!-- Banner Visual con Inicial o Foto -->
                    <div class="p-4 text-center bg-light border-bottom position-relative">
                        <div class="mx-auto mb-3 rounded-circle border border-4 border-white shadow-sm overflow-hidden" style="width: 110px; height: 110px;">
                            @if($client->photo_path)
                                @if(str_starts_with($client->photo_path, 'local:'))
                                    <img src="{{ asset('storage/' . str_replace('local:', '', $client->photo_path)) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @elseif(str_starts_with($client->photo_path, 'bunny:'))
                                    <img src="{{ config('filesystems.disks.bunny_storage.url') }}/{{ str_replace('bunny:', '', $client->photo_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <img src="{{ config('filesystems.disks.bunny_storage.url') }}/{{ $client->photo_path }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            @else
                                <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white fs-2 fw-bold">
                                    {{ substr($client->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <h4 class="fw-bold mb-1 text-dark">{{ $client->name }}</h4>
                        <p class="text-muted small mb-2">Credencial N°: {{ $client->affiliate_number ?? 'No asignado' }}</p>
                        
                        <span class="badge bg-{{ $client->affiliate_status == 'active' ? 'success' : ($client->affiliate_status == 'overdue' ? 'warning' : 'danger') }} rounded-pill px-3">
                            <span class="status-dot bg-white me-1"></span>
                            @if($client->affiliate_status == 'active') ACTIVO @elseif($client->affiliate_status == 'overdue') MOROSO @else INACTIVO @endif
                        </span>
                    </div>

                    <!-- Datos Adicionales -->
                    <div class="p-4">
                        <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted small fw-bold">DNI / Documento</span>
                            <span class="fw-bold text-dark">{{ $client->document }}</span>
                        </div>
                        <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted small fw-bold">Plan Contratado</span>
                            <span class="fw-bold text-primary">{{ $client->affiliatePlan->name ?? 'Sin Plan Asociado' }}</span>
                        </div>
                        <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted small fw-bold">Cuota Mensual</span>
                            <span class="fw-bold text-dark">${{ number_format($client->monthly_fee ?? 0, 2, ',', '.') }}</span>
                        </div>
                        <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted small fw-bold">Afiliado desde</span>
                            <span class="fw-bold text-dark">{{ $client->affiliate_since ? \Carbon\Carbon::parse($client->affiliate_since)->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="mb-0 d-flex justify-content-between">
                            <span class="text-muted small fw-bold">Obra Social</span>
                            <span class="fw-bold text-dark text-end">{{ $client->obraSocial->nombre ?? 'Ninguna' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métricas y Listado de Cuotas -->
        <div class="col-lg-8">
            <!-- Fila de Métricas Financieras -->
            @php
                $totalEmitido = $fees->sum('amount');
                $totalPagado = $fees->where('status', 'paid')->sum('amount');
                $totalPendiente = $fees->where('status', '!=', 'paid')->sum('amount');
                $totalVencido = $fees->where('status', 'overdue')->sum('amount');
                
                $cantidadTotal = $fees->count();
                $cantidadPagadas = $fees->where('status', 'paid')->count();
                $cantidadPendientes = $fees->where('status', '!=', 'paid')->count();
            @endphp
            <div class="row g-3 mb-4">
                <!-- Total Pagado -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white metric-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Pagado</span>
                                <h3 class="fw-extrabold text-success mb-0">${{ number_format($totalPagado, 2, ',', '.') }}</h3>
                                <small class="text-muted">{{ $cantidadPagadas }} de {{ $cantidadTotal }} cuotas pagadas</small>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success rounded-4 p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Saldo Pendiente -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white metric-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Saldo Pendiente</span>
                                <h3 class="fw-extrabold text-warning mb-0">${{ number_format($totalPendiente, 2, ',', '.') }}</h3>
                                <small class="text-muted">{{ $cantidadPendientes }} cuotas pendientes</small>
                            </div>
                            <div class="bg-warning bg-opacity-10 text-warning rounded-4 p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                                <i class="bi bi-clock-history"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Saldo Vencido -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white metric-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Monto Vencido</span>
                                <h3 class="fw-extrabold text-danger mb-0">${{ number_format($totalVencido, 2, ',', '.') }}</h3>
                                <small class="text-muted">Requiere regularización</small>
                            </div>
                            <div class="bg-danger bg-opacity-10 text-danger rounded-4 p-3 fs-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                                <i class="bi bi-exclamation-octagon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Historial de Cuotas -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Historial de Cuotas del Plan</h5>
                    <span class="badge bg-light text-muted border rounded-pill px-3">{{ $cantidadTotal }} Registro(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 small fw-bold text-muted text-uppercase">Período</th>
                                    <th class="py-3 small fw-bold text-muted text-uppercase">Monto</th>
                                    <th class="py-3 small fw-bold text-muted text-uppercase">Vencimiento</th>
                                    <th class="py-3 small fw-bold text-muted text-uppercase text-center">Estado</th>
                                    <th class="py-3 small fw-bold text-muted text-uppercase">Fecha Pago</th>
                                    <th class="py-3 small fw-bold text-muted text-uppercase">Método</th>
                                    <th class="text-end pe-4 py-3 small fw-bold text-muted text-uppercase no-print">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fees as $fee)
                                    <tr>
                                        <!-- Período -->
                                        <td class="ps-4 fw-bold">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-calendar3 text-muted small"></i>
                                                <span>{{ \Carbon\Carbon::parse($fee->period . '-01')->translatedFormat('F Y') }}</span>
                                            </div>
                                        </td>
                                        
                                        <!-- Monto -->
                                        <td class="fw-extrabold text-dark">
                                            ${{ number_format($fee->amount, 2, ',', '.') }}
                                        </td>

                                        <!-- Vencimiento -->
                                        <td>
                                            {{ \Carbon\Carbon::parse($fee->due_date)->format('d/m/Y') }}
                                        </td>

                                        <!-- Estado -->
                                        <td class="text-center">
                                            @if($fee->status == 'paid')
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold">
                                                    <i class="bi bi-check-circle-fill me-1"></i> PAGADA
                                                </span>
                                            @elseif($fee->status == 'overdue' || (\Carbon\Carbon::parse($fee->due_date)->isPast() && $fee->status != 'paid'))
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-bold">
                                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> VENCIDA
                                                </span>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-bold">
                                                    <i class="bi bi-clock-fill me-1"></i> PENDIENTE
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Fecha de Pago -->
                                        <td class="text-muted small">
                                            @if($fee->paid_at)
                                                {{ \Carbon\Carbon::parse($fee->paid_at)->format('d/m/Y H:i') }}
                                            @else
                                                <span class="text-muted opacity-50">—</span>
                                            @endif
                                        </td>

                                        <!-- Método de Pago -->
                                        <td class="small">
                                            @if($fee->payment_method)
                                                <span class="badge bg-light text-dark border px-2 py-1 rounded-3">
                                                    {{ strtoupper($fee->payment_method) }}
                                                </span>
                                            @else
                                                <span class="text-muted opacity-50">—</span>
                                            @endif
                                        </td>

                                        <!-- Acción -->
                                        <td class="text-end pe-4 no-print">
                                            @if($fee->status != 'paid')
                                                <button onclick="simularPago('{{ $fee->id }}', '{{ $fee->period }}', '{{ $fee->amount }}')" class="btn btn-sm btn-success rounded-pill px-3 fw-bold">
                                                    <i class="bi bi-cash me-1"></i> Cobrar
                                                </button>
                                            @else
                                                <button onclick="verComprobante('{{ $fee->id }}', '{{ $fee->period }}', '{{ $fee->amount }}', '{{ $fee->paid_at }}', '{{ $fee->payment_method }}')" class="btn btn-sm btn-light border rounded-pill px-3 fw-bold">
                                                    <i class="bi bi-receipt me-1"></i> Ver Recibo
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="bi bi-receipt fs-1 d-block mb-3 opacity-25"></i>
                                            <span class="fw-bold d-block">No se registran cuotas emitidas</span>
                                            <span class="small opacity-75">Las cuotas se generarán mensualmente según el plan contratado.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function simularPago(feeId, period, amount) {
        // Formatear el periodo
        let dateObj = new Date(period + '-02'); // Evitamos problemas de zona horaria restando un dia
        let options = { month: 'long', year: 'numeric' };
        let formattedPeriod = dateObj.toLocaleDateString('es-ES', options);

        Swal.fire({
            title: 'Registrar Cobro de Cuota',
            html: `
                <div class="text-start">
                    <p class="mb-3 text-muted small">Registra el ingreso de dinero para la cuota correspondiente a <strong>\${formattedPeriod}</strong>.</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Monto Recibido</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control fw-bold" id="swal-amount" value="\${parseFloat(amount).toFixed(2)}" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Método de Pago</label>
                        <select class="form-select" id="swal-method">
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia Bancaria</option>
                            <option value="debito">Tarjeta de Débito</option>
                            <option value="credito">Tarjeta de Crédito</option>
                            <option value="mutual">Mutual / Descuento Planilla</option>
                        </select>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-check-circle me-1"></i> Confirmar Pago',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: 'var(--color-primario, #10b981)',
            focusConfirm: false,
            preConfirm: () => {
                return {
                    method: document.getElementById('swal-method').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Como no existe una ruta directa de backend expuesta en el controlador para actualizar de forma asíncrona la cuota individualmente,
                // enviamos una simulación exitosa interactiva muy profesional que le indica al usuario cómo proceder, o bien, si en el futuro se implementa
                // el guardado, estará listo para realizar la petición fetch.
                
                Swal.fire({
                    icon: 'info',
                    title: 'Integración de Cobros',
                    text: 'Para registrar el cobro formal de la cuota en caja de tesorería y emitir factura electrónica A/B o comprobante oficial de caja, dirígete al módulo de Caja & Finanzas o ingresa a Ventas > Ventas Manuales.',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: 'var(--color-primario, #10b981)'
                });
            }
        });
    }

    function verComprobante(feeId, period, amount, paidAt, method) {
        let dateObj = new Date(period + '-02');
        let options = { month: 'long', year: 'numeric' };
        let formattedPeriod = dateObj.toLocaleDateString('es-ES', options);

        let paidDate = new Date(paidAt);
        let formattedPaidAt = paidDate.toLocaleDateString('es-ES') + ' a las ' + paidDate.toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'});

        Swal.fire({
            title: '<span class="text-success"><i class="bi bi-shield-check"></i> Comprobante de Cobro</span>',
            html: `
                <div class="p-3 border rounded-4 bg-light text-start">
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="text-muted small">Concepto:</span>
                        <strong class="text-dark">Cuota de Plan MedPlus (\${formattedPeriod})</strong>
                    </div>
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="text-muted small">Monto Total:</span>
                        <strong class="text-success fs-5">$\${parseFloat(amount).toLocaleString('es-AR', {minimumFractionDigits: 2})}</strong>
                    </div>
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="text-muted small">Fecha de Cobro:</span>
                        <span class="fw-bold text-dark">\${formattedPaidAt}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Medio de Pago:</span>
                        <span class="badge bg-dark text-white text-uppercase">\${method}</span>
                    </div>
                </div>
                <p class="text-muted small mt-3 mb-0"><i class="bi bi-info-circle"></i> Este es un registro administrative del módulo de afiliados.</p>
            `,
            confirmButtonText: 'Cerrar',
            confirmButtonColor: 'var(--color-primario, #0d6efd)'
        });
    }
</script>
@endsection
