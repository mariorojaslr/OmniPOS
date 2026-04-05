@extends('layouts.empresa')

@section('content')

<div class="container-fluid py-3">

    {{-- CABECERA --}}
    <div class="mb-3">
        <h2 class="fw-bold mb-0">Nuevo Cliente</h2>
        <small class="text-muted">Alta de cliente en el sistema</small>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST" action="{{ route('empresa.clientes.store') }}">
                @csrf

                <div class="row g-3">

                    {{-- NOMBRE --}}
                    <div class="col-md-6">
                        <label class="form-label">Nombre / Razón social *</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               required
                               value="{{ old('name') }}">
                    </div>

                    {{-- DOCUMENTO / LUPA --}}
                    <div class="col-md-3">
                        <label class="form-label">Documento / CUIT</label>
                        <div class="input-group">
                            <input type="text"
                                   name="document"
                                   id="documentInput"
                                   class="form-control"
                                   value="{{ old('document') }}"
                                   placeholder="CUIT sin guiones">
                            <button class="btn btn-primary" type="button" id="btnSearchCuit" title="Buscar en AFIP">
                                <span class="spinner-border spinner-border-sm d-none" id="searchSpinner"></span>
                                🚀
                            </button>
                        </div>
                    </div>

                    {{-- TELEFONO --}}
                    <div class="col-md-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone') }}">
                    </div>

                    {{-- EMAIL --}}
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email') }}">
                    </div>

                    {{-- CONDICION IVA --}}
                    <div class="col-md-3">
                        <label class="form-label">Condición fiscal *</label>
                        <select name="tax_condition" id="taxConditionSelect" class="form-select" required>
                            <option value="consumidor_final">Consumidor Final</option>
                            <option value="responsable_inscripto">Responsable Inscripto</option>
                            <option value="monotributo">Monotributo</option>
                            <option value="exento">Exento</option>
                        </select>
                    </div>

                    {{-- DIRECCION (Agregada para autocompletar) --}}
                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="address" id="addressInput" class="form-control" value="{{ old('address') }}">
                    </div>

                    {{-- TIPO DE CLIENTE --}}
                    <div class="col-md-3">
                        <label class="form-label">Tipo de cliente *</label>
                        <select name="type" class="form-select" required>
                            <option value="consumidor_final">Consumidor Final</option>
                            <option value="minorista" selected>Minorista (Normal)</option>
                            <option value="mayorista">Mayorista</option>
                            <option value="revendedor">Revendedor</option>
                            <option value="amigo">Amigo / VIP</option>
                        </select>
                    </div>

                    {{-- LIMITE CREDITO --}}
                    <div class="col-md-3">
                        <label class="form-label">Límite crédito</label>
                        <input type="number"
                               step="0.01"
                               name="credit_limit"
                               class="form-control"
                               value="{{ old('credit_limit',0) }}">
                    </div>

                </div>

                {{-- BOTONES --}}
                <div class="mt-4 d-flex gap-2">

                    <button type="submit" class="btn btn-success">
                        Guardar cliente
                    </button>

                    <a href="{{ route('empresa.clientes.index') }}"
                       class="btn btn-secondary">
                       Cancelar
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
document.getElementById('btnSearchCuit').addEventListener('click', function() {
    let cuit = document.getElementById('documentInput').value.trim();
    if(!cuit) return alert("Ingresa un CUIT primero");

    const btn = this;
    const spinner = document.getElementById('searchSpinner');
    
    btn.disabled = true;
    spinner.classList.remove('d-none');

    fetch(`{{ route('empresa.tax.search_cuit') }}?cuit=${cuit}`)
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                // Autocompletar campos
                document.querySelector('input[name="name"]').value = res.data.nombre;
                document.getElementById('addressInput').value = res.data.direccion + ' ' + (res.data.localidad || '');
                
                // Mapear Condición IVA
                const cond = res.data.condicion_iva.toLowerCase();
                const select = document.getElementById('taxConditionSelect');
                
                if(cond.includes('inscripto')) select.value = 'responsable_inscripto';
                else if(cond.includes('monotributo')) select.value = 'monotributo';
                else if(cond.includes('exento')) select.value = 'exento';
                else select.value = 'consumidor_final';

                // Sugerir tipo de cliente
                if(cond.includes('inscripto')) document.querySelector('select[name="type"]').value = 'mayorista';

            } else {
                alert("Error AFIP: " + res.error);
            }
        })
        .catch(err => alert("Error técnico: " + err))
        .finally(() => {
            btn.disabled = false;
            spinner.classList.add('d-none');
        });
});
</script>
@endsection
