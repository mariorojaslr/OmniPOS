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
        margin-bottom: 8px;
    }
    .item-row:hover {
        background: rgba(255, 255, 255, 0.05);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 pb-5" x-data="presupuestoForm()">

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

    <form action="{{ route('empresa.presupuestos.store') }}" method="POST" class="glass-form shadow-2xl">
        @csrf
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <label class="form-label">Seleccionar Cliente</label>
                <select name="client_id" class="form-control form-control-premium" required>
                    <option value="">Cliente Ocasional / Final</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha de Emisión</label>
                <input type="date" name="fecha" class="form-control form-control-premium" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Validez (Días)</label>
                <input type="number" name="validez" class="form-control form-control-premium" value="15" required>
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
                            <th style="width: 50%;">Producto / Servicio</th>
                            <th class="text-center" style="width: 100px;">Cant.</th>
                            <th class="text-end" style="width: 150px;">Precio Unit.</th>
                            <th class="text-end" style="width: 150px;">Subtotal</th>
                            <th style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="item-row">
                                <td>
                                    <select x-model="item.product_id" @change="updatePrice(item)" class="form-control form-control-premium" :name="'items['+index+'][product_id]'">
                                        <option value="">-- Buscar producto o servicio --</option>
                                        <template x-for="p in productos" :key="p.id">
                                            <option :value="p.id" x-text="p.nombre"></option>
                                        </template>
                                    </select>
                                    <input type="hidden" :name="'items['+index+'][descripcion]'" :value="item.descripcion">
                                </td>
                                <td class="text-center">
                                    <input type="number" x-model.number="item.qty" @input="calculateTotal()" class="form-control form-control-premium text-center mx-auto" :name="'items['+index+'][qty]'" min="1" step="0.01">
                                </td>
                                <td class="text-end">
                                    <input type="number" x-model.number="item.price" @input="calculateTotal()" class="form-control form-control-premium text-end ms-auto" :name="'items['+index+'][price]'" step="0.01">
                                </td>
                                <td class="text-end fw-bold text-white">
                                    $ <span x-text="numberFormat(item.qty * item.price)"></span>
                                </td>
                                <td class="text-center">
                                    <button type="button" @click="removeItem(index)" class="btn btn-sm btn-link text-danger p-0" x-show="items.length > 1">
                                        <i class="bi bi-trash-fill fs-5"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <button type="button" @click="addItem()" class="btn btn-outline-info btn-sm rounded-pill mt-3 px-4">
                <i class="bi bi-plus-circle me-2"></i> AGREGAR LÍNEA
            </button>
        </div>

        {{-- TOTALES --}}
        <div class="row mt-5 pt-4 border-top border-white border-opacity-10">
            <div class="col-md-6">
                <label class="form-label">Observaciones internas / Nota al cliente</label>
                <textarea name="notas" class="form-control form-control-premium" rows="4" placeholder="Algún comentario especial para el presupuesto..."></textarea>
            </div>
            <div class="col-md-6 text-end">
                <div class="d-flex justify-content-end align-items-center mb-2">
                    <span class="text-white-50 me-4">Subtotal Neto:</span>
                    <span class="fs-5 text-white fw-bold">$ <span x-text="numberFormat(total)"></span></span>
                </div>
                <div class="d-flex justify-content-end align-items-center mb-4">
                    <span class="text-white-50 me-4" style="font-size: 1.2rem;">TOTAL FINAL:</span>
                    <span class="fs-2 fw-bold text-info">$ <span x-text="numberFormat(total)"></span></span>
                </div>
                
                <input type="hidden" name="total_final" :value="total">

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <button type="button" class="btn btn-outline-light px-5 py-3 rounded-pill fw-bold" style="letter-spacing: 1px;">GUARDAR COMO BORRADOR</button>
                    <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-lg" style="letter-spacing: 1px;">
                        <i class="bi bi-send-fill me-2"></i> CONFIRMAR Y ENVIAR
                    </button>
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
            items: [
                { product_id: '', qty: 1, price: 0, descripcion: '' }
            ],
            productos: @json($productos),
            total: 0,
            
            addItem() {
                this.items.push({ product_id: '', qty: 1, price: 0, descripcion: '' });
                this.calculateTotal();
            },
            
            removeItem(index) {
                this.items.splice(index, 1);
                this.calculateTotal();
            },
            
            updatePrice(item) {
                const prod = this.productos.find(p => p.id == item.product_id);
                if (prod) {
                    item.price = prod.precio_venta || 0;
                    item.descripcion = prod.nombre;
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

