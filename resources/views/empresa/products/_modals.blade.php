{{-- MODALES REUTILIZADOS --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('empresa.products.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Importar Artículos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small">Seleccione su archivo CSV (separador punto y coma).</p>
                    <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary fw-bold">Procesar Archivo</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL ETIQUETA RÁPIDA --}}
<div class="modal fade" id="modalEtiquetaRapida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow border-0" style="border-radius: 1rem;">
            <form id="formEtiquetaRapida" action="{{ route('empresa.labels.generate') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="items[]" id="modal_product_id">
                <input type="hidden" name="selected_items[0]" id="modal_product_id_alt">
                
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Impresión de Etiquetas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="p-3 mb-3 bg-light rounded text-center">
                        <h6 class="fw-bold mb-0" id="modal_product_name"></h6>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase">Formato</label>
                        <select name="format" class="form-select form-select-sm">
                            <option value="small">Pequeña (A4)</option>
                            <option value="medium" selected>Mediana (A4)</option>
                            <option value="large">Grande (A4)</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold text-uppercase">Cantidad</label>
                        <input type="number" name="dynamic_qty" id="modal_qty_oled" value="10" min="1" max="999" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow">
                        GENERAR PDF DE ETIQUETAS
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
