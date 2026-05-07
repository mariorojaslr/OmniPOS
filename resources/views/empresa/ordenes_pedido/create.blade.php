@extends('layouts.empresa')

@section('styles')
<style>
    .form-label {
        color: #4b5563;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 0.3rem;
    }
    .item-row:hover {
        background: #f9fafb;
    }
    .instruction-box {
        font-size: 0.75rem;
        border-color: #e5e7eb;
        background-color: #fff;
    }
    .price-diff {
        font-size: 0.7rem;
        font-weight: 700;
    }
    .badge-manual {
        background-color: #fef3c7;
        color: #92400e;
        font-size: 0.6rem;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 800;
        text-transform: uppercase;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 pb-5" x-data="ordenPedidoForm()">

    {{-- HEADER --}}
    <div class="mb-3 mt-3 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="fw-bold mb-0" style="font-size: 1.6rem; letter-spacing: -0.5px; color: #111827;">
                Nueva Orden de Pedido <span class="badge bg-light text-dark ms-2 border small" style="font-size: 0.6rem; font-weight: 400;">OP-SISTEMA</span>
            </h1>
            <p class="text-muted small mb-0">Especificaciones técnicas y comparativa de costos</p>
        </div>
        <a href="{{ route('empresa.ordenes-pedido.index') }}" class="btn btn-light btn-sm border px-3 text-dark shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> VOLVER
        </a>
    </div>

    <form action="{{ route('empresa.ordenes-pedido.store') }}" method="POST" class="bg-white border rounded-3 shadow-sm p-4">
        @csrf
        
        <div class="row g-3 mb-4">
            <div class="col-md-5">
                <label class="form-label">Proveedor</label>
                <select name="proveedor_id" x-model="proveedor_id" class="form-select border-secondary-subtle" required @change="updateAllPrices()">
                    <option value="">-- Seleccionar Proveedor --</option>
                    @foreach($proveedores as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha del Pedido</label>
                <input type="date" name="fecha" class="form-control border-secondary-subtle" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <div class="table-responsive border rounded-3 mb-3">
            <table class="table table-hover align-top mb-0" style="font-size: 0.85rem;">
                <thead class="bg-light text-dark">
                    <tr>
                        <th class="ps-3 py-3" style="min-width: 300px;">ARTÍCULO / DESCRIPCIÓN TÉCNICA</th>
                        <th class="py-3 text-center" style="width: 100px;">CANT.</th>
                        <th class="py-3 text-end" style="width: 160px;">PRECIO UNIT.</th>
                        <th class="py-3 text-end" style="width: 160px;">SUBTOTAL</th>
                        <th class="py-3 text-center" style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="item-row border-bottom">
                            <td class="ps-3 py-3">
                                <div class="d-flex flex-column gap-2">
                                    {{-- Selección de Producto --}}
                                    <div class="input-group input-group-sm">
                                        <select x-model="item.product_id" @change="onProductSelect(item)" class="form-select fw-bold border-secondary-subtle" :name="'items['+index+'][product_id]'">
                                            <option value="">-- Producto Manual / No listado --</option>
                                            <template x-for="p in productos" :key="p.id">
                                                <option :value="p.id" x-text="p.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    
                                    {{-- Nombre / Descripción (Editable si es manual o si se quiere ajustar) --}}
                                    <input type="text" x-model="item.descripcion" :name="'items['+index+'][descripcion]'" 
                                           class="form-control form-control-sm border-secondary-subtle fw-semibold" 
                                           placeholder="Nombre del artículo o servicio..." required>
                                    
                                    {{-- Campo de Instrucciones (LO QUE PIDIÓ EL USUARIO) --}}
                                    <textarea x-model="item.instrucciones" :name="'items['+index+'][instrucciones]'" 
                                              class="form-control instruction-box" rows="2" 
                                              placeholder="Añadir instrucciones especiales de procesamiento (ej: laminado, corte, etc.)"></textarea>
                                    
                                    {{-- Opción guardar como producto interno --}}
                                    <div x-show="!item.product_id" class="d-flex align-items-center gap-2 mt-1">
                                        <input type="checkbox" :id="'save_'+index" :name="'items['+index+'][save_as_product]'" value="1">
                                        <label :for="'save_'+index" class="x-small text-muted fw-bold">Guardar como nuevo Artículo Privado (Insumo)</label>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <input type="number" x-model.number="item.qty" @input="calculateTotal()" class="form-control text-center" :name="'items['+index+'][qty]'" min="0.01" step="0.01" required>
                            </td>
                            <td class="py-3">
                                <div class="d-flex flex-column align-items-end">
                                    <input type="number" x-model.number="item.price" @input="calculateTotal()" class="form-control text-end" :name="'items['+index+'][price]'" step="0.01" required>
                                    
                                    {{-- Comparativa de Precios (LO QUE PIDIÓ EL USUARIO) --}}
                                    <div x-show="item.product_id && proveedor_id" class="mt-1 text-end">
                                        <div x-show="item.last_price > 0">
                                            <span class="text-muted x-small">Anterior: $<span x-text="numberFormat(item.last_price)"></span></span>
                                            <span :class="item.diff_percent > 0 ? 'text-danger' : 'text-success'" class="price-diff">
                                                (<span x-text="item.diff_percent > 0 ? '+' : ''"></span><span x-text="item.diff_percent"></span>%)
                                            </span>
                                        </div>
                                        <div x-show="item.last_price == 0" class="text-muted x-small italic">Sin historial</div>
                                        <input type="hidden" :name="'items['+index+'][precio_anterior]'" :value="item.last_price">
                                    </div>
                                </div>
                            </td>
                            <td class="text-end fw-bold text-dark pe-3 fs-6 py-3">
                                $ <span x-text="numberFormat(item.qty * item.price)"></span>
                            </td>
                            <td class="text-center pe-2 py-3">
                                <button type="button" @click="removeItem(index)" class="btn btn-sm btn-outline-danger border-0" x-show="items.length > 1">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <button type="button" @click="addItem()" class="btn btn-outline-primary btn-sm px-4 fw-bold rounded-pill mb-4">
            <i class="bi bi-plus-lg me-1"></i> AGREGAR OTRO ARTÍCULO
        </button>

        <div class="row g-4 mt-2">
            <div class="col-md-7">
                <label class="form-label">Notas Generales de la Orden</label>
                <textarea name="notas_generales" class="form-control border-secondary-subtle" rows="3" placeholder="Información adicional para el proveedor sobre el pedido completo..."></textarea>
            </div>
            <div class="col-md-5">
                <div class="card bg-light border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fw-bold x-small text-uppercase">Subtotal Estimado:</span>
                            <span class="text-dark fw-bold">$ <span x-text="numberFormat(total)"></span></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4 pt-2 border-top">
                            <span class="text-dark fw-bold h6 mb-0">TOTAL ESTIMADO:</span>
                            <span class="h3 fw-bold text-success mb-0">$ <span x-text="numberFormat(total)"></span></span>
                        </div>
                        
                        <input type="hidden" name="total_final" :value="total">

                        <button type="submit" class="btn btn-warning w-100 fw-bold py-3 shadow-sm">
                            <i class="bi bi-save2 me-2"></i> GUARDAR ORDEN DE PEDIDO
                        </button>
                        <p class="text-center text-muted x-small mt-2 mb-0">Podrás enviarla al proveedor en el siguiente paso.</p>
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
    function ordenPedidoForm() {
        return {
            items: [
                { product_id: '', qty: 1, price: 0, last_price: 0, diff_percent: 0, descripcion: '', instrucciones: '' }
            ],
            productos: @json($productos),
            proveedor_id: '',
            total: 0,
            
            addItem() {
                this.items.push({ product_id: '', qty: 1, price: 0, last_price: 0, diff_percent: 0, descripcion: '', instrucciones: '' });
                this.calculateTotal();
            },
            
            removeItem(index) {
                this.items.splice(index, 1);
                this.calculateTotal();
            },
            
            onProductSelect(item) {
                const prod = this.productos.find(p => p.id == item.product_id);
                if (prod) {
                    item.descripcion = prod.name;
                    // El precio lo cargamos como 0 o el precio de venta actual, pero lo ideal es buscar el de compra
                    item.price = prod.cost || 0; 
                    this.fetchLastPrice(item);
                } else {
                    item.descripcion = '';
                    item.price = 0;
                    item.last_price = 0;
                    item.diff_percent = 0;
                }
                this.calculateTotal();
            },

            async fetchLastPrice(item) {
                if (!item.product_id || !this.proveedor_id) {
                    item.last_price = 0;
                    item.diff_percent = 0;
                    return;
                }

                try {
                    const response = await fetch(`{{ route('empresa.ordenes-pedido.index') }}/last-price?product_id=${item.product_id}&proveedor_id=${this.proveedor_id}`);
                    const data = await response.json();
                    item.last_price = data.price || 0;
                    this.calculateDiff(item);
                } catch (e) {
                    console.error("Error al buscar precio anterior", e);
                }
            },

            updateAllPrices() {
                this.items.forEach(item => {
                    if (item.product_id) this.fetchLastPrice(item);
                });
            },

            calculateDiff(item) {
                if (item.last_price > 0 && item.price > 0) {
                    const diff = item.price - item.last_price;
                    item.diff_percent = ((diff / item.last_price) * 100).toFixed(1);
                } else {
                    item.diff_percent = 0;
                }
            },
            
            calculateTotal() {
                this.total = this.items.reduce((acc, item) => {
                    this.calculateDiff(item);
                    return acc + (item.qty * item.price);
                }, 0);
            },
            
            numberFormat(num) {
                return new Intl.NumberFormat('es-AR', { minimumFractionDigits: 2 }).format(num);
            }
        }
    }
</script>
@endsection
