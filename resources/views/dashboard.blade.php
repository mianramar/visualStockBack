{{-- 

  Título: Entrega Final

  Autor: Miguel Ángel Rama Martínez.

  Data modificación: 14/03/2024

  Versión 1.0

--}}

@include('layouts.cabeceira')

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/dashboard">Inicio</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          {{--Si es administrador vera usuarios y empresas--}}
          @if (auth()->user()->rol == 'administrador')
          <li class="nav-item active">
            <a class="nav-link" href="{{ route('listaxeusuarios') }}">{{ __('idioma.usuarios') }} <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="{{ route('listaxeempresas') }}">{{ __('idioma.empresas') }} <span class="sr-only">(current)</span></a>
          </li>
          @endif 
          
          <li class="nav-item dropdown"> {{--Desplegable para idiomas--}}
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{ __('idioma.language') }}
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{ route('idioma', 'es') }}">{{ __('idioma.spanish') }}</a>
              <a class="dropdown-item" href="{{ route('idioma', 'en') }}">{{ __('idioma.english') }}</a>
              <a class="dropdown-item" href="{{ route('idioma', 'gl') }}">@lang('idioma.galician')</a> 
          </li>
    
          <li class="nav-item dropdown"> {{--Desplegable para opciones de ver perfil y deslogearse--}}
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{ Auth::user()->name }}
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="/verperfil">{{ __('idioma.perfil') }}</a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
    
                <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
            </div>
          </li>
          
        </ul>
      </div>
  </nav>

{{--Menu de las opciones--}}
<div class="row">
    <div class="col-sm-3  p-3 mb-5 ">
      <div class="card h-100 d-flex flex-column">
        <div class="card-body">
          <h5 class="card-title">{{ __('idioma.entradas') }} </h5>
          <p class="card-text">{{ __('idioma.descEntradas') }}</p>
        </div>
        <a href="{{ route('listaxealbarans', ['tipo' => 'entrada']) }}" class="btn btn-primary  mt-auto">{{ __('idioma.irEntradas') }}</a>

      </div>
    </div>
    <div class="col-sm-3 p-3 mb-5 ">
      <div class="card h-100 d-flex flex-column">
        <div class="card-body">
          <h5 class="card-title">{{ __('idioma.salidas') }} </h5>
          <p class="card-text">{{ __('idioma.descSalidas') }}</p>
        </div>
        <a href="{{ route('listaxealbarans', ['tipo' => 'salida']) }}" class="btn btn-primary  mt-auto">{{ __('idioma.irSalidas') }}</a>

      </div>
    </div>
    <div class="col-sm-3 p-3 mb-5 ">
        <div class="card h-100 d-flex flex-column">
          <div class="card-body">
            <h5 class="card-title">{{ __('idioma.almacen') }} </h5>
            <p class="card-text">{{ __('idioma.descAlmacen') }}</p>
          </div>
          <a href="{{ route('listaxematerials') }}" class="btn btn-primary mt-auto">{{ __('idioma.irAlmacen') }}</a>

        </div>
      </div>
      <div class="col-sm-3 p-3 mb-5 ">
        <div class="card h-100 d-flex flex-column">
          <div class="card-body">
            <h5 class="card-title">{{ __('idioma.produccion') }} </h5>
            <p class="card-text">{{ __('idioma.descProduccion') }}</p>
          </div>
          <a href="{{ route('novoproducto') }}" class="btn btn-primary mt-auto">{{ __('idioma.irProduccion') }}</a>

        </div>
      </div>
  </div>

  @include('layouts.pe')