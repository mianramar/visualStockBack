{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
    <h1 class="font-semibold text-xl">{{ __('idioma.modEmpresa') }}</h1><br>
    <div style="display: flex;   justify-content: flex-end; ">
        {{--Enlace para volver--}}
    <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/listaxeempresas">{{ __('idioma.volver') }}</a>
    </div>
    <form action="/modempresa/{{ $empresa->id }}" method="POST">
      @csrf
      @method('PUT')
       <div class="mb-4">
       <label for="nombre" class="block text-gray-700 font-medium mb-2">{{ __('idioma.nombreEmpresas') }}</label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $empresa->nombre ) }}"
            class="form-control">
        </div>
        {{-- Mensaje de error de validaciones --}}
        @error('nombre')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror

        <div class="mb-4">
        <label for="email" class="block text-gray-700 font-medium mb-2">{{ __('idioma.emailEmpresas') }}</label>
        <input type="text" name="email" id="email" value="{{ old('email', $empresa->email ) }}"
            class="form-control">
        </div>
        {{-- Mensaje de error de validaciones --}}
        @error('email')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror

        <div class="mb-4">
        <label for="telefono" class="block text-gray-700 font-medium mb-2">{{ __('idioma.telefonoEmpresas') }}</label>
        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $empresa->telefono ) }}"
            class="form-control">
        </div>
        {{-- Mensaje de error de validaciones --}}
        @error('telefono')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror
        
        <div class="mb-4">
        <label for="direccion" class="block text-gray-700 font-medium mb-2">{{ __('idioma.direccionEmpresas') }}</label>
        <input type="text" name="direccion" id="direccion" value="{{ old('direccion', $empresa->direccion ) }}"
            class="form-control">
        </div>
        {{-- Mensaje de error de validaciones --}}
        @error('direccion')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror
        

       <button type="submit" class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;">
        {{ __('idioma.gardarCambios') }} {{-- Gardamos os cambios --}}
       </button>
    </form>
    
        
@endsection
