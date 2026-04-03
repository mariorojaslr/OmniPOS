@extends('layouts.empresa')

@section('content')
<style>
    :root {
        --oled-bg: #000;
        --card-bg: #0c0c0e;
        --accent-sky: #38bdf8;
        --accent-amber: #f59e0b;
        --accent-emerald: #10b981;
        --stellar-blue: #1e3a8a;
    }

    body { background-color: var(--oled-bg) !important; color: #fff; }

    .vault-container {
        padding: 4rem 8%;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* TARJETA DE ESTADO DE BÓVEDA */
    .vault-status-card {
        background: var(--card-bg);
        border: 2px solid #fff;
        border-radius: 40px;
        padding: 4rem;
        display: flex;
        align-items: center;
        gap: 4rem;
        box-shadow: 0 0 100px rgba(56, 189, 248, 0.1);
        margin-bottom: 4rem;
    }

    .vault-icon-box {
        width: 150px;
        height: 150px;
        background: rgba(56, 189, 248, 0.05);
        border: 2px solid var(--accent-sky);
        border-radius: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        color: var(--accent-sky);
        box-shadow: 0 0 30px rgba(56, 189, 248, 0.2);
    }

    .download-btn {
        background: var(--accent-sky);
        color: #000;
        border: 0;
        padding: 1.5rem 3rem;
        border-radius: 20px;
        font-weight: 950;
        text-transform: uppercase;
        font-size: 1rem;
        letter-spacing: 2px;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 1.5rem;
    }
    .download-btn:hover { transform: translateY(-5px); box-shadow: 0 0 40px var(--accent-sky); }

    /* GUÍA PASO A PASO EXIGIDA */
    .guide-box {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 3rem;
    }
    .step-card {
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 30px;
        padding: 3rem;
        position: relative;
    }
    .step-num {
        position: absolute;
        top: -15px;
        left: 30px;
        background: var(--accent-sky);
        color: #000;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 950;
    }
    .step-title { font-size: 1rem; font-weight: 950; text-transform: uppercase; margin-bottom: 1.5rem; color: #fff; }
    .step-desc { font-size: 0.85rem; color: #71717a; line-height: 1.8; }

    /* CHEQUEO DE ESPACIO */
    .storage-check {
        margin-top: 5rem;
        border-top: 1px dashed rgba(255,255,255,0.1);
        padding-top: 3rem;
    }
    .storage-bar {
        height: 12px;
        background: rgba(255,255,255,0.05);
        border-radius: 10px;
        overflow: hidden;
        margin: 1.5rem 0;
    }
    .storage-fill { background: var(--accent-sky); height: 100%; border-radius: 10px; box-shadow: 0 0 10px var(--accent-sky); }

</style>

<div class="vault-container">
    <div class="flex justify-between items-center mb-12">
        <div>
            <h1 class="text-3xl font-black uppercase tracking-[0.4em] mb-2 text-white">Bóveda de Resguardo</h1>
            <p class="text-zinc-500 font-bold uppercase text-xs tracking-widest">Su información, siempre en sus manos.</p>
        </div>
        <div class="flex items-center gap-4 text-emerald-500 text-xs font-black uppercase tracking-widest">
            <span class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span> CONEXIÓN SEGURA ACTIVA
        </div>
    </div>

    <!-- STATUS CARD -->
    <div class="vault-status-card">
        <div class="vault-icon-box">
            <i class="bi bi-safe2"></i>
        </div>
        <div class="flex-1">
            <h2 class="text-2xl font-black uppercase tracking-widest mb-4">Respaldar mi Empresa</h2>
            <p class="text-zinc-400 mb-8 max-w-2xl leading-relaxed">
                Este proceso unifica su base de datos comercial y sus archivos cargados en Bunny.net en un solo archivo comprimido. Recomendamos realizar este proceso al menos una vez por semana.
            </p>
            <div class="flex gap-6">
                <a href="#" class="download-btn">
                     Generar Resguardo <i class="bi bi-cloud-download-fill fs-4"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- GUÍA PASO A PASO (LA QUE PIDIÓ MARIO) -->
    <h3 class="text-xl font-black uppercase tracking-widest mb-10 text-center">Guía de Seguridad Local</h3>
    <div class="guide-box">
        <div class="step-card">
            <div class="step-num">01</div>
            <div class="step-title">Crear Carpeta Local</div>
            <div class="step-desc">
                Cree una carpeta llamada <span class="text-sky-400 font-bold">MULTIPOS_BACKUP</span> en un disco duro externo o servicio de nube local. Evite carpetas temporales.
            </div>
        </div>
        <div class="step-card">
            <div class="step-num step-bg-amber">02</div>
            <div class="step-title">Descargar & Mover</div>
            <div class="step-desc">
                Una vez generado el archivo .ZIP, muévalo inmediatamente a la carpeta creada. No lo modifique ni intente abrirlo si no es necesario restaurar.
            </div>
        </div>
        <div class="step-card">
            <div class="step-num step-bg-emerald">03</div>
            <div class="step-title">Chequeo Semanal</div>
            <div class="step-desc">
                Su empresa genera aproximadamente <span class="text-emerald-400 font-bold">45MB</span> semanales de nueva data. Asegúrese de tener espacio suficiente en su máquina local.
            </div>
        </div>
    </div>

    <!-- CHEQUEO DE DISCO SIMULADO -->
    <div class="storage-check">
        <div class="flex justify-between text-[0.7rem] font-black uppercase tracking-widest mb-2">
            <span class="text-zinc-500">Estimación de Peso del Backup</span>
            <span class="text-sky-400">1.2 GB (Base de Datos + Bunny Media)</span>
        </div>
        <div class="storage-bar">
            <div class="storage-fill" style="width: 15%"></div>
        </div>
        <p class="text-[0.6rem] text-zinc-600 font-black uppercase text-center mt-4">
            <i class="bi bi-info-circle me-2"></i> El tiempo de descarga dependerá de su conexión a internet y el volumen de archivos en Bunny.net
        </p>
    </div>

</div>
@endsection
