@extends('layouts.empresa')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Gestión de Inventario Colaborativo 📦✨</h2>
                <a href="{{ route('empresa.stock.index') }}" class="btn btn-outline-secondary">Volver al Stock</a>
            </div>

            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-body p-5">
                    
                    @if(!$session)
                        {{-- SESIÓN NO INICIADA --}}
                        <div class="text-center py-5">
                            <div class="display-1 mb-4">📢</div>
                            <h3>¿Listo para el conteo de stock?</h3>
                            <p class="text-muted mb-5">Habilita una sesión para que otros colaboradores puedan escanear con su celular.</p>
                            
                            <form action="{{ route('empresa.inventory_start') }}" method="POST">
                                @csrf
                                <button class="btn btn-primary btn-lg px-5 py-3 shadow fw-bold" style="border-radius: 12px">
                                    <i class="bi bi-play-fill me-2"></i> INICIAR SESIÓN DE ESCANEO
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- SESIÓN ACTIVA --}}
                        <div class="row align-items-center mt-3">
                            <div class="col-md-6 text-center">
                                <div class="p-4 bg-white shadow-sm border rounded-4 d-inline-block mb-3" id="qrContainer">
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate(route('inventory.guest-access', $session->uuid)) !!}
                                </div>
                                <div class="mt-2 fw-bold text-success animate__animated animate__pulse animate__infinite">
                                    ● Sesión de Inventario Activa
                                </div>
                                <div class="text-muted small mt-2">
                                    Token: <span class="badge bg-light text-dark font-monospace">{{ $session->uuid }}</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h4 class="fw-bold mb-3">Escanea este código 📱</h4>
                                <p>Cualquier colaborador con este código podrá sumar stock instantáneamente usando su cámara.</p>
                                
                                <div class="d-grid gap-3 mt-4">
                                    <button onclick="window.print()" class="btn btn-light border-secondary btn-lg py-3 fw-bold">
                                        <i class="bi bi-printer me-2"></i> IMPRIMIR CÓDIGO QR
                                    </button>

                                    <a href="{{ route('inventory.guest-access', $session->uuid) }}" target="_blank" class="btn btn-outline-primary py-3 fw-bold">
                                        <i class="bi bi-eye me-2"></i> VER COMO COLABORADOR
                                    </a>

                                    <form action="{{ route('empresa.inventory_stop') }}" method="POST" class="mt-4">
                                        @csrf
                                        <button class="btn btn-outline-danger w-100 py-2 fw-bold">
                                            <i class="bi bi-stop-circle me-1"></i> FINALIZAR SESIÓN Y CERRAR ACCESOS
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Info Extra --}}
            <div class="mt-5 text-center px-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="p-3">
                            <i class="bi bi-shield-check text-primary fs-2"></i>
                            <h6 class="mt-2 fw-bold">Acceso Seguro</h6>
                            <p class="small text-muted">Aislamiento total. Solo acceden a la utilidad de escaneo.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3">
                            <i class="bi bi-clock-history text-primary fs-2"></i>
                            <h6 class="mt-2 fw-bold">Historial en Vivo</h6>
                            <p class="small text-muted">Cada ajuste queda registrado en el Kardex al instante.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3">
                            <i class="bi bi-layers text-primary fs-2"></i>
                            <h6 class="mt-2 fw-bold">Multicolaborador</h6>
                            <p class="small text-muted">Varios teléfonos pueden escanear al mismo tiempo.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    @media print {
        .navbar, .btn, .text-muted, h2, .mt-5, form { display: none !important; }
        .container { width: 100%; max-width: 100%; margin: 0; padding: 0; }
        .card { border: 0 !important; box-shadow: none !important; }
        .card-body { padding: 0 !important; }
        #qrContainer { border: 0 !important; box-shadow: none !important; margin-top: 50px !important; }
        .col-md-6 { width: 100% !important; text-align: center !important; }
        h4 { display: block !important; margin-top: 20px; font-size: 24px; }
        body { background: white !important; }
    }
</style>
@endsection
