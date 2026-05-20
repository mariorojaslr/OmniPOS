@extends('layouts.empresa')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
        <div>
            <h4 class="mb-1 text-primary fw-bold">⚙️ Configuración de Reserva y Pago Online</h4>
            <p class="text-muted small mb-0">Gestione la visibilidad de la agenda pública para sus pacientes, tarifas de co-seguro online y la política de tolerancia de 15 minutos.</p>
        </div>
        <div>
            <a href="{{ route('empresa.turnos.index') }}" class="btn btn-outline-primary fw-bold shadow-sm">
                <i class="bi bi-calendar-event me-2"></i> Volver a Agenda
            </a>
        </div>
    </div>

    {{-- ALERTA --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card shadow border-0 bg-white rounded-4 overflow-hidden mb-5">
                <div class="card-body p-4">
                    <form action="{{ route('empresa.turnos.online-config.save') }}" method="POST">
                        @csrf

                        <!-- Activar Portal Autogestionado -->
                        <div class="mb-4 p-3 bg-light rounded-4 border border-secondary border-opacity-10">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-globe text-primary me-2"></i> Habilitar Portal de Autogestión Pública</h6>
                                    <p class="x-small text-muted mb-0">Permite que los pacientes visualicen su cartilla de profesionales y reserven turnos de forma autónoma.</p>
                                </div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input" type="checkbox" name="online_booking_enabled" value="1" {{ !empty($config['online_booking_enabled']) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        <!-- Configuración de Cobro y Pasarela -->
                        <h5 class="fw-bold text-dark mb-3 text-uppercase tracking-wider fs-6 border-bottom pb-2"><i class="bi bi-wallet2 text-success me-2"></i> Tarifas de Co-Seguro y Pasarelas de Pago</h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6 col-sm-12">
                                <label class="small text-muted fw-bold mb-1">Costo de Reserva Online ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">$</span>
                                    <input type="number" name="online_booking_price" value="{{ $config['online_booking_price'] ?? 0 }}" class="form-control border-start-0 shadow-none" placeholder="0.00" min="0" step="any">
                                </div>
                                <p class="x-small text-muted mt-1">Defina el arancel a cobrar al paciente para confirmar su reserva online (dejar en 0 si es gratuito).</p>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label class="small text-muted fw-bold mb-1">Pasarela de Cobros Integrada</label>
                                <select name="online_booking_gateway" class="form-select border-slate shadow-none">
                                    <option value="MercadoPago" {{ (!empty($config['online_booking_gateway']) && $config['online_booking_gateway'] === 'MercadoPago') ? 'selected' : '' }}>MercadoPago Argentina (Producción / Sandbox)</option>
                                    <option value="Stripe" {{ (!empty($config['online_booking_gateway']) && $config['online_booking_gateway'] === 'Stripe') ? 'selected' : '' }}>Stripe Internacional (USD / Multimoneda)</option>
                                </select>
                                <p class="x-small text-muted mt-1">El importe será acreditado instantáneamente en su cuenta vinculada.</p>
                            </div>
                        </div>

                        <!-- Política de Tolerancia de 15 Minutos -->
                        <h5 class="fw-bold text-dark mb-3 text-uppercase tracking-wider fs-6 border-bottom pb-2"><i class="bi bi-clock-history text-danger me-2"></i> Políticas de Presentación de Pacientes</h5>
                        
                        <div class="mb-4 p-3 bg-light rounded-4 border border-secondary border-opacity-10">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-hourglass-split text-danger me-2"></i> Política Estricta de 15 Minutos de Tolerancia</h6>
                                    <p class="x-small text-muted mb-0">Si el paciente excede los 15 minutos de retraso sobre su turno reservado, el sistema automáticamente reordenará su orden en la cola del día de guardia o cancelará la prioridad para evitar retrasar la agenda médica de los profesionales.</p>
                                </div>
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input" type="checkbox" name="online_booking_tolerance" value="1" {{ !empty($config['online_booking_tolerance']) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        <!-- Configuración del Smart TV / Turnero de Sala -->
                        <h5 class="fw-bold text-dark mb-3 text-uppercase tracking-wider fs-6 border-bottom pb-2 mt-4"><i class="bi bi-tv text-primary me-2"></i> Configuración de Smart TV / Turnero de Sala</h5>
                        
                        <p class="x-small text-muted mt-1 mb-3">Copie y pegue el ID de su playlist de YouTube (es la serie de caracteres después de <code>?list=</code>). Puede cargar hasta 5 listas distintas. El sistema las seleccionará y reproducirá al azar de forma continua para evitar que los pacientes se aburran.</p>

                        <div class="row g-3 mb-4">
                            @for($i = 1; $i <= 5; $i++)
                            <div class="col-md-4 col-sm-6">
                                <label class="small text-muted fw-bold mb-1">Playlist de YouTube {{ $i }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-youtube text-danger"></i></span>
                                    <input type="text" name="youtube_playlist_id_{{ $i }}" value="{{ $config['youtube_playlist_id_'.$i] ?? ($i == 1 ? ($config['youtube_playlist_id'] ?? 'PLUw7Nsql2TwQrUwKitNw4OIoxYAzEWczn') : '') }}" class="form-control border-start-0 shadow-none" placeholder="PL...">
                                </div>
                            </div>
                            @endfor
                        </div>

                        <!-- Mensajes Dinámicos del Turnero (Smart TV) -->
                        <h5 class="fw-bold text-dark mb-3 text-uppercase tracking-wider fs-6 border-bottom pb-2 mt-4"><i class="bi bi-chat-left-quote text-info me-2"></i> Mensajes Dinámicos en Sala de Espera (Smart TV)</h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-4 col-sm-12">
                                <div class="p-3 border rounded-4 bg-light bg-opacity-75">
                                    <label class="x-small text-muted fw-bold mb-1">Mensaje 1 (Título)</label>
                                    <input type="text" name="ticker_title_1" value="{{ $config['ticker_title_1'] ?? '📇 Tenga su DNI Listo' }}" class="form-control shadow-none mb-2 form-control-sm" placeholder="Título">
                                    <label class="x-small text-muted fw-bold mb-1">Mensaje 1 (Descripción)</label>
                                    <textarea name="ticker_desc_1" class="form-control shadow-none form-control-sm" rows="3" placeholder="Descripción">{{ $config['ticker_desc_1'] ?? 'Entregue su documento nacional de identidad en recepción para registrar su ingreso.' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="p-3 border rounded-4 bg-light bg-opacity-75">
                                    <label class="x-small text-muted fw-bold mb-1">Mensaje 2 (Título)</label>
                                    <input type="text" name="ticker_title_2" value="{{ $config['ticker_title_2'] ?? '📁 Digitalice sus Estudios' }}" class="form-control shadow-none mb-2 form-control-sm" placeholder="Título">
                                    <label class="x-small text-muted fw-bold mb-1">Mensaje 2 (Descripción)</label>
                                    <textarea name="ticker_desc_2" class="form-control shadow-none form-control-sm" rows="3" placeholder="Descripción">{{ $config['ticker_desc_2'] ?? 'Presente radiografías, PDF o análisis previos a la secretaria para cargarlos de inmediato.' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="p-3 border rounded-4 bg-light bg-opacity-75">
                                    <label class="x-small text-muted fw-bold mb-1">Mensaje 3 (Título)</label>
                                    <input type="text" name="ticker_title_3" value="{{ $config['ticker_title_3'] ?? '🧼 Cuidado e Higiene' }}" class="form-control shadow-none mb-2 form-control-sm" placeholder="Título">
                                    <label class="x-small text-muted fw-bold mb-1">Mensaje 3 (Descripción)</label>
                                    <textarea name="ticker_desc_3" class="form-control shadow-none form-control-sm" rows="3" placeholder="Descripción">{{ $config['ticker_desc_3'] ?? 'Utilice el dispenser de alcohol en gel disponible en los pasillos al entrar y salir.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary fw-bold py-3 rounded-4 shadow-sm fs-5">
                                <i class="bi bi-save me-2"></i> GUARDAR CONFIGURACIONES DE PORTAL
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <!-- Tarjeta Informativa Widget -->
            <div class="card bg-gradient-dark text-white rounded-4 border-0 shadow-lg p-4 mb-4" style="background: linear-gradient(135deg, #1e293b, #0f172a);">
                <h5 class="fw-bold mb-3 text-white"><i class="bi bi-link-45deg me-2 text-primary"></i> Su Enlace de Widget Público</h5>
                <p class="small text-white-50">Copie y pegue este enlace en sus redes sociales, sitio web institucional o envíelo por WhatsApp para que los pacientes reserven online:</p>
                
                <div class="input-group mb-3">
                    <input type="text" id="widgetLink" class="form-control bg-dark border-0 text-white x-small" readonly value="{{ route('catalog.booking', $empresa->slug) }}">
                    <button class="btn btn-primary" onclick="copyLink()"><i class="bi bi-copy"></i></button>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('catalog.booking', $empresa->slug) }}" target="_blank" class="btn btn-sm btn-outline-success fw-bold w-100 rounded-pill py-2">
                        <i class="bi bi-eye-fill"></i> PROBAR VISTA PACIENTE
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyLink() {
        const linkInput = document.getElementById('widgetLink');
        linkInput.select();
        linkInput.setSelectionRange(0, 99999); // Para móviles
        navigator.clipboard.writeText(linkInput.value);

        Swal.fire({
            title: '¡Copiado!',
            text: 'El enlace del portal de autogestión de turnos ha sido copiado al portapapeles.',
            icon: 'success',
            confirmButtonColor: '#1f6feb',
            background: '#ffffff',
            color: '#000000'
        });
    }
</script>
@endsection
