@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Editar Novedad</div>
                <div class="card-body">
                    <form action="{{ route('owner.updates.update', $update) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Título de la mejora</label>
                            <input type="text" name="title" class="form-control" value="{{ $update->title }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha</label>
                                <input type="date" name="publish_date" class="form-control" value="{{ $update->publish_date->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo</label>
                                <select name="type" class="form-select">
                                    <option value="nuevo" {{ $update->type == 'nuevo' ? 'selected' : '' }}>Nuevo (Funcionalidad)</option>
                                    <option value="mejora" {{ $update->type == 'mejora' ? 'selected' : '' }}>Mejora (Optimización)</option>
                                    <option value="arreglo" {{ $update->type == 'arreglo' ? 'selected' : '' }}>Arreglo (Bug fix)</option>
                                    <option value="tarea" {{ $update->type == 'tarea' ? 'selected' : '' }}>Tarea (Acción requerida)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción detallada</label>
                            <textarea name="description" class="form-control" rows="5">{{ $update->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link Tutorial / URL (Opcional)</label>
                            <input type="url" name="link_tutorial" class="form-control" value="{{ $update->link_tutorial }}">
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('owner.updates.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
