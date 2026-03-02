@extends('layouts.empresa')

@section('content')

<div class="container-fluid py-3">

    {{-- =========================================================
       CABECERA
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-0">Clientes</h2>
            <small class="text-muted">Gestión de clientes y cuentas corrientes</small>
        </div>

        <a href="{{ route('empresa.clientes.create') }}" class="btn btn-primary">
            Nuevo cliente
        </a>
    </div>

    {{-- =========================================================
       BUSCADOR + PAGINADO
    ========================================================== --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body d-flex flex-wrap gap-2 align-items-center">

            <input type="text"
                   id="buscarCliente"
                   class="form-control"
                   placeholder="Buscar cliente..."
                   style="max-width:300px"
                   autocomplete="off">

            <select id="perPage" class="form-select" style="width:auto">
                @foreach([5,10,15,20,25,50,100] as $n)
                    <option value="{{ $n }}" {{ request('perPage',15)==$n?'selected':'' }}>
                        {{ $n }} por página
                    </option>
                @endforeach
            </select>

        </div>
    </div>

    {{-- =========================================================
       TABLA DE CLIENTES
    ========================================================== --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table align-middle mb-0 table-fixed">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Documento</th>
                            <th>Condición</th>
                            <th>Límite crédito</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody id="tablaClientes">

                        @foreach($clientes as $i => $c)
                        <tr class="{{ $i % 2 == 0 ? 'fila-blanca' : 'fila-celeste' }} cliente-row">

                            <td class="fw-semibold text-nowrap">{{ $c->name }}</td>
                            <td class="text-nowrap">{{ $c->email }}</td>
                            <td class="text-nowrap">{{ $c->phone }}</td>
                            <td class="text-nowrap">{{ $c->document }}</td>

                            <td class="text-nowrap">
                                {{ ucfirst(str_replace('_',' ',$c->type)) }}
                            </td>

                            <td class="text-nowrap">
                                ${{ number_format($c->credit_limit ?? 0,2) }}
                            </td>

                            <td>
                                @if($c->active)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>

                            {{-- ================= ACCIONES ================= --}}
                            <td class="text-end text-nowrap">

                                @if($c->document !== 'CF')

                                    <a href="{{ route('empresa.clientes.edit',$c->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                       Editar
                                    </a>

                                    <a href="{{ route('empresa.clientes.show',$c->id) }}"
                                       class="btn btn-sm btn-outline-success">
                                       Cuenta corriente
                                    </a>

                                @else

                                    <span class="badge bg-secondary">
                                        Cliente del sistema
                                    </span>

                                @endif

                            </td>

                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{-- PAGINADOR --}}
            <div class="p-3 d-flex justify-content-end w-100">
                {{ $clientes->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>

@endsection


{{-- =========================================================
   ESTILOS LOCALES
========================================================== --}}
@push('styles')
<style>

.table-fixed td, .table-fixed th {
    white-space: nowrap;
}

.fila-blanca {
    background: #ffffff;
}

.fila-celeste {
    background: #f2f8ff;
}

.cliente-row:hover {
    background: #e6f0ff;
}

mark {
    background: #fff3cd;
    padding: 0 2px;
}

</style>
@endpush


{{-- =========================================================
   BUSCADOR EN VIVO
========================================================== --}}
@section('scripts')

<script>

const input   = document.getElementById('buscarCliente');
const tabla   = document.getElementById('tablaClientes');
const perPage = document.getElementById('perPage');

const baseUrl = "{{ url('empresa/clientes') }}";

/* Cambio de paginado */
perPage.addEventListener('change',()=>{
    const q = input.value;
    window.location = `?perPage=${perPage.value}&q=${encodeURIComponent(q)}`;
});

/* Buscador dinámico */
input.addEventListener('input', function(){

    let q = this.value.trim();

    fetch(`?ajax=1&q=${encodeURIComponent(q)}&perPage=${perPage.value}`)
    .then(res=>res.json())
    .then(data=>{

        tabla.innerHTML='';

        data.forEach((c,i)=>{

            let nombre = c.name ?? '';

            if(q.length>0){
                const regex = new RegExp(`(${q})`,'gi');
                nombre = nombre.replace(regex, `<mark>$1</mark>`);
            }

            let tipo = c.type
                ? c.type.replace('_',' ').replace(/\b\w/g,l=>l.toUpperCase())
                : '';

            let limite = c.credit_limit
                ? parseFloat(c.credit_limit).toFixed(2)
                : "0.00";

            let acciones = '';

            if(c.document !== 'CF'){
                acciones = `
                    <a href="${baseUrl}/${c.id}/edit"
                       class="btn btn-sm btn-outline-primary">Editar</a>
                    <a href="${baseUrl}/${c.id}"
                       class="btn btn-sm btn-outline-success">Cuenta corriente</a>
                `;
            } else {
                acciones = `<span class="badge bg-secondary">Cliente del sistema</span>`;
            }

            tabla.innerHTML += `
            <tr class="${i%2==0?'fila-blanca':'fila-celeste'} cliente-row">
                <td class="fw-semibold text-nowrap">${nombre}</td>
                <td class="text-nowrap">${c.email ?? ''}</td>
                <td class="text-nowrap">${c.phone ?? ''}</td>
                <td class="text-nowrap">${c.document ?? ''}</td>
                <td class="text-nowrap">${tipo}</td>
                <td class="text-nowrap">$${limite}</td>
                <td>
                    ${c.active
                        ? '<span class="badge bg-success">Activo</span>'
                        : '<span class="badge bg-secondary">Inactivo</span>'
                    }
                </td>
                <td class="text-end text-nowrap">${acciones}</td>
            </tr>`;
        });

    });

});

</script>

@endsection
