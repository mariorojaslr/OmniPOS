@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Imágenes del producto</h2>
            <small class="text-muted">
                {{ $product->name }}
            </small>
        </div>

        <a href="{{ route('empresa.products.index') }}"
           class="btn btn-outline-secondary">
            Volver
        </a>
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Subida de imágenes --}}
    @if($product->images->count() < 5)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="POST"
                      action="{{ route('empresa.products.images.store', $product) }}"
                      enctype="multipart/form-data">
                    @csrf

                    <label class="form-label fw-semibold">
                        Agregar imágenes (máximo 5)
                    </label>

                    <input type="file"
                           name="images[]"
                           class="form-control mb-3"
                           accept="image/*"
                           multiple
                           required>

                    <button class="btn btn-primary">
                        Subir imágenes
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- Grid de imágenes --}}
    <div class="row g-4">
        @forelse($product->images as $image)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm border-0">

                    <img src="{{ $image->url }}"
                         class="card-img-top"
                         style="object-fit: cover; height: 200px;">

                    <div class="card-body text-center">

                        {{-- Imagen principal --}}
                        @if($image->is_main)
                            <span class="badge bg-success mb-2">
                                Imagen principal
                            </span>
                        @endif

                        {{-- Botón eliminar --}}
                        <form method="POST"
                              action="{{ route('empresa.products.images.destroy', [$product, $image]) }}"
                              onsubmit="return confirm('¿Eliminar esta imagen?');"
                              class="mt-2">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-outline-danger w-100">
                                Eliminar
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        @empty
            {{-- Estado vacío --}}
            <div class="col-12">
                <div class="text-center p-5 border rounded bg-light">
                    <i class="bi bi-image fs-1 text-muted"></i>
                    <h5 class="mt-3">No hay imágenes cargadas</h5>
                    <p class="text-muted">
                        Subí imágenes para mejorar la presentación del producto.
                    </p>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
