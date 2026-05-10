@extends('layouts.empresa')

@section('styles')
<style>
    /* Estilos base para pantalla */
    .receipt-wrapper {
        background: #f1f5f9;
        padding: 2rem 0;
        min-height: 100vh;
    }
    .receipt-card {
        background: white;
        width: 210mm; /* Ancho A4 */
        margin: 0 auto;
        padding: 10mm;
        border: 1px solid #e2e8f0;
        position: relative;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .troquelado {
        border-top: 1px dashed #cbd5e1;
        margin: 30px 0;
        position: relative;
        text-align: center;
    }
    .troquelado::after {
        content: 'TIJERA / TROQUELADO';
        position: absolute;
        top: -8px;
        left: 50%;
        transform: translateX(-50%);
        background: white;
        padding: 0 10px;
        font-size: 9px;
        color: #94a3b8;
        letter-spacing: 2px;
        font-weight: 800;
    }

    /* Diseño compacto */
    .receipt-header { border-bottom: 2px solid #1e293b; padding-bottom: 8px; margin-bottom: 12px; }
    .receipt-title { font-size: 16px; font-weight: 900; color: #1e293b; margin: 0; letter-spacing: -0.5px; }
    .receipt-id { font-size: 12px; color: #64748b; font-weight: 600; }
    
    .data-label { font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 1px; letter-spacing: 0.5px; }
    .data-value { font-size: 12px; font-weight: 700; color: #1e293b; }

    .table-compact th { font-size: 9px; padding: 6px 4px !important; background: #f8fafc !important; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .table-compact td { font-size: 11px; padding: 4px 4px !important; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }

    .totals-box { background: #1e293b; color: white; border-radius: 6px; padding: 10px; }
    .signature-line { border-top: 1px solid #cbd5e1; width: 130px; margin-top: 25px; }

    .receipt-instance {
        height: 135mm; /* Mitad de A4 aprox */
        overflow: hidden;
    }

    /* Estilos de Impresión */
    @media print {
        @page { size: A4; margin: 0; }
        body { background: white !important; }
        .receipt-wrapper { padding: 0; background: white !important; }
        .receipt-card { border: none; padding: 5mm 10mm; width: 100%; height: 297mm; box-shadow: none; }
        .d-print-none { display: none !important; }
        .btn, .navbar, .sidebar, .footer { display: none !important; }
        
        .receipt-instance {
            height: 140mm;
        }
    }
</style>
@endsection

@section('content')
<div class="receipt-wrapper">
    {{-- CONTROLES --}}
    <div class="container d-print-none">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('empresa.liquidaciones.index') }}" class="btn btn-light rounded-pill px-3 shadow-sm border">
                    <i class="bi bi-arrow-left me-2"></i>Volver al listado
                </a>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-dark fw-bold rounded-pill px-4 shadow">
                    <i class="bi bi-printer me-2"></i> IMPRIMIR RECIBOS (A4)
                </button>
                <button type="button" class="btn btn-outline-danger rounded-pill px-4" onclick="confirmDelete()">
                    ANULAR
                </button>
            </div>
        </div>
    </div>

    {{-- HOJA A4 --}}
    <div class="receipt-card">
        {{-- RECIBO 1 (COPIA EMPRESA) --}}
        @include('empresa.liquidaciones._receipt_content', ['type' => 'COPIA ADMINISTRACIÓN / EMPRESA'])

        <div class="troquelado"></div>

        {{-- RECIBO 2 (COPIA PROFESIONAL) --}}
        @include('empresa.liquidaciones._receipt_content', ['type' => 'COPIA PROFESIONAL / EMPLEADO'])
    </div>
</div>

{{-- Modal de Confirmación de Borrado --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Anular Liquidación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-exclamation-triangle-fill fs-1 text-danger mb-3"></i>
                <h5>¿Deseas invalidar este recibo?</h5>
                <p class="text-muted">Al anular, los turnos volverán a estar pendientes de liquidar.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <form id="deleteForm" method="POST" action="{{ route('empresa.liquidaciones.destroy', $liquidacion->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Mantener</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Anular Ahora</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDelete() {
        var myModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        myModal.show();
    }
</script>
@endsection
