{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
<div style="display: flex;   justify-content: flex-end; ">
    {{--Enlace para volver--}}
    <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/listaxeproductos">{{ __('idioma.volver') }}</a>
</div><br>
{{--Formulario para nuevo producto--}}
<form action="/novoproducto" method="POST">
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <h3>{{ __('idioma.materialesSinAsignar') }}</h3>
                </div>
                <div class="col-md-2">
                    <h3>{{ __('idioma.cantidadMaterial') }}</h3>
                </div>
            </div>
                @csrf          
                  <div class="form-group">
                      @foreach($materiales as $material) {{--Por cada material muestra un checkbox--}}
                      <div class="row pt-2">
                        <div class="col-md-6 form-control">
                            <input type="checkbox" name="materiales_seleccionados[]" value="{{ $material->id }}"> {{ $material->metal }} | {{ $material->dimensiones }} | {{ __('idioma.disponibilidad') }}: {{ $material->getCantidadDisponible() }} 
                            <input type="hidden" name="materials[{{ $material->id }}][id]" value="{{ $material->id }}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="materials[{{ $material->id }}][cantidad]" class="form-control">
                        </div>
                      </div>
                  @endforeach
                  </div> 
                  {{-- Mensaje de error de validaciones --}} 
                  @error('materials')
                      <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
                  @enderror


        </div>
        <div class="col-md-4">
            <h3>{{ __('idioma.nuevoProducto') }}</h3>

            <div class="form-group">
                <label for="nombre">{{ __('idioma.nombreProducto') }}</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}">
            </div><br> 
            @error('nombre')
                <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
            @enderror

            <div class="form-group">
                <label for="tipo">{{ __('idioma.tipoProducto') }}</label>
                <select name="tipo" id="tipo">
                        <option value="intermedio" selected>{{ __('idioma.intermedio') }}</option>
                        <option value="terminado">{{ __('idioma.terminado') }}</option>
                </select>
                </div>
                {{-- Mensaje de error de validaciones --}} 
                @error('tipo')
                    <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
                @enderror

            <div class="form-group">
                <label for="cantidad_producida">{{ __('idioma.cantidadProducto') }}</label>
                <input type="text" class="form-control" id="cantidad_producida" name="cantidad_producida" value="{{ old('cantidad_producida') }}">

                </div>
                {{-- Mensaje de error de validaciones --}} 
                @error('cantidad_producida')
                    <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
                @enderror


            <div class="form-group">
                <label for="adicional">{{ __('idioma.adicionalProducto') }}</label>
                <input type="text" class="form-control" id="adicional" name="adicional" value="{{ old('adicional') }}">
            </div><br> 
            @error('adicional')
                <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
            @enderror

        </div>
    </div>   
              
    <div class="row d-flex justify-content-center">
        <button type="submit" class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;">
        {{ __('idioma.producirProducto') }} {{-- Botón de crear --}}
        </button>
    </div>
</form>    
@endsection
