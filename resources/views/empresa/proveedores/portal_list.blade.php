@extends('layouts.empresa')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Gestión de Portales de Proveedores</h2>
            <p class="text-muted small mb-0">Copia el enlace directo para enviar a tus proveedores</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white p-4 border-0">
            <form action="{{ route('empresa.proveedores.portal_list') }}" method="GET" class="row g-2">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fas fa-search"></i></span>
                        <input type="text" name="q" class="form-control bg-light border-0" placeholder="Buscar por nombre o CUIT..." value="{{ request('q') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Buscar</button>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="x-small fw-bold text-muted text-uppercase">
                        <th class="ps-4">Proveedor</th>
                        <th>CUIT</th>
                        <th>Email</th>
                        <th class="text-end pe-4">Enlace Portal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $s)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $s->name }}</div>
                            <div class="x-small text-muted">{{ $s->phone ?: 'Sin teléfono' }}</div>
                        </td>
                        <td>{{ $s->cuit ?: '-' }}</td>
                        <td>{{ $s->email ?: '-' }}</td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" 
                                    onclick="copyPortalLink(this, {{ $s->id }}, 'proveedor')">
                                <i class="fas fa-copy me-1"></i> COPIAR ENLACE
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($suppliers->hasPages())
            <div class="card-footer bg-white p-4">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function copyPortalLink(btn, id, type) {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>';
        btn.disabled = true;

        const route = type === 'cliente' ? `/empresa/clientes/${id}/portal-link` : `/empresa/proveedores/${id}/portal-link`;

        fetch(route)
            .then(response => response.json())
            .then(data => {
                const url = data.url;
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(url).then(() => {
                        showSuccess(btn, originalHtml);
                    }).catch(err => {
                        window.open(url, '_blank');
                        resetBtn(btn, originalHtml);
                    });
                } else {
                    const textArea = document.createElement("textarea");
                    textArea.value = url;
                    document.body.appendChild(textArea);
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        showSuccess(btn, originalHtml);
                    } catch (err) {
                        window.open(url, '_blank');
                    }
                    document.body.removeChild(textArea);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = 'Error';
                setTimeout(() => resetBtn(btn, originalHtml), 2000);
            });
    }

    function showSuccess(btn, originalHtml) {
        btn.innerHTML = '<i class="fas fa-check me-1"></i> ¡COPIADO!';
        btn.classList.replace('btn-primary', 'btn-success');
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.replace('btn-success', 'btn-primary');
            btn.disabled = false;
        }, 3000);
    }

    function resetBtn(btn, originalHtml) {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
</script>
@endsection
