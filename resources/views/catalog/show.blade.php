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
        <a href="{{ route('catalog.index', $empresa) }}" class="navbar-brand fw-bold">
            ← Volver al catálogo
        </a>
    </div>
</nav>

<div class="container">

    <div class="row">

        <div class="col-md-6">
            @if($product->images->count())
                <img
                    src="{{ asset('storage/' . $product->images->first()->path) }}"
                    class="img-fluid rounded shadow-sm mb-3"
                >
            @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                     style="height: 300px;">
                    Sin imagen
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <h1 class="fw-bold">{{ $product->name }}</h1>

            <h3 class="text-success fw-bold mb-3">
                $ {{ number_format($product->price, 2) }}
            </h3>

            <p class="text-muted">
                Próximamente vas a poder comprar este producto desde el catálogo.
            </p>

            <button class="btn btn-secondary" disabled>
                Comprar (próx.)
            </button>
        </div>

    </div>

</div>

</body>
</html>
