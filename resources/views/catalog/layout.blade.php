<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>@yield('title')</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background:#f3f4f6;
}

.catalog-header {
    background:linear-gradient(135deg,#3563E9,#2F55D4);
    padding:18px 30px;
    border-radius:14px;
    color:white;
    box-shadow:0 10px 30px rgba(0,0,0,0.15);
}

.main-content {
    min-height:75vh;
}

.product-card {
    background:white;
    border-radius:14px;
    padding:15px;
    transition:.25s ease;
    box-shadow:0 8px 20px rgba(0,0,0,0.05);
}

.product-card:hover {
    transform:translateY(-4px);
}
</style>

</head>

<body>

<div class="container py-5">

    <div class="catalog-header d-flex justify-content-between align-items-center mb-5">
        <div class="fw-bold fs-5">
            {{ $empresa->nombre_comercial }}
        </div>

        <div class="d-flex gap-3 align-items-center">

            <a href="#" class="btn btn-light btn-sm">👤</a>

            <a href="#" class="btn btn-light btn-sm">❤️</a>

            <a href="{{ route('cart.index') }}" class="btn btn-light btn-sm position-relative">
                🛒
                @if(session('cart'))
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>

        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
