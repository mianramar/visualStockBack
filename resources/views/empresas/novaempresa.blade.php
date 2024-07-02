{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
    <h1 class="font-semibold text-xl">{{ __('idioma.nuevaEmpresa') }}</h1><br>
    <div style="display: flex;   justify-content: flex-end; ">
        {{--Enlace para volver--}}
    <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/listaxeempresas">{{ __('idioma.volver') }}</a>
    </div>
    <form action="/novaempresa" method="POST">
      @csrf
       <div class="form-group">
            <label for="nombre">{{ __('idioma.nombreEmpresas') }}</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}">
        </div><br> 
        {{-- Mensaje de error de validaciones --}} 
        @error('nombre')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror

        <div class="form-group">
            <label for="nombre">{{ __('idioma.emailEmpresas') }}</label>
            <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}">
        </div><br> 
        {{-- Mensaje de error de validaciones --}} 
        @error('email')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror

        <div class="form-group">
            <label for="telefono">{{ __('idioma.telefonoEmpresas') }}</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}">
        </div><br> 
        {{-- Mensaje de error de validaciones --}} 
        @error('telefono')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror

        <div class="form-group">
            <label for="direccion">{{ __('idioma.direccionEmpresas') }}</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}">
        </div><br> 
        {{-- Mensaje de error de validaciones --}} 
        @error('nombre')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror

        <button type="submit" class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;">
            {{ __('idioma.crear') }} {{-- Botón de crear --}}
        </button>
    </form>
    
        
@endsection
