{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
    <h1 class="font-semibold text-xl">{{ __('idioma.detalleAlbaran') }}</h1><br>
    <div style="display: flex;   justify-content: flex-end; ">
         {{-- Enlace para volver al listado de albaranes --}}
        <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/listaxealbarans?tipo={{ $albaran->tipo }}">{{ __('idioma.volver') }}</a>
    </div>
    {{--Presentación tipo form disabled --}}
    <form >
        @csrf
          <div class="form-group">
              <label for="numero">{{ __('idioma.numeroAlbaran') }}</label>
              <input type="text" class="form-control" id="numero" name="numero" value="{{ $albaran->numero }}" disabled>
          </div>

          <div class="form-group">
              <label for="fecha">{{ __('idioma.fechaAlbaran') }}</label>
              <input type="date" class="form-control" id="fecha" name="fecha"  value="{{ $albaran->fecha }}" disabled>
          </div>  

          <div class="form-group">
            <label for="empresa_nome">{{ __('idioma.empresaNombreAlbaran') }}</label>
            <input type="text" class="form-control" id="empresa_nome" name="empresa_nome" value="{{ $albaran->empresa_nome }}" disabled>
          </div>

          <div class="form-group">
          <label for="tipo">{{ __('idioma.tipoAlbaran') }}</label>
            <input type="text" class="form-control" id="tipo" name="tipo" value="{{ $albaran->tipo }}" disabled>
          </div>

  
          @if ($tipo == 'entrada') {{--Si es de entrada --}}
          <div class="form-group">
              <label>{{ __('idioma.materialAlbaran') }}</label>
              <br>
              @foreach($materials as $material)
                <div class="row pt-2">
                <div class="col-md-6 form-control">
                    {{ $material->metal }} | {{ $material->dimensiones }} 
                </div>
                    <div class="col-md-6 form-control">
                        <label for="cantidad">Cantidad:</label>
                        <input type="text"  value="{{ $material->pivot->cantidad }}" disabled>
                        <label for="precio">Precio:</label>
                        <input type="text"  value="{{ $material->pivot->precio }}" disabled>
                    </div>
                </div>
                @endforeach
          </div> 
          @endif
          @if ($tipo == 'salida'){{--Si es de salida --}}
          <div class="form-group">
              <label>{{ __('idioma.productoAlbaran') }}</label>
              <br>
              @foreach($productos as $producto)
                <div class="row pt-2">
                <div class="col-md-6 form-control">
                    {{ $producto->nombre }} | {{ $producto->tipo }} 
                </div>
                    <div class="col-md-6 form-control">
                        <label for="cantidad">Cantidad:</label>
                        <input type="text"  value="{{ $producto->pivot->cantidad }}" disabled>
                        <label for="precio">Precio:</label>
                        <input type="text"  value="{{ $producto->pivot->precio }}" disabled>
                    </div>
                </div>
                @endforeach
          </div> 
          @endif
          
          <div class="form-group"> {{--Precio Total--}}
            <label for="numero"><strong>{{ __('idioma.precioTotal') }}</strong></label>
            <input type="text" class="form-control col-md-2" id="numero" name="numero" value="{{ $precio }}€" disabled>
        </div>

      </form>
        
@endsection
