@extends('layouts.empresa')

@section('content')

<div class="container-fluid">

    {{-- ======================= --}}
    {{-- TITULO + BOTONES --}}
    {{-- ======================= --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="mb-0">
            📅 Ventas por Fecha
        </h3>

        <div>

            {{-- PDF --}}
            <a href="{{ route('empresa.reportes.export.pdf') }}"
               class="btn btn-danger btn-sm">
                PDF
            </a>

            {{-- EXCEL --}}
            <a href="{{ route('empresa.reportes.export.excel') }}"
               class="btn btn-success btn-sm">
                Excel
            </a>

            {{-- VOLVER --}}
            <a href="{{ route('empresa.reportes.panel') }}"
               class="btn btn-secondary btn-sm">
                Volver
            </a>

        </div>
    </div>


    {{-- ======================= --}}
    {{-- TABLA --}}
    {{-- ======================= --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th class="text-end">Ventas</th>
                        <th class="text-end">Total $</th>
                    </tr>
                </thead>

                <tbody>

                    @php
                        $totalVentas = 0;
                        $totalDinero = 0;
                    @endphp

                    @foreach($ventas as $v)

                        @php
                            $totalVentas += $v->cantidad;
                            $totalDinero += $v->total;
                            $fechaUnica = \Carbon\Carbon::parse($v->fecha)->format('Y-m-d');
                        @endphp

                        {{-- Fila Principal (Clickeable) --}}
                        <tr class="venta-dia-row" 
                            data-fecha="{{ $fechaUnica }}"
                            style="cursor: pointer; transition: background 0.2s;"
                            onmouseover="this.style.backgroundColor='#f8f9fa'"
                            onmouseout="this.style.backgroundColor='transparent'">
                            
                            <td class="fw-bold text-primary">
                                <i class="bi bi-chevron-right me-2 chevron-icon" style="font-size: 0.8rem; transition: transform 0.3s;"></i>
                                {{ \Carbon\Carbon::parse($v->fecha)->format('d/m/Y') }}
                            </td>

                            <td class="text-end">
                                {{ number_format($v->cantidad, 0, ',', '.') }}
                            </td>

                            <td class="text-end fw-bold">
                                $ {{ number_format($v->total, 2, ',', '.') }}
                            </td>
                        </tr>

                        {{-- Fila Detalle (Accordion) --}}
                        <tr id="detalle-{{ $fechaUnica }}" class="detalle-row d-none bg-light">
                            <td colspan="3" class="p-0 border-0">
                                <div class="accordion-content shadow-inner overflow-hidden" style="max-height: 0; transition: max-height 0.4s ease-out;">
                                    {{-- El contenido se carga vía AJAX --}}
                                    <div class="text-center p-4 loading-spinner">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                        <span class="ms-2 small text-muted">Cargando detalles...</span>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    @endforeach

                </tbody>

                {{-- ======================= --}}
                {{-- TOTALES --}}
                {{-- ======================= --}}
                <tfoot class="table-light">
                    <tr>
                        <th class="text-end">Totales</th>

                        <th class="text-end">
                            {{ number_format($totalVentas, 0, ',', '.') }}
                        </th>

                        <th class="text-end text-success fs-5">
                            $ {{ number_format($totalDinero, 2, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>

            </table>

        </div>
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.venta-dia-row');

        rows.forEach(row => {
            row.addEventListener('click', function() {
                const fecha = this.getAttribute('data-fecha');
                const detalleRow = document.getElementById(`detalle-${fecha}`);
                const content = detalleRow.querySelector('.accordion-content');
                const chevron = this.querySelector('.chevron-icon');

                // Si ya está abierto, lo cerramos (REPLIEGUE)
                if (!detalleRow.classList.contains('d-none')) {
                    content.style.maxHeight = '0';
                    chevron.style.transform = 'rotate(0deg)';
                    setTimeout(() => {
                        detalleRow.classList.add('d-none');
                    }, 400);
                    return;
                }

                // Cerramos otros abiertos primero (Opcional, pero recomendado para prolijidad)
                // document.querySelectorAll('.detalle-row:not(.d-none)').forEach(openRow => { ... });

                // Abrimos el actual
                detalleRow.classList.remove('d-none');
                chevron.style.transform = 'rotate(90deg)';

                // Si no tiene contenido (excepto el spinner), lo cargamos
                const inner = content.querySelector('.ventas-inner');
                if (!inner) {
                    fetch(`{{ route('empresa.reportes.ventas_detalle') }}?fecha=${fecha}`)
                        .then(response => response.text())
                        .then(html => {
                            content.innerHTML = `<div class="ventas-inner">${html}</div>`;
                            content.style.maxHeight = content.scrollHeight + 'px';
                        })
                        .catch(err => {
                            console.error(err);
                            content.innerHTML = '<div class="p-3 text-danger text-center small">Error al cargar detalles. Reintente.</div>';
                        });
                } else {
                    content.style.maxHeight = content.scrollHeight + 'px';
                }
            });
        });
    });
</script>
@endpush

<style>
    .venta-dia-row:hover {
        background-color: rgba(99, 102, 241, 0.05) !important;
    }
    .shadow-inner {
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
    }
</style>

@endsection
