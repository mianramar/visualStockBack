{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}

@extends('layouts.personalizada')

@section('contido')
    <h1 class="font-semibold text-xl">{{ __('idioma.usuarios') }}</h1><br>
    <div style="display: flex;   justify-content: flex-end; ">
        {{--Enlace para nuevo usuario--}}
    <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/novousuario">{{ __('idioma.nuevoUsuario') }}</a>
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
            <th class="px-4 py-2">{{ __('idioma.nombreUsuario') }}</th>    
            <th class="px-4 py-2">{{ __('idioma.emailUsuario') }}</th>    
            <th class="px-4 py-2">{{ __('idioma.rolUsuario') }}</th>    
            <th class="px-4 py-2">{{ __('idioma.acciones') }}</th>
        </tr>
        </thead>
        {{-- Mostramos los usuarios --}}
        <tbody>
            @foreach($usuarios as $usuario)
                <tr class="border-b border-gray-300">
                    <td class="px-4 py-2">{{ $usuario->name }}</td>
                    <td class="px-4 py-2">{{ $usuario->email }}</td>
                    <td class="px-4 py-2">{{ $usuario->rol }}</td>
                    
                    {{-- Opciones de modificar/eliminar --}}
                    <td class="px-4 py-2">
                    <a href="/modusuario/{{ $usuario->id }}">{{ __('idioma.editar') }}</a> | 
                    <a href="/eliminarusuario/{{ $usuario->id }}">{{ __('idioma.eliminar') }}</a>
                    </td>
                </tr>  
        @endforeach
        </tbody>  
    <table>
        
@endsection
