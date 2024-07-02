{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
    <h1 class="font-semibold text-xl">{{ __('idioma.nuevoMaterial') }}</h1><br>
    <div style="display: flex;   justify-content: flex-end; ">
        {{--Enlace para volver--}}
        <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/listaxematerials">{{ __('idioma.volver') }}</a>
    </div>
    <form action="/novomaterial" method="POST">
      @csrf
       <div class="form-group">
            <label for="metal">{{ __('idioma.metalMaterial') }}</label>
            <input type="text" class="form-control" id="metal" name="metal" value="{{ old('metal') }}">
        </div><br> 
        {{-- Mensaje de error de validaciones --}} 
        @error('metal')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror

        <div class="form-group">
            <label for="dimensiones">{{ __('idioma.dimensionesMaterial') }}</label>
            <input type="text" class="form-control" id="dimensiones" name="dimensiones" value="{{ old('dimensiones') }}">
        </div><br> 
        {{-- Mensaje de error de validaciones --}} 
        @error('dimensiones')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror

        <button type="submit" class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;">
            {{ __('idioma.crear') }} {{-- Botón de crear --}}
        </button>
    </form>
    
        
@endsection
