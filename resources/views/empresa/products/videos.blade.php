@extends('layouts.empresa')

@section('content')

<div class="container py-4">

    {{-- ==========================================================
        CABECERA
    =========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Videos del producto</h2>
            <small class="text-muted">{{ $product->name }}</small>
        </div>

        {{-- 🔁 Ahora vuelve al listado general --}}
        <a href="{{ route('empresa.products.index') }}"
           class="btn btn-outline-secondary">
            Volver
        </a>
    </div>


    {{-- ==========================================================
        MENSAJES
    =========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif


    {{-- ==========================================================
        FORMULARIO AGREGAR VIDEO
    =========================================================== --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <form method="POST"
                  action="{{ route('empresa.products.videos.store', $product) }}">

                @csrf

                <div class="input-group">

                    <input type="url"
                           name="youtube_url"
                           value="{{ old('youtube_url') }}"
                           class="form-control"
                           placeholder="https://www.youtube.com/watch?v=xxxx"
                           required>

                    <button type="submit"
                            class="btn btn-primary">
                        Agregar video
                    </button>

                </div>

                <small class="text-muted d-block mt-2">
                    Máximo 3 videos por producto.
                </small>

            </form>

        </div>
    </div>


    {{-- ==========================================================
        LISTADO DE VIDEOS
    =========================================================== --}}
    @if($videos->count())

        <div class="row g-4">

            @foreach($videos as $video)

                <div class="col-md-6 col-lg-4">

                    <div class="card shadow-sm border-0 h-100">

                        {{-- 🎬 Video embebido usando accessor del modelo --}}
                        <div class="ratio ratio-16x9">
                            <iframe
                                src="{{ $video->embed_url }}"
                                title="Video del producto"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="strict-origin-when-cross-origin">
                            </iframe>
                        </div>

                        {{-- 🗑 Eliminar --}}
                        <div class="card-body text-end">

                            <form method="POST"
                                  action="{{ route('empresa.products.videos.destroy', [$product, $video]) }}"
                                  onsubmit="return confirm('¿Eliminar este video?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger">
                                    Eliminar
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="text-center py-4 text-muted">
            No hay videos cargados.
        </div>

    @endif

</div>

@endsection
