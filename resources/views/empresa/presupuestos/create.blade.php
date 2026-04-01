@extends('layouts.app')

@section('styles')
<style>
    .glass-form {
        background: rgba(30, 41, 59, 0.45);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 2.5rem;
    }
    .form-label {
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
    }
    .form-control-premium {
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #f8fafc !important;
        border-radius: 12px !important;
        padding: 12px 18px !important;
    }
    .form-control-premium:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.2) !important;
    }
    .item-row {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        transition: all 0.2s ease;
    }
    .item-row:hover {
        background: rgba(255, 255, 255, 0.05);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 pb-5">

    {{-- HEADER --}}
    <div class="mb-5 mt-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="stat-label mb-1 text-primary">Generador de Cotizaciones</h5>
            <h1 class="fw-bold text-white mb-0" style="font-size: 2.5rem; letter-spacing: -1.5px;">
                Nueva <span class="text-info">Referencia Comercial</span>
            </h1>
        </div>
        <a href="{{ route('empresa.presupuestos.index') }}" class="btn btn-outline-light rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i> VOLVER AL LISTADO
        </a>
    </div>

    <form class="glass-form shadow-2xl">
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label class="form-label">Seleccionar Cliente</label>
                <select class="form-control form-control-premium">
                    <option>Cliente Ocasional / Final</option>
                    {{-- Iterar clientes aquí --}}
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha de Emisión</label>
                <input type="date" class="form-control form-control-premium" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Validez (Días)</label>
                <input type="number" class="form-control form-control-premium" value="15">
            </div>
        </div>

        <hr class="opacity-10 my-5">

        {{-- GRILLA DE PRODUCTOS --}}
        <div class="mb-4">
            <h6 class="fw-bold text-white-50 mb-4 text-uppercase letter-spacing-1">Detalle de Productos / Servicios</h6>
            
            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead class="text-white-50 small">
                        <tr>
                            <th style="width: 50%;">Producto</th>
                            <th class="text-center">Cant.</th>
                            <th class="text-end">Precio Unit.</th>
                            <th class="text-end">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="item-row">
                            <td>
                                <input type="text" class="form-control form-control-premium" placeholder="Buscar producto o servicio...">
                            </td>
                            <td class="text-center">
                                <input type="number" class="form-control form-control-premium mx-auto" style="width: 80px;" value="1">
                            </td>
                            <td class="text-end">
                                <input type="text" class="form-control form-control-premium text-end ms-auto" style="width: 120px;" value="0.00">
                            </td>
                            <td class="text-end fw-bold text-white">$ 0.00</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-link text-danger"><i class="bi bi-trash-fill"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <button type="button" class="btn btn-outline-info btn-sm rounded-pill mt-3 px-3">
                <i class="bi bi-plus-circle me-1"></i> AGREGAR LÍNEA
            </button>
        </div>

        {{-- TOTALES --}}
        <div class="row mt-5 pt-4 border-top border-white border-opacity-10">
            <div class="col-md-6">
                <label class="form-label">Observaciones internas / Nota al cliente</label>
                <textarea class="form-control form-control-premium" rows="3"></textarea>
            </div>
            <div class="col-md-6 text-end">
                <div class="d-flex justify-content-end align-items-center mb-2">
                    <span class="text-white-50 me-4">Subtotal Neto:</span>
                    <span class="fs-5 text-white fw-bold">$ 0,00</span>
                </div>
                <div class="d-flex justify-content-end align-items-center mb-4">
                    <span class="text-white-50 me-4" style="font-size: 1.2rem;">TOTAL FINAL:</span>
                    <span class="fs-2 fw-bold text-info">$ 0,00</span>
                </div>
                
                <div class="d-flex justify-content-end gap-3">
                    <button type="button" class="btn btn-outline-light px-4 py-2 rounded-pill">GUARDAR COMO BORRADOR</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow">
                        <i class="bi bi-check-circle-fill me-2"></i> CONFIRMAR Y ENVIAR
                    </button>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection
