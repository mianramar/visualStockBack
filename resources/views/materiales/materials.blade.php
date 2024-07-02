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
        <a class="nav-link active" href="/listaxematerials">{{ __('idioma.tituloMaterial') }}</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="/listaxeproductos">{{ __('idioma.tituloProductos') }}</a>
        </li>
    </ul>

    {{--Enlace para crear nuevo material--}}
    <div style="display: flex;   justify-content: flex-end; " class="mt-3 mb-3">
        <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/novomaterial">{{ __('idioma.nuevoMaterial') }}</a>
        <div id="lista_busqueda" style="position: absolute; z-index: 1;"></div>
        <input type="text" name="metal" id="metal" class="form-control col-md-2" autocomplete="off" placeholder="Buscar por metal...">
    </div>

    @if ($mensajeError == true)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ __('idioma.mensajeErrorEliminarMaterial1') }}</strong> {{ __('idioma.mensajeErrorEliminarMaterial2') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if ($mensajeSuccess == true)
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ __('idioma.mensajeSuccessEliminarMaterial1') }}</strong>
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
    <div id="tabla_materiales">
        <table class="table table-striped">
            {{-- Cabecera de la tabla --}}    
            <thead>
            <tr class="bg-gray-300">
                <th class="px-4 py-2">{{ __('idioma.emailMaterial') }}</th>    
                <th class="px-4 py-2">{{ __('idioma.metalMaterial') }}</th>    
                <th class="px-4 py-2">{{ __('idioma.dimensionesMaterial') }}</th>    
                <th class="px-4 py-2">{{ __('idioma.cantidadMaterialDisponible') }}</th>  

                @if (auth()->user()->rol == 'administrador')
                    <th class="px-4 py-2">{{ __('idioma.acciones') }}</th>
                @endif
            </tr>
            </thead>
            {{-- Mostramos los materiales --}}
            <tbody>
            @foreach($materiales as $material)
            <tr class="border-b border-gray-300">
                <td class="px-4 py-2">{{ $material->usuario_email }}</td>
                <td class="px-4 py-2">{{ $material->metal }}</td>
                <td class="px-4 py-2">{{ $material->dimensiones }}</td>
                <td class="px-4 py-2">{{ $material->getCantidadDisponible() }}</td>
                
                {{-- Opciones de modificar/eliminar solo para admin--}}
                @if (auth()->user()->rol == 'administrador')
                <td class="px-4 py-2">
                    <a href="/modmaterial/{{ $material->id }}">{{ __('idioma.editar') }}</a> | 
                    <a href="/eliminarmaterial/{{ $material->id }}">{{ __('idioma.eliminar') }}</a>
                </td>
                @endif
            </tr>  
            @endforeach
            </tbody>  

        <table>
    </div>
{{--Ajax para buscador de materiales por campo "metal"--}}
    <script type="text/javascript"> 
        $(document).ready(function() {
            //Al escribir en el formulario de busqueda
            $('#metal').on('keyup', function(){
                var buscar = $(this).val();
                $.ajax({
                    url: '{{ url('buscarmaterials')}}',
                    type: 'GET',
                    data: {'metal': buscar},
                    success: function (data) {
                        $("#lista_busqueda").html(data);
                        //Carga una nueva lista de materiales al lado del buscador
                    }
                })
            });
            $(document).on('click', 'li', function(){
                var value = $(this).text();
                $('#metal').val(value);
                //Pone el elemento seleccionado en el input de búsqueda
                $("#tabla_materiales").html("");
                $.ajax({
                    url: '{{ url('buscarmaterial')}}',
                    type: 'POST',
                    data: {
                        metal: value,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        $("#tabla_materiales").html(data);
                        //Carga los materiales de la busqueda
                    }
                })
            })

        });
        </script>
        
@endsection
