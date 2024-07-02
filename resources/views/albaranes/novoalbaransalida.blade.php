{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
    <h1 class="font-semibold text-xl">{{ __('idioma.novoAlbaranSalida') }}</h1><br>
    <div style="display: flex;   justify-content: flex-end; ">
        {{-- Enlace para volver al listado de albaranes --}}
        <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/listaxealbarans?tipo=salida">{{ __('idioma.volver') }}</a>
    </div>
    {{--Formulario nuevo albaran --}}
    <form action="/novoalbaransalida" method="POST">
      @csrf
        <div class="form-group">
            <label for="numero">{{ __('idioma.numeroAlbaran') }}</label>
            <input type="text" class="form-control" id="numero" name="numero" value="{{ old('numero') }}">
        </div><br> 
        @error('numero')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror
        <div class="form-group">
            <label for="fecha">{{ __('idioma.fechaAlbaran') }}</label>
            <input type="date" class="form-control" id="fecha" name="fecha"  required>
        </div>  
        @error('fecha')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror
        <div class="form-group">
        <label for="empresa_id">{{ __('idioma.seleccionaEmpresa') }}</label>
            <select name="empresa_id" id="empresa_id">
                @foreach($empresas as $empresa) {{-- Desplegable con el listado de las empresas --}}
                    <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                @endforeach
            </select>
        </div>
        {{-- Mensaje de error de validaciones --}} 
        @error('empresa_id')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror
        <div class="form-group"> {{--Tipo de albaran no modificable--}}
        <label for="tipo">{{ __('idioma.tipoAlbaran') }}</label>
        <input type="text" class="form-control" id="tipo" name="tipo" value="salida" disabled>

        </div>
        {{-- Mensaje de error de validaciones --}} 
        @error('tipo')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror

        <div class="form-group">
            <label>{{ __('idioma.productosDisponibles') }}</label>
            <br>
            @foreach($productos as $producto) {{--Por cada producto --}}
            <div class="row pt-2">
                <div class="col-md-6 form-control"> {{--Mostramos un checkbox y campos a cubrir --}}
                    <input type="checkbox" name="productos_seleccionados[]" value="{{ $producto->id }}"> {{ $producto->nombre }} | {{ __('idioma.disponibilidad') }}: {{ $producto->getCantidadDisponible() }} 
                    <input type="hidden" name="productos[{{ $producto->id }}][id]" value="{{ $producto->id }}">
                </div>
                <div class="col-md-6 form-control">
                    <label for="cantidad">Cantidad:</label>
                    <input type="text" name="productos[{{ $producto->id }}][cantidad]" >
                    <label for="precio">Precio:</label>
                    <input type="text" name="productos[{{ $producto->id }}][precio]" >
                </div>
            </div>
        @endforeach
        </div> 
        {{-- Mensaje de error de validaciones --}} 
        @error('productos_seleccionados')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror

        {{-- Mensaje de error de validaciones --}} 
        @error('productos')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror

        <p>
            <button type="submit" class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;">
            {{ __('idioma.crear') }} {{-- Botón de crear --}}
            </button>
        </p>
    </form>
    
        
@endsection
