{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
    <h1 class="font-semibold text-xl">{{ __('idioma.modificarProducto') }}</h1>
    <div style="display: flex;   justify-content: flex-end; ">
        {{--Enlace para volver--}}
        <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/listaxeproductos">{{ __('idioma.volver') }}</a>
    </div><br>
    {{--Formulario de modificar producto--}}
    <form action="/modproducto/{{ $producto->id }}" method="POST">
      @csrf
      @method('PUT')
       <div class="form-group">
            <label for="nombre">{{ __('idioma.nombreProducto') }}</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}">
        </div><br>
        {{-- Mensaje de error de validaciones --}} 
        @error('metal')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror

        <div class="form-group">
            <label for="tipo">{{ __('idioma.tipoProducto') }}</label>
            {{--Dependiendo del producto mostramos datos de intermedio o terminado--}}
            @if ($producto->tipo == 'intermedio') 
                <select name="tipo" id="tipo">
                    <option value="intermedio" selected>{{ __('idioma.intermedio') }}</option>
                    <option value="terminado">{{ __('idioma.terminado') }}</option>
                </select>
            @endif
            @if ($producto->tipo == 'terminado')
                <select name="tipo" id="tipo">
                    <option value="intermedio" >{{ __('idioma.intermedio') }}</option>
                    <option value="terminado" selected>{{ __('idioma.terminado') }}</option>
                </select>
            @endif
        </div><br> 
        {{-- Mensaje de error de validaciones --}} 
        @error('tipo')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror
        {{--Campo adicional tanto para intermedio como para terminado--}}
        <div class="form-group">
            <label for="adicional">{{ __('idioma.adicionalProducto') }}</label>
            <input type="text" class="form-control" id="adicional" name="adicional" value="{{ old('nombre', $producto->campo_adicional) }}">
        </div><br> 
        @error('adicional')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror


        <button type="submit" class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;">
            {{ __('idioma.modificar') }} {{-- Botón de modificar --}}
        </button>
    </form>
    
        
@endsection

