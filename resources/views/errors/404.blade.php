@extends('errors.multipos_error')

@section('title', 'Página no encontrada')

@section('icon')
    <i class="bi bi-search"></i>
@endsection

@section('head', 'Sector no Encontrado')

@section('message')
    Parece que la ubicación que estás buscando no existe o fue movida a un nuevo sector del panel. 
    Verificá la dirección o volvé al inicio para continuar navegando.
@endsection
