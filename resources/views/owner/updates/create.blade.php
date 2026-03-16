@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Publicar Nueva Novedad</div>
                <div class="card-body">
                    <form action="{{ route('owner.updates.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Título de la mejora</label>
                            <input type="text" name="title" class="form-control" required placeholder="Ej: Importación masiva de productos">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha</label>
                                <input type="date" name="publish_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo</label>
                                <select name="type" class="form-select">
                                    <option value="nuevo">Nuevo (Funcionalidad)</option>
                                    <option value="mejora" selected>Mejora (Optimización)</option>
                                    <option value="arreglo">Arreglo (Bug fix)</option>
                                    <option value="tarea">Tarea (Acción requerida)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción detallada</label>
                            <textarea name="description" class="form-control" rows="5" placeholder="Explique qué cambió y qué deben hacer los usuarios..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link Tutorial / URL (Opcional)</label>
                            <input type="url" name="link_tutorial" class="form-control" placeholder="https://...">
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('owner.updates.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Publicar Novedad</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
