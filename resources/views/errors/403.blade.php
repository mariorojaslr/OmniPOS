@extends('errors.multipos_error')

@section('title', 'Acceso Restringido')

@section('icon')
    <i class="bi bi-lock-fill"></i>
@endsection

@section('head', 'Área Restringida')

@section('message')
    @if(!empty($exception) && !empty($exception->getMessage()))
        <div class="alert alert-warning py-2 px-3 rounded-3 mb-3 text-start small fw-bold" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); color: #f59e0b;">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $exception->getMessage() }}
        </div>
    @endif
    No tenés los permisos necesarios para acceder a esta funcionalidad. 
    Si creés que esto es un error, por favor contactá al administrador de tu empresa.
@endsection

@section('status', 'Acceso Denegado por Seguridad')
