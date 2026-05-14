@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-white mb-0">Panel del Revendedor</h2>
            <p class="text-white opacity-50">Bienvenido, {{ auth()->user()->name }}. Aquí está el resumen de tu red.</p>
        </div>
        <div class="bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-pill px-4 py-2">
            <span class="text-primary fw-bold small">ID AGENTE: #{{ str_pad(auth()->id(), 4, '0', STR_PAD_LEFT) }}</span>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="glass-card p-4">
                <div class="stat-label">Empresas Activadas</div>
                <div class="h2 fw-bold mb-0 text-white">{{ $totalClients }}</div>
                <p class="small text-white opacity-50 mb-0">Cuentas bajo tu gestión.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4">
                <div class="stat-label">Volumen Mensual (MRR)</div>
                <div class="h2 fw-bold mb-0 text-success">$ {{ number_format($totalMRR, 0, ',', '.') }}</div>
                <p class="small text-white opacity-50 mb-0">Facturación total de tu red.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4" style="border-top: 3px solid #f59e0b;">
                <div class="stat-label">Comisión Estimada (10%)</div>
                <div class="h2 fw-bold mb-0 text-warning">$ {{ number_format($estimatedCommission, 0, ',', '.') }}</div>
                <p class="small text-white opacity-50 mb-0">Tu ganancia este mes.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4">
                <div class="stat-label">Nivel de Agente</div>
                <div class="h2 fw-bold mb-0 text-info">Partner Gold</div>
                <p class="small text-white opacity-50 mb-0">Próximo nivel: Platinum (20 cli.)</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Listado de Empresas --}}
        <div class="col-lg-8">
            <div class="glass-card">
                <div class="p-4 border-bottom border-white border-opacity-10 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Mi Cartera de Empresas</h5>
                    <button class="btn btn-sm btn-primary rounded-pill px-3">Registrar Nueva Empresa</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0" style="--bs-table-bg: transparent;">
                        <thead class="text-muted small">
                            <tr>
                                <th class="ps-4">EMPRESA</th>
                                <th>PLAN</th>
                                <th>ESTADO</th>
                                <th>VENCIMIENTO</th>
                                <th class="text-end pe-4">COMISIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $emp)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $emp->nombre_comercial }}</div>
                                    <div class="small opacity-50">{{ $emp->email }}</div>
                                </td>
                                <td>{{ $emp->plan->nombre ?? 'Básico' }}</td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">ACTIVA</span>
                                </td>
                                <td>{{ optional($emp->fecha_vencimiento)->format('d/m/Y') ?? 'N/A' }}</td>
                                <td class="text-end pe-4 fw-bold text-success">$ {{ number_format(($emp->plan->precio ?? 0) * 0.1, 0) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Aún no tienes empresas registradas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Últimos Pagos --}}
        <div class="col-lg-4">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold mb-4">Últimos Cobros en tu Red</h5>
                <div class="d-flex flex-column gap-3">
                    @forelse($latestPayments as $pay)
                    <div class="d-flex align-items-center gap-3 p-3 rounded-4 bg-white bg-opacity-5 border border-white border-opacity-5">
                        <div class="bg-success bg-opacity-20 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold small">{{ $pay->empresa->nombre_comercial }}</div>
                            <div class="x-small opacity-50">{{ $pay->created_at->format('d/m/Y') }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">$ {{ number_format($pay->monto, 0) }}</div>
                            <div class="x-small text-warning">Com: $ {{ number_format($pay->monto * 0.1, 0) }}</div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-5">No hay pagos recientes.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    color: white;
}
.stat-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.5;
    margin-bottom: 0.5rem;
}
.x-small { font-size: 0.7rem; }
</style>
@endsection
