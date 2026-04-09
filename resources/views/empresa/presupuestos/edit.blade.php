@extends('layouts.empresa')

@section('styles')
<style>
    .card-premium {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        padding: 2rem;
    }
    .form-label {
        color: #4b5563;
        font-weight: 600;
        text-transform: none;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
    .form-control-premium {
        background: #f9fafb !important;
        border: 1px solid #d1d5db !important;
        color: #111827 !important;
        border-radius: 8px !important;
        padding: 10px 14px !important;
    }
    .form-control-premium:focus {
        border-color: var(--color-primario) !important;
        box-shadow: 0 0 0 3px rgba(var(--color-primario-rgb), 0.1) !important;
        background: #fff !important;
    }
    .item-row {
        background: #fff;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }
    .item-row:hover {
        background: #f9fafb;
    }
    .btn-primary {
        background: var(--color-primario) !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 pb-5" x-data="presupuestoForm()">

    {{-- HEADER --}}
    <div class="mb-2 mt-3 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="fw-bold mb-0" style="font-size: 1.6rem; letter-spacing: -0.5px; color: #111827;">
                Editar Presupuesto <span class="badge bg-primary text-white ms-2 border-0 small" style="font-size: 0.9rem; font-weight: 400;">#{{ $presupuesto->numero }}</span>
            </h1>
            <p class="text-muted small mb-0">Modifica los ítems o condiciones de la cotización</p>
        </div>
        <div class="d-flex gap-2">
            {{-- CLONAR DESDE EDICIÓN --}}
            <form action="{{ route('empresa.presupuestos.clone', $presupuesto->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-info btn-sm px-3"
                    onclick="return confirm('¿Clonar este presupuesto como nuevo?')">
                    <i class="bi bi-copy me-1"></i> Clonar
                </button>
            </form>
            <a href="{{ route('empresa.presupuestos.index') }}" class="btn btn-light btn-sm border px-3 text-dark shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> VOLVER AL LISTADO
            </a>
        </div>
    </div>

    {{-- ALERTAS --}}
    @if(session('info'))
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-3">
            <i class="bi bi-copy fs-5 me-2"></i> {{ session('info') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-3">{{ session('error') }}</div>
    @endif

    <form action="{{ route('empresa.presupuestos.update', $presupuesto->id) }}" method="POST" class="bg-white border rounded-3 shadow-sm p-3">
        @csrf
        @method('PUT')
        
        {{-- FILA 1: CLIENTE Y FECHAS --}}
        <div class="row g-2 mb-2">
            <div class="col-md-5">
                <label class="form-label small fw-bold mb-1" style="color: #111827;">Seleccionar Cliente</label>
                <select name="client_id" class="form-select form-select-sm border-secondary-subtle" @change="updateClientInfo($event.target.value)">
                    <option value="">Cliente Ocasional / Final</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ $presupuesto->client_id == $cliente->id ? 'selected' : '' }}>{{ $cliente->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1" style="color: #111827;">Fecha de Emisión</label>
                <input type="date" name="fecha" class="form-control form-control-sm border-secondary-subtle" value="{{ $presupuesto->fecha->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1" style="color: #111827;">Validez (Días)</label>
                @php
                    $validez = $presupuesto->vencimiento ? $presupuesto->fecha->diffInDays($presupuesto->vencimiento) : 15;
                @endphp
                <input type="number" name="validez" class="form-control form-control-sm border-secondary-subtle" value="{{ $validez }}" required>
            </div>
        </div>

        {{-- INFO DEL CLIENTE --}}
        <div x-show="selectedClient" 
             class="mb-3 py-1 px-3 bg-light rounded-2 border d-flex gap-4 align-items-center text-dark" 
             x-transition style="font-size: 0.75rem; margin-top: -2px;">
            <div class="fw-bold text-success"><i class="bi bi-person-check-fill me-1"></i> <span x-text="selectedClient?.name"></span></div>
            <div class="opacity-75"><i class="bi bi-envelope me-1"></i> <span x-text="selectedClient?.email || '-'"></span></div>
            <div class="opacity-75"><i class="bi bi-telephone me-1"></i> <span x-text="selectedClient?.phone || '-'"></span></div>
            <div class="opacity-75"><i class="bi bi-geo-alt me-1"></i> <span x-text="selectedClient?.address || '-'"></span></div>
        </div>

        {{-- GRILLA DE PRODUCTOS --}}
        <div class="table-responsive border rounded-2 mb-2">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3 py-2 text-uppercase text-muted" style="font-size: 0.65rem; font-weight: 800;">Producto / Servicio</th>
                        <th class="py-2 text-uppercase text-muted text-center" style="width: 100px; font-size: 0.65rem; font-weight: 800;">Cant.</th>
                        <th class="py-2 text-uppercase text-muted text-end" style="width: 150px; font-size: 0.65rem; font-weight: 800;">Precio Unit.</th>
                        <th class="py-2 text-uppercase text-muted text-end" style="width: 150px; font-size: 0.65rem; font-weight: 800;">Subtotal</th>
                        <th class="py-2 text-center" style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="border-bottom">
                            <td class="ps-2 py-1">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="bi bi-upc-scan"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" placeholder="Scanner / Código..." 
                                           @keydown.enter.prevent="lookupBarcode($event.target.value, item); $event.target.value = ''"
                                           style="max-width: 140px; font-size: 0.75rem;">
                                    
                                    <select x-model="item.product_id" @change="updatePrice(item)" class="form-select fw-bold ms-1" :name="'items['+index+'][product_id]'">
                                        <option value="">-- Buscar producto --</option>
                                        <template x-for="p in productos" :key="p.id">
                                            <option :value="p.id" x-text="p.name" :selected="p.id == item.product_id"></option>
                                        </template>
                                    </select>
                                </div>
                                <input type="hidden" :name="'items['+index+'][descripcion]'" :value="item.descripcion">
                            </td>
                            <td class="py-1">
                                <input type="number" x-model.number="item.qty" @input="calculateTotal()" class="form-control form-control-sm text-center mx-auto" :name="'items['+index+'][qty]'" min="1" step="0.01">
                            </td>
                            <td class="py-1">
                                <input type="number" x-model.number="item.price" @input="calculateTotal()" class="form-control form-control-sm text-end ms-auto" :name="'items['+index+'][price]'" step="0.01">
                            </td>
                            <td class="py-1 text-end fw-bold text-dark pe-3">
                                $ <span x-text="numberFormat(item.qty * item.price)"></span>
                            </td>
                            <td class="py-1 text-center pe-2">
                                <button type="button" @click="removeItem(index)" class="btn btn-sm btn-light border rounded-circle" style="width: 28px; height: 28px; padding:0;" x-show="items.length > 1">
                                    <i class="bi bi-trash text-danger" style="font-size: 0.75rem;"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <button type="button" @click="addItem()" class="btn btn-primary btn-sm px-3 fw-bold shadow-sm rounded-pill mb-4 mt-2">
            <i class="bi bi-plus-lg me-1"></i> AGREGAR LÍNEA
        </button>

        <div class="row g-3 align-items-end">
            <div class="col-md-7">
                <label class="form-label small fw-bold" style="color: #111827;">Observaciones internas / Nota al cliente</label>
                <textarea name="notas" class="form-control border-secondary-subtle" rows="2" placeholder="Algún comentario especial..." style="font-size: 0.8rem;">{{ $presupuesto->notas }}</textarea>
            </div>
            <div class="col-md-5 text-end">
                <div class="p-3 bg-light rounded-3 border">
                    <div class="d-flex justify-content-end align-items-center mb-1">
                        <span class="text-muted me-4 small text-uppercase fw-bold" style="letter-spacing: 0.5px;">Subtotal Neto:</span>
                        <span class="text-dark fw-bold">$ <span x-text="numberFormat(total)"></span></span>
                    </div>
                    <div class="d-flex justify-content-end align-items-center mb-3">
                        <span class="text-dark me-4 fw-bold" style="font-size: 1rem; letter-spacing: 0.5px;">TOTAL FINAL:</span>
                        <span class="fs-2 fw-bold text-success" style="letter-spacing: -1.5px;">$ <span x-text="numberFormat(total)"></span></span>
                    </div>
                    
                    <input type="hidden" name="total_final" :value="total">

                    <div class="d-flex justify-content-end gap-2 mt-2 flex-wrap">
                        <button type="submit" name="status" value="pendiente" class="btn btn-primary btn-sm px-4 fw-bold shadow-sm">
                            <i class="bi bi-save-fill me-2"></i> ACTUALIZAR
                        </button>
                    </div>
                    {{-- CONVERTIR EN FACTURA --}}
                    <div class="mt-2">
                        <form action="{{ route('empresa.presupuestos.convertir_factura', $presupuesto->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm"
                                onclick="return confirm('¿Convertir en Factura?\n\nEl presupuesto quedará como ACEPTADO y se abrirá el facturador manual con los ítems pre-cargados.')">
                                <i class="bi bi-receipt-cutoff me-2"></i> CONVERTIR EN FACTURA
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection

@section('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    function presupuestoForm() {
        return {
            items: @json($itemsData),
            productos: @json($productos),
            clientes: @json($clientes),
            selectedClient: @json($presupuesto->client),
            total: {{ $presupuesto->total }},
            
            addItem() {
                this.items.push({ product_id: '', qty: 1, price: 0, descripcion: '' });
                this.calculateTotal();
            },
            
            removeItem(index) {
                this.items.splice(index, 1);
                this.calculateTotal();
            },

            updateClientInfo(clientId) {
                this.selectedClient = this.clientes.find(c => c.id == clientId);
            },
            
            lookupBarcode(barcode, item) {
                if (!barcode) return;
                const prod = this.productos.find(p => p.barcode === barcode || p.sku === barcode || p.id == barcode);
                if (prod) {
                    item.product_id = prod.id;
                    this.updatePrice(item);
                } else {
                    alert('Producto no encontrado con el código: ' + barcode);
                }
            },
            
            updatePrice(item) {
                const prod = this.productos.find(p => p.id == item.product_id);
                if (prod) {
                    item.price = prod.price || 0;
                    item.descripcion = prod.name;
                }
                this.calculateTotal();
            },
            
            calculateTotal() {
                this.total = this.items.reduce((acc, item) => acc + (item.qty * item.price), 0);
            },
            
            numberFormat(num) {
                return new Intl.NumberFormat('es-AR', { minimumFractionDigits: 2 }).format(num);
            }
        }
    }
</script>
@endsection
