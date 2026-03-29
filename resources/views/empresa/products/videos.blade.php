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
    <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
        <div class="card-header bg-dark text-white py-3">
             <h6 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-1"></i> Vincular Nuevo Video</h6>
        </div>
        <div class="card-body">

            <form method="POST" action="{{ route('empresa.products.videos.store', $product) }}">
                @csrf
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Tipo de Video</label>
                        <select name="video_type" id="videoType" class="form-select rounded-pill" onchange="toggleVideoFields()">
                            <option value="youtube">YouTube</option>
                            <option value="bunny">Bunny Stream</option>
                        </select>
                    </div>

                    {{-- CAMPOS YOUTUBE --}}
                    <div class="col-md-7" id="youtubeFields">
                        <label class="form-label small fw-bold text-muted">URL de YouTube</label>
                        <input type="url" name="youtube_url" value="{{ old('youtube_url') }}" class="form-control rounded-pill" placeholder="https://www.youtube.com/watch?v=xxxx">
                    </div>

                    {{-- CAMPOS BUNNY --}}
                    <div class="col-md-4 d-none" id="bunnyVideoIdField">
                        <label class="form-label small fw-bold text-muted">Video ID (Bunny)</label>
                        <input type="text" name="bunny_video_id" class="form-control rounded-pill" placeholder="Ej: 5b6a7c-...">
                    </div>
                    <div class="col-md-3 d-none" id="bunnyLibIdField">
                        <label class="form-label small fw-bold text-muted">Library ID (Bunny)</label>
                        <input type="text" name="bunny_library_id" class="form-control rounded-pill" placeholder="Ej: 123456">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill">Vincular</button>
                    </div>
                </div>

                <small class="text-muted d-block mt-3">
                    <i class="bi bi-info-circle me-1"></i> Se recomienda usar <strong>Bunny Stream</strong> para videos promocionales pesados y evitar publicidad externa. Máximo 3 por producto.
                </small>
            </form>
        </div>
    </div>

    <script>
        function toggleVideoFields() {
            const type = document.getElementById('videoType').value;
            const youtube = document.getElementById('youtubeFields');
            const bunnyVid = document.getElementById('bunnyVideoIdField');
            const bunnyLib = document.getElementById('bunnyLibIdField');

            if(type === 'youtube') {
                youtube.classList.remove('d-none');
                bunnyVid.classList.add('d-none');
                bunnyLib.classList.add('d-none');
            } else {
                youtube.classList.add('d-none');
                bunnyVid.classList.remove('d-none');
                bunnyLib.classList.remove('d-none');
            }
        }
    </script>


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
