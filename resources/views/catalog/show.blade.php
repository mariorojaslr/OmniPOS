<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} - {{ $empresa->nombre_comercial }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-light bg-white shadow-sm mb-4">
    <div class="container">
        <span class="navbar-brand fw-bold">
            {{ $empresa->nombre_comercial }}
        </span>
    </div>
</nav>

<div class="container">

    <div class="mb-4">
        <h1 class="fw-bold">{{ $product->name }}</h1>
        <p class="text-muted">Detalles del producto</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($product->images as $index => $image)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image->path) }}" class="d-block w-100" alt="{{ $product->name }}">
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
        </div>

        <div class="col-md-6">
            <h5 class="fw-bold">Precio: ${{ number_format($product->price, 2) }}</h5>

            <p class="fw-bold mt-3">Descripción corta:</p>
            <p>{{ $product->short_description }}</p>

            <p class="fw-bold mt-3">Descripción larga:</p>
            <p>{{ $product->long_description }}</p>

            <p class="fw-bold mt-3">Medidas:</p>
            <p>{{ $product->dimensions }}</p>

            <p class="fw-bold mt-3">Precio sin IVA: ${{ number_format($product->price / 1.21, 2) }}</p>
            <p class="fw-bold mt-3">IVA: ${{ number_format($product->price - ($product->price / 1.21), 2) }}</p>

            <a href="#" class="btn btn-primary btn-lg w-100 mt-4">Agregar al carrito</a>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
