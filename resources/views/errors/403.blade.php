@extends('errors.multipos_error')

@section('title', 'Acceso Restringido')

@section('icon')
    <i class="bi bi-lock-fill"></i>
@endsection

@section('head', 'Área Restringida')

@section('message')
    No tenés los permisos necesarios para acceder a esta funcionalidad. 
    Si creés que esto es un error, por favor contactá al administrador de tu empresa.
@endsection

@section('status', 'Acceso Denegado por Seguridad')
