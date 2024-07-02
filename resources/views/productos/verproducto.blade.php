{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
<div style="display: flex;   justify-content: flex-end; "> {{--Enlace para volver--}}
    <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/listaxeproductos">{{ __('idioma.volver') }}</a>
</div>
<form >
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6">
                    <h3>{{ __('idioma.materialesConsumidos') }}</h3>
                </div>
                <div class="col-md-2">
                    <h3>{{ __('idioma.cantidadMaterial') }}</h3>
                </div>
            </div>
            @csrf          
            <div class="form-group">
                @foreach($materiales as $material) {{--Por cada material mostramos sus caracteristicas--}}
                    <div class="row pt-2">
                    <div class="col-md-6 form-control">
                        {{ $material->metal }} | {{ $material->dimensiones }}
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" value="{{ $material->pivot->cantidad_consumida }}" disabled>
                    </div>
                    </div>
                @endforeach
            </div> 
        </div>
        <div class="col-md-4">
            <h3>{{ __('idioma.verProducto') }}</h3>

            <div class="form-group">
                <label for="nombre">{{ __('idioma.nombreProducto') }}</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $producto->nombre }}" disabled>
            </div>

            <div class="form-group">
                <label for="tipo">{{ __('idioma.tipoProducto') }}</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $producto->tipo }}" disabled>
            </div>

            <div class="form-group">
                <label for="cantidad_producida">{{ __('idioma.cantidadProducto') }}</label>
                <input type="text" class="form-control" id="cantidad_producida" name="cantidad_producida" value="{{ $materiales[0]->pivot->cantidad_producida }}" disabled>
            </div>
               
            {{--Segun el tipo mostraremos tratamiento o garantia--}}
            @if ( $producto->tipo === 'intermedio')
                <div class="form-group">
                    <label >{{ __('idioma.tratamientoProducto') }}</label>
                    <input type="text" class="form-control" id="adicional" name="adicional" value="{{ $intermedio->tratamiento }}" disabled>
                </div><br> 
            @else
                <div class="form-group">
                    <label >{{ __('idioma.garantiaProducto') }}</label>
                    <input type="text" class="form-control" id="adicional" name="adicional" value="{{ $terminado->garantia }}" disabled>
                </div><br> 
            @endif
        </div>
    </div>   

</form>    
@endsection
