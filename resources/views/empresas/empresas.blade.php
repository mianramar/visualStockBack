{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
    <h1 class="font-semibold text-xl">{{ __('idioma.tituloEmpresas') }}</h1><br>
    <div style="display: flex;   justify-content: flex-end; ">
        {{--Enlace para ir a crear nueva empresa --}}
    <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/novaempresa">{{ __('idioma.nuevaEmpresa') }}</a>
    </div>
    @if (session('success'))
    <div class="bg-green-500 p-4">
        {{ session('success') }}
    </div>
    @endif
    <table class="table table-striped">
        {{-- Cabecera de la tabla --}}    
        <thead>
        <tr class="bg-gray-300">
            <th class="px-4 py-2">{{ __('idioma.nombreEmpresas') }}</th>    
            <th class="px-4 py-2">{{ __('idioma.emailEmpresas') }}</th>    
            <th class="px-4 py-2">{{ __('idioma.telefonoEmpresas') }}</th> 
            <th class="px-4 py-2">{{ __('idioma.direccionEmpresas') }}</th> 
            <th class="px-4 py-2">{{ __('idioma.acciones') }}</th>
        </tr>
        </thead>
        {{-- Mostramos las empresas --}}
        <tbody>
            @foreach($empresas as $empresa)
                <tr class="border-b border-gray-300">
                    <td class="px-4 py-2">{{ $empresa->nombre }}</td>
                    <td class="px-4 py-2">{{ $empresa->email }}</td>
                    <td class="px-4 py-2">{{ $empresa->telefono }}</td>
                    <td class="px-4 py-2">{{ $empresa->direccion }}</td>
                    
                    {{-- Opciones de modificar/eliminar --}}
                    <td class="px-4 py-2">
                    <a href="/modempresa/{{ $empresa->id }}">{{ __('idioma.editar') }}</a> | 
                    <a href="/eliminarempresa/{{ $empresa->id }}">{{ __('idioma.eliminar') }}</a>
                    </td>
                </tr>  
            @endforeach
        </tbody>  

    <table>
        
@endsection
