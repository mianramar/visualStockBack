{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
    <ul class="nav nav-tabs pt-3"> {{--Barra de navegacion para cambiar entre materiales y productos--}}
        <li class="nav-item">
        <a class="nav-link " href="/listaxematerials">{{ __('idioma.tituloMaterial') }}</a>
        </li>
        <li class="nav-item ">
        <a class="nav-link active" href="/listaxeproductos">{{ __('idioma.tituloProductos') }}</a>
        </li>
    </ul>


    <div style="display: flex;   justify-content: flex-end; "> {{--Enlace para crear nuevo producto--}}
        <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/novoproducto">{{ __('idioma.nuevoProducto') }}</a>
    </div>

    {{--En caso de que haya error, lo mostramos--}}
    @if ($mensajeError == true)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ __('idioma.mensajeErrorEliminarProducto1') }}</strong> {{ __('idioma.mensajeErrorEliminarProducto2') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if ($mensajeSuccess == true)
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ __('idioma.mensajeSuccessEliminarProducto1') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif


    @if ($mensajeSuccessEdit == true)
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ __('idioma.mensajeSuccessEditProducto1') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if ($mensajeSuccessCrear == true)
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ __('idioma.mensajeSuccessCrearProducto1') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif


    @if (session('success'))
    <div class="bg-green-500 p-4">
        {{ session('success') }}
    </div>
    @endif
    <table class="table table-striped">
        {{-- Cabecera de la tabla --}}    
        <thead>
        <tr class="bg-gray-300">
            <th class="px-4 py-2">{{ __('idioma.nombreProducto') }}</th>    
            <th class="px-4 py-2">{{ __('idioma.tipoProducto') }}</th>    
            <th class="px-4 py-2">{{ __('idioma.cantidadMaterialDisponible') }}</th>
            <th class="px-4 py-2">{{ __('idioma.acciones') }}</th>

        </tr>
        </thead>
        {{-- Mostramos los productos--}}
        <tbody>
            @foreach($productos as $producto)
                <tr class="border-b border-gray-300">
                    <td class="px-4 py-2">{{ $producto->nombre }}</td>
                    <td class="px-4 py-2">{{ $producto->tipo }}</td>
                    <td class="px-4 py-2">{{ $producto->getCantidadDisponible() }}</td>

                    
                    <td class="px-4 py-2">
                      <a href="/listaxeproductos/{{ $producto->id }}">{{ __('idioma.verDetalle') }}</a> 
                    {{-- Opciones de modificar/eliminar solo para admin--}}
                    @if (auth()->user()->rol == 'administrador') | 
                    <a href="/modproducto/{{ $producto->id }}">{{ __('idioma.editar') }}</a>  | 
                    <a href="/eliminarproducto/{{ $producto->id }}">{{ __('idioma.eliminarProducto') }}</a>   
                    @endif                
                    </td>
                  
                </tr>  
            @endforeach
        </tbody>  

    <table>
        
@endsection
