<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo - {{ $empresa->nombre_comercial }}</title>

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
        <h1 class="fw-bold">Catálogo</h1>
        <p class="text-muted">Explorá nuestros productos</p>
    </div>

    <div class="row g-4">

        @forelse($products as $product)
            <div class="col-md-3">
                <div class="card h-100 shadow-sm border-0">

                    @if($product->images->first())
                        <img
                            src="{{ asset('storage/' . $product->images->first()->path) }}"
                            class="card-img-top"
                            style="object-fit: cover; height: 200px;"
                        >
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                             style="height: 200px;">
                            Sin imagen
                        </div>
                    @endif

                    <div class="card-body">
                        <h5 class="fw-bold">{{ $product->name }}</h5>
                        <p class="text-success fw-bold mb-2">
                            $ {{ number_format($product->price, 2) }}
                        </p>

                        <a href="{{ route('catalog.show', [$empresa, $product]) }}"
                           class="btn btn-outline-primary btn-sm w-100">
                            Ver producto
                        </a>
                    </div>

                </div>
            </div>
        @empty
            <p class="text-muted">No hay productos disponibles.</p>
        @endforelse

    </div>

</div>

</body>
</html>
