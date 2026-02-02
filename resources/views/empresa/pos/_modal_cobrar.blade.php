{{-- MODAL COBRAR --}}
<div class="modal fade" id="modalCobrar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">
                    Confirmar venta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body">

                {{-- RESUMEN --}}
                <div class="border rounded p-3 mb-3 bg-light">
                    <div class="d-flex justify-content-between">
                        <span>Total de la venta</span>
                        <strong>$ <span id="modal-total">0.00</span></strong>
                    </div>
                </div>

                {{-- MÉTODO DE PAGO --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Forma de pago</label>
                    <select class="form-select" id="payment_method">
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="qr">QR</option>
                    </select>
                </div>

                {{-- EFECTIVO --}}
                <div id="cash-section" class="mb-3">
                    <label class="form-label">Paga con</label>
                    <input type="number"
                           class="form-control"
                           id="cash_received"
                           placeholder="Ingrese monto recibido">
                </div>

                <div id="change-section" class="text-end text-muted d-none">
                    Vuelto: $ <span id="cash_change">0.00</span>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button class="btn btn-success fw-bold" id="confirm-sale">
                    Confirmar venta
                </button>
            </div>

        </div>
    </div>
</div>
