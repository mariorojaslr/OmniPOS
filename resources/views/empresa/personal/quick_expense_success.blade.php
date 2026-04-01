@extends('layouts.app')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
<style>
    body { 
        background-color: #000 !important; 
        color: #fff; 
        font-family: 'Outfit', sans-serif; 
        margin: 0; 
        padding: 0;
        overflow: hidden; 
    }
    .success-container { 
        height: 100vh; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        padding: 30px; 
        text-align: center; 
        background: radial-gradient(circle at center, rgba(16, 185, 129, 0.05), transparent 70%);
    }
    .check-neon { 
        width: 140px; 
        height: 140px; 
        border-radius: 50%; 
        background: rgba(16, 185, 129, 0.1);
        border: 2px solid #10b981; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 5rem; 
        color: #10b981; 
        box-shadow: 0 0 50px rgba(16, 185, 129, 0.3); 
        margin-bottom: 2.5rem; 
        animation: successPop 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) both; 
    }
    @keyframes successPop { 
        0% { transform: scale(0.5); opacity: 0; } 
        100% { transform: scale(1); opacity: 1; } 
    }
    .success-title {
        font-weight: 800;
        font-size: 2.2rem;
        letter-spacing: -1px;
        margin-bottom: 15px;
        background: linear-gradient(to bottom, #ffffff, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .success-text {
        color: #94a3b8;
        font-size: 1.1rem;
        line-height: 1.6;
        max-width: 320px;
        margin-bottom: 40px;
    }
    .btn-action { 
        background: #fff; 
        border: none; 
        border-radius: 20px; 
        padding: 18px 40px; 
        color: #000; 
        font-weight: 800; 
        text-decoration: none; 
        transition: all 0.3s; 
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 10px 30px rgba(255,255,255,0.1);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.9rem;
    }
    .btn-action:active { transform: scale(0.95); }
    
    .btn-secondary-custom { 
        background: transparent; 
        border: 1px solid rgba(255, 255, 255, 0.1); 
        color: #64748b; 
        margin-top: 15px;
        padding: 12px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<div class="success-container">
    <div class="check-neon">
        <i class="bi bi-check-lg"></i>
    </div>
    
    <h1 class="success-title">PAGO REGISTRADO</h1>
    <p class="success-text">El gasto ha sido cargado con éxito a tu planilla de caja actual.</p>
    
    <a href="{{ route('empresa.gastos.quick') }}" class="btn-action">
        REGISTRAR OTRO <i class="bi bi-plus-lg"></i>
    </a>

    <a href="{{ route('empresa.dashboard') }}" class="btn-secondary-custom">
        Volver al Panel
    </a>

    <div class="mt-5 small text-white-50 letter-spacing-2" style="font-size: 0.6rem; opacity: 0.3;">
        MULTIPOS FIELD OPERATIVE SYSTEM
    </div>
</div>

<script>
    if (window.navigator.vibrate) {
        window.navigator.vibrate([100, 30, 100]);
    }
</script>
@endsection

