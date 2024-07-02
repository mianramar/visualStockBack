{{-- 


  Título: Entrega Final

  Autor: Miguel Ángel Rama Martínez.

  Data modificación: 14/03/2024

  Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
    {{-- Titulo y nuevo albaran --}}
    @if ($tipo == 'entrada') {{-- Si el tipo es de entrada --}}
        <h1 class="font-semibold text-xl">{{ __('idioma.entradas') }}</h1><br>
        <div style="display: flex;   justify-content: flex-end; ">
            <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/novoalbaran">{{ __('idioma.novoAlbaran') }}</a>
        </div>
    @else {{-- Si es tipo salida --}}
        <h1 class="font-semibold text-xl">{{ __('idioma.salidas') }}</h1><br>
        <div style="display: flex;   justify-content: flex-end; ">
            <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/novoalbaransalida">{{ __('idioma.novoAlbaran') }}</a>
        </div>
    @endif

    {{-- Mensajes de alerta --}}
    @if ($mensajeSuccess == true)
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ __('idioma.mensajeSuccessCrearAlbaran1') }}</strong> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if ($mensajeEliminarSuccess == true)
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ __('idioma.mensajeSuccessEliminarAlbaran1') }}</strong> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif


    @if ($mensajeEditarSuccess == true)
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ __('idioma.mensajeSuccessEditarAlbaran1') }}</strong> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if ($mensajeEliminarError == true)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ __('idioma.mensajeErrorEliminarAlbaran1') }}</strong> {{ __('idioma.mensajeErrorEliminarAlbaran2') }}
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
            <th class="px-4 py-2">{{ __('idioma.numeroAlbaran') }}</th>    
            <th class="px-4 py-2">{{ __('idioma.tipo') }}</th>   
            <th class="px-4 py-2">{{ __('idioma.fechaAlbaran') }}</th>   
            <th class="px-4 py-2">{{ __('idioma.empresaAlbaran') }}</th>   
            <th class="px-4 py-2">{{ __('idioma.acciones') }}</th>   
        </tr>
        </thead>
        {{-- Mostramos os albarans --}}
        <tbody>
            @foreach($albarans as $albaran)
                <tr class="border-b border-gray-300">
                    <td class="px-4 py-2">{{ $albaran->numero }}</td>
                    <td class="px-4 py-2">{{ $albaran->tipo }}</td>
                    <td class="px-4 py-2">{{ $albaran->fecha }}</td>
                    <td class="px-4 py-2">{{ $albaran->empresa_nome }}</td>
                    <td class="px-4 py-2">
                        <a  href="/listaxealbarans/{{ $albaran->id }}">{{ __('idioma.verDetalle') }}</a>
                        @if (auth()->user()->rol == 'administrador') {{--Editar y eliminar solo el admin --}}
                            @if ($tipo == 'entrada') {{--Diferenciamos entre entrada y salida --}}
                            | <a  href="/modalbaran/{{ $albaran->id }}">{{ __('idioma.editar') }}</a>
                            | <a  href="/eliminaralbaran/{{ $albaran->id }}">{{ __('idioma.eliminar') }}</a> 
                            @else 
                            | <a  href="/modalbaransalida/{{ $albaran->id }}">{{ __('idioma.editar') }}</a>
                            | <a  href="/eliminaralbaransalida/{{ $albaran->id }}">{{ __('idioma.eliminar') }}</a> 
                            @endif

                        @endif       
                    </td>

                </tr>  
            @endforeach
        </tbody>  
    <table>

@endsection