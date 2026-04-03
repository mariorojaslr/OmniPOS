@extends('layouts.empresa')

@section('content')

{{-- =========================================================
   CONTROL DE STOCK
   ========================================================= --}}

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="fw-bold mb-0">Control de Stock</h2>
        <small class="text-muted">Inventario en tiempo real</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('empresa.inventory_scan') }}" class="btn btn-outline-primary d-flex align-items-center shadow-sm">
            <span class="fs-5 me-2">📲</span> Escáner Móvil
        </a>
        <a href="{{ route('empresa.stock.faltantes') }}" class="btn btn-primary d-flex align-items-center shadow-sm">
            <span class="fs-5 me-2">🤖</span> Centro de Reposición Inteligente
        </a>
    </div>
</div>


{{-- =========================================================
   KPIs DE INVENTARIO
   ========================================================= --}}

<div class="row mb-3">

    <div class="col-md-4">
        <div class="card border-success shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-success mb-1">OK</h6>
                <h3 class="fw-bold mb-0">{{ $ok }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-warning shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-warning mb-1">BAJO</h6>
                <h3 class="fw-bold mb-0">{{ $bajo }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-danger shadow-sm text-center">
            <div class="card-body">
                <h6 class="text-danger mb-1">CRÍTICO</h6>
                <h3 class="fw-bold mb-0">{{ $critico }}</h3>
            </div>
        </div>
    </div>

</div>


{{-- =========================================================
   BUSCADOR Y FILTROS
   ========================================================= --}}

<div class="card shadow-sm mb-3">
    <div class="card-body d-flex gap-3 align-items-center">

        <form method="GET" id="formBusqueda" class="d-flex gap-3 align-items-center w-100">

            <input type="text"
                   name="q"
                   id="buscador"
                   class="form-control"
                   placeholder="Buscar producto en toda la base..."
                   value="{{ request('q') }}"
                   style="max-width:350px"
                   autocomplete="off">

            <select name="estado"
                    id="filtroEstado"
                    class="form-select"
                    style="max-width:200px">
                <option value="">Todos los estados</option>
                <option value="ok" {{ request('estado')=='ok'?'selected':'' }}>OK</option>
                <option value="bajo" {{ request('estado')=='bajo'?'selected':'' }}>Bajo</option>
                <option value="critico" {{ request('estado')=='critico'?'selected':'' }}>Crítico</option>
            </select>

            <select name="filas"
                    class="form-select"
                    style="width:150px"
                    onchange="this.form.submit()">

                @foreach([5,10,20,50,100] as $n)
                    <option value="{{ $n }}"
                        {{ request('filas',20)==$n ? 'selected' : '' }}>
                        {{ $n }} filas
                    </option>
                @endforeach

            </select>

        </form>

    </div>
</div>


{{-- =========================================================
   TABLA DE INVENTARIO
   ========================================================= --}}

<div class="card shadow-sm border-0">
<div class="card-body p-0">

<table class="table table-hover mb-0 align-middle text-center">

<thead class="table-light">
<tr>

<th class="text-start">Producto</th>
<th width="120">Stock</th>
<th width="120">Mínimo</th>
<th width="120">Ideal</th>
<th width="140">Estado</th>
<th width="130">Kardex</th>
<th width="120">Guardar</th>

</tr>
</thead>


<tbody>

@forelse($productos as $p)

@php

$stock = $p->stock ?? 0;
$min   = $p->stock_min ?? 0;
$ideal = $p->stock_ideal ?? 0;

if($stock <= 0){
    $estado = ['CRÍTICO','danger'];
}
elseif($stock <= $min){
    $estado = ['BAJO','warning'];
}
else{
    $estado = ['OK','success'];
}

@endphp


<tr>

<td class="text-start fw-semibold">

<a href="{{ route('empresa.stock.kardex', $p->id) }}"
class="text-decoration-none text-dark">

{{ $p->name }}

</a>

</td>


<td>

{{ number_format($stock,2) }}

</td>


<form method="POST"
action="{{ route('empresa.stock.config',$p->id) }}">

@csrf


<td>

<input type="number"
step="0.01"
name="minimo"
value="{{ $min }}"
class="form-control form-control-sm text-center">

</td>


<td>

<input type="number"
step="0.01"
name="ideal"
value="{{ $ideal }}"
class="form-control form-control-sm text-center">

</td>


<td>

<span class="badge bg-{{ $estado[1] }}">
{{ $estado[0] }}
</span>

</td>


<td>

<a href="{{ route('empresa.stock.kardex', $p->id) }}"
class="btn btn-sm btn-outline-dark">

Kardex

</a>

</td>


<td>

<button class="btn btn-sm btn-success">
Guardar
</button>

</td>

</form>


</tr>


@empty

<tr>
<td colspan="7" class="text-center text-muted py-4">
No hay productos
</td>
</tr>

@endforelse


</tbody>
</table>

</div>
</div>


{{-- =========================================================
   PAGINACIÓN
   ========================================================= --}}

<div class="mt-3 d-flex justify-content-center">

{{ $productos->withQueryString()->links('pagination::bootstrap-5') }}

</div>

@endsection



@section('scripts')

<script>

document.addEventListener("DOMContentLoaded", function(){

const buscador = document.getElementById("buscador");
const form = document.getElementById("formBusqueda");

let timer = null;

buscador.addEventListener("keyup", function(){

clearTimeout(timer);

timer = setTimeout(function(){

form.submit();

},400);

});

});

</script>

@endsection
