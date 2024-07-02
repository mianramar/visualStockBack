{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
    <h1 class="font-semibold text-xl">{{ __('idioma.novoAlbaran') }}</h1><br>
    <div style="display: flex;   justify-content: flex-end; ">
        {{-- Enlace para volver al listado de albaranes --}}
        <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/listaxealbarans?tipo=entrada">{{ __('idioma.volver') }}</a>
    </div>
{{--Formulario nuevo albaran --}}
    <form action="/novoalbaran" method="POST">
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
        <div class="form-group">{{--Tipo de albaran no modificable--}}
        <label for="tipo">{{ __('idioma.tipoAlbaran') }}</label>
        <input type="text" class="form-control" id="tipo" name="tipo" value="entrada" disabled>

        </div>
        {{-- Mensaje de error de validaciones --}} 
        @error('tipo')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror

        <div class="form-group">
            <label>{{ __('idioma.materialesSinAsignar') }}</label><a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/novomaterial">{{ __('idioma.nuevoMaterial') }}</a>
            <br>
            @foreach($materials as $material) {{--Por cada material --}}
            <div class="row pt-2">
                <div class="col-md-6 form-control"> {{--Mostramos un checkbox y campos a cubrir --}}
                    <input type="checkbox" name="materiales_seleccionados[]" id=materialseleccionado value="{{ $material->id }}"> {{ $material->metal }} | {{ $material->dimensiones }} | {{ __('idioma.disponibilidad') }}: {{ $material->getCantidadDisponible() }} 
                    <input type="hidden" name="materials[{{ $material->id }}][id]" value="{{ $material->id }}">
                </div>
                <div class="col-md-6 form-control">
                    <label for="cantidad">Cantidad:</label>
                    <input type="text" name="materials[{{ $material->id }}][cantidad]" >
                    <label for="precio">Precio:</label>
                    <input type="text" name="materials[{{ $material->id }}][precio]" value=0>
                </div>
            </div>
        @endforeach
        </div> 
        {{-- Mensaje de error de validaciones --}} 
        @error('materiales_seleccionados')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror
        {{-- Mensaje de error de validaciones --}} 
        @error('materials')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
        @enderror

        <p>
            <button type="submit" class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;">
            {{ __('idioma.crear') }} {{-- Botón de crear --}}
            </button>
        </p>
    </form>
    
        
@endsection
