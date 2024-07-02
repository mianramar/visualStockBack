{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
    <h1 class="font-semibold text-xl">{{ __('idioma.modUsuario') }}</h1><br>
    <div style="display: flex;   justify-content: flex-end; ">
        {{--Enlace para volver--}}
    <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/listaxeusuarios">{{ __('idioma.volver') }}</a>
    </div>
    <div class="row">
        <div class="col-md-6">
            {{--Formulario de modificacion de usuario--}}
            <form action="/modusuario/{{ $usuario->id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
            <label for="name" class="block text-gray-700 font-medium mb-2">{{ __('idioma.nombreUsuario') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name', $usuario->name ) }}"
                                    class="form-control">
                </div>
                {{-- Mensaje de error de validaciones --}}
                @error('name')
                    <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
                @enderror

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-2">{{ __('idioma.emailUsuario') }}</label>
                    <input type="text" name="email" id="email" value="{{ old('email', $usuario->email ) }}"
                                        class="form-control">
                    </div>
                    {{-- Mensaje de error de validaciones --}}
                @error('email')
                    <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
                @enderror

                <div class="mb-4">
                    <label for="rol" :value="__('Rol')" >{{ __('idioma.rolUsuario') }}</label>
                    <select name="rol" id="rol" class="form-control">
                        <option value="administrador" selected>Admin</option>
                        <option value="usuario">Usuario</option>
                    </select>
                    <x-input-error :messages="$errors->get('rol')" class="mt-2" />
                </div>
                    {{-- Mensaje de error de validaciones --}}
                    @error('rol')
                        <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
                    @enderror

                    <div class="mb-4">
                        <label for="nombre" class="block text-gray-700 font-medium mb-2">{{ __('idioma.nombrePerfil') }}</label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $perfil->nombre ) }}"
                        class="form-control">
                        </div>
                        {{-- Mensaje de error de validaciones --}}
                        @error('nombre')
                        <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
                        @enderror
                        
                        <div class="mb-4">
                        <label for="apellido" class="block text-gray-700 font-medium mb-2">{{ __('idioma.apellidoPerfil') }}</label>
                        <input type="text" name="apellido" id="apellido" value="{{ old('email', $perfil->apellido ) }}"
                        class="form-control">
                        </div>
                        {{-- Mensaje de error de validaciones --}}
                        @error('apellido')
                        <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
                        @enderror


            <button type="submit" class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;">
                {{ __('idioma.gardarCambios') }} {{-- Gardamos os cambios --}}
            </button>
            </form>
        </div>
        <div class="col-md-6">
            <img id="imagenPerfil" src="{{ asset($perfil->imagen) }}" alt="Imagen usuario">
            {{--Formulario para cambiar la imagen--}}
            <form id="formularioArchivo" enctype="multipart/form-data" >
                @csrf
                <input type="file" name="archivo" id="archivo" class="mt-3">
                <p class="mt-3"><input type="button" class="font-medium py-2 px-4 rounded upload" value="{{ __('idioma.fotoPerfil') }}"><p>
            </form>            
        </div>
    </div>
    {{--Ajax para cambiar la imagen--}}
    <script type="text/javascript">
        $(document).ready(function() {
            $(".upload").on('click', function() {
                //Creamos un FormData con los datos del formulario (archivo de la imagen)
                var formData = new FormData();
                var archivo = document.getElementById('archivo').files[0];
                formData.append('archivo', archivo);
                // Obtén el token CSRF del formulario
                var token = $('input[name="_token"]').val();
                formData.append('_token', token);
                
                //Llamamos a modperfil
                $.ajax({
                    url: "/modperfil", 
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var urlCompleta = "{{ asset('') }}" + response;
                        if (response != -1) {
                            $("#imagenPerfil").attr("src", urlCompleta);
                        } else {
                            alert("{{ __('idioma.mensajeErrorImagenPerfil') }}");
                        }
                    }
                });
            })
        });
        </script>
        
@endsection
