@extends('layouts.empresa')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Editar Comprobante de Compra</h2>
        <small class="text-muted">Modificando metadatos (tipo, número, fecha y proveedor)</small>
    </div>

    <a href="{{ route('empresa.compras.index') }}" class="btn btn-secondary">
        Volver
    </a>
</div>

<form method="POST" action="{{ route('empresa.compras.update', $purchase->id) }}" id="formEditCompra">
@csrf
@method('PUT')

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Proveedor (Actual: {{ $purchase->supplier->name ?? '-' }})</label>
                <select name="supplier_id" class="form-select" required>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected($purchase->supplier_id == $supplier->id)>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Fecha</label>
                <input type="date" name="purchase_date" class="form-control"
                       value="{{ $purchase->purchase_date ? \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d') : date('Y-m-d') }}" required>
            </div>

            <div class="col-md-2 mb-3">
                <label class="form-label fw-semibold">Comprobante</label>
                <select name="invoice_type" class="form-select">
                    <option value="A" @selected($purchase->invoice_type == 'A')>Factura A</option>
                    <option value="B" @selected($purchase->invoice_type == 'B')>Factura B</option>
                    <option value="C" @selected($purchase->invoice_type == 'C')>Factura C</option>
                    <option value="NC" @selected($purchase->invoice_type == 'NC')>Nota de Crédito</option>
                    <option value="T" @selected($purchase->invoice_type == 'T')>Ticket</option>
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Sucursal / Número</label>
                <input type="text"
                       id="invoice_number"
                       name="invoice_number"
                       class="form-control"
                       value="{{ $purchase->invoice_number }}"
                       placeholder="0000-00000000"
                       maxlength="13">
            </div>

        </div>
    </div>
</div>

<div class="alert alert-warning d-flex align-items-center mb-4">
    <div class="me-3 fs-3">ℹ️</div>
    <div>
        <strong>Nota importante:</strong> Esta edición solo afecta la cabecera del comprobante (identificación).
        Los productos, sus cantidades, el stock y el monto total de la compra <strong>no se alteran</strong> mediante este formulario.
        Para corregir cantidades o montos erróneos, por favor utilice una Nota de Crédito.
    </div>
</div>

<div class="text-end">
    <button type="submit" class="btn btn-primary btn-lg px-5">
        Actualizar Comprobante
    </button>
</div>

</form>

@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function(){
    const input = document.getElementById("invoice_number");
    if(!input) return;

    input.addEventListener("blur", function(){
        let value = input.value.trim();
        if(value === "") return;

        let parts = value.split("-");
        let suc = (parts[0] ?? "").replace(/\D/g,"");
        let num = (parts[1] ?? "").replace(/\D/g,"");

        suc = suc.padStart(4,"0").substring(0,4);
        num = num.padStart(8,"0").substring(0,8);

        input.value = suc + "-" + num;
    });
});
</script>
@endsection
