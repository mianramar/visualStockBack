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
    {{--Si hay mensaje de error --}}
    @if ($mensajeError == true)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ __('idioma.mensajeErrorModAlbaran1') }}</strong> {{ __('idioma.mensajeErrorModAlbaran2') }}{{ $infoError}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    {{-- Formulario para editar --}}
    <form action="/modalbaran/{{ $albaran->id }}" method="POST">
        @csrf
        @method('PUT')
          <div class="form-group">
              <label for="numero">{{ __('idioma.numeroAlbaran') }}</label>
              <input type="text" class="form-control" id="numero" name="numero" value="{{ $albaran->numero }}" > {{-- Recuperamos el numero anterior --}}
          </div><br> 
          @error('numero') 
              <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
          @enderror
          <div class="form-group">
              <label for="fecha">{{ __('idioma.fechaAlbaran') }}</label>
              <input type="date" class="form-control" id="fecha" name="fecha"  value="{{ $albaran->fecha }}" >
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
          <div class="form-group">
          <label for="tipo">{{ __('idioma.tipoAlbaran') }}</label>
            <input type="text" class="form-control" id="tipo" name="tipo" value="{{ $albaran->tipo }}" disabled> {{--El tipo no se puede modificar --}}
          </div>
          {{-- Mensaje de error de validaciones --}} 
          @error('tipo')
              <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
          @enderror
  
          <div class="form-group">
              <label>{{ __('idioma.materialAlbaran') }}</label>
              <br>
              @foreach($materials as $material) {{-- Listado de materiales asignados al albaran --}}
              <div class="row pt-2">
                <div class="col-md-6 form-control">
                    {{ $material->metal }} | {{ $material->dimensiones }} {{--caracteristicas--}}
                </div>
                  <div class="col-md-6 form-control"> {{-- Mostramos cantidad y precio --}}
                    <input type="hidden" name="materials[{{ $material->id }}][id]" value="{{ $material->id }}">
                      <label for="cantidad">Cantidad:</label>
                      <input type="text"  name="materials[{{ $material->id }}][cantidad]"  value="{{ $material->pivot->cantidad }}" >
                      <label for="precio">Precio:</label>
                      <input type="text" name="materials[{{ $material->id }}][precio]" value="{{ $material->pivot->precio }}" >
                  </div>
              </div>
          @endforeach
          </div> 
          {{-- Mensaje de error de validaciones --}} 
          @error('materials[]')
              <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
          @enderror

        <p>
            <button type="submit" class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;">
            {{ __('idioma.modificarAlbaran') }} {{-- Botón de crear --}}
            </button>
        </p>
      </form>
        
@endsection
