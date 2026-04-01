@extends('layouts.app')

@section('styles')
<style>
    .presu-header {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.4), rgba(15, 23, 42, 0.6));
        border-bottom: 2px solid rgba(59, 130, 246, 0.3);
        padding: 3rem 0;
        margin-bottom: 3rem;
        border-radius: 0 0 40px 40px;
    }
    .glass-stat {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    .glass-stat:hover {
        background: rgba(255, 255, 255, 0.07);
        transform: translateY(-5px);
        border-color: rgba(59, 130, 246, 0.5);
    }
    .table-glass {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        overflow: hidden;
    }
    .table-glass th {
        background: rgba(59, 130, 246, 0.1);
        color: #94a3b8;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 2px;
        border: none;
        padding: 1.2rem;
    }
    .table-glass td {
        padding: 1.2rem;
        color: #f8fafc;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        vertical-align: middle;
    }
    .gradient-text-gold {
        background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .gradient-text-blue {
        background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 pb-5">
    
    {{-- HEADER PREMIUM --}}
    <div class="d-flex justify-content-between align-items-end mb-5 mt-4">
        <div>
            <h5 class="stat-label mb-1 text-primary animate__animated animate__fadeInLeft">Módulo Comercial Elite</h5>
            <h1 class="fw-bold text-white mb-0" style="font-size: 3rem; letter-spacing: -2px;">
                Gestión de <span class="gradient-text-blue">Presupuestos</span>
            </h1>
        </div>
        <div class="text-end">
            <a href="{{ route('empresa.presupuestos.create') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-lg animate-pulse">
                <i class="bi bi-magic me-2"></i> GENERAR NUEVA COTIZACIÓN
            </a>
        </div>
    </div>

    {{-- INDICADORES FLASH --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="glass-stat text-center">
                <div class="stat-label">Total Emitidos</div>
                <div class="fs-2 fw-bold text-white">0</div>
                <div class="small text-muted">Histórico Global</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-stat text-center" style="border-bottom: 3px solid #fbbf24;">
                <div class="stat-label">Pendientes</div>
                <div class="fs-2 fw-bold gradient-text-gold">0</div>
                <div class="small text-muted">A la espera de cierre</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-stat text-center" style="border-bottom: 3px solid #22c55e;">
                <div class="stat-label">Convertidos</div>
                <div class="fs-2 fw-bold text-success">0</div>
                <div class="small text-muted">Exito Comercial</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-stat text-center" style="border-bottom: 3px solid #ef4444;">
                <div class="stat-label">Vencidos</div>
                <div class="fs-2 fw-bold text-danger">0</div>
                <div class="small text-muted">Requieren Seguimiento</div>
            </div>
        </div>
    </div>

    {{-- TABLA DE CRISTAL --}}
    <div class="table-glass shadow-2xl">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Ref #</th>
                    <th>Cliente / Prospecto</th>
                    <th>Fecha Emisión</th>
                    <th>Vencimiento</th>
                    <th class="text-end">Monto Total</th>
                    <th class="text-center">Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- Mock para que no se vea vacío y "feo" --}}
                <tr class="opacity-30">
                    <td><span class="badge bg-secondary">PRE-0000</span></td>
                    <td><i>Inicie un presupuesto para ver datos...</i></td>
                    <td>-- / -- / --</td>
                    <td>-- / -- / --</td>
                    <td class="text-end fw-bold">$ 0.00</td>
                    <td class="text-center"><span class="badge bg-dark border border-secondary">ESPERA</span></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-light rounded-circle"><i class="bi bi-three-dots"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection

