{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}

{{-- Contenido del menú superior --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
  <a class="navbar-brand" href="/dashboard">Inicio</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">{{--Rutas del menu de navegacion--}}
      @if (auth()->user()->rol == 'administrador') {{-- Si entramos como admin tambien veremos usuarios y empresas --}}
      <li class="nav-item">
        <a class="nav-link" href="{{ route('listaxeusuarios') }}">{{ __('idioma.usuarios') }} </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="{{ route('listaxeempresas') }}">{{ __('idioma.empresas') }} </a>
      </li>
      @endif
      <li class="nav-item ">
        <a class="nav-link" href="{{ route('listaxealbarans', ['tipo' => 'entrada']) }}">{{ __('idioma.entradas') }} </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="{{ route('listaxealbarans', ['tipo' => 'salida']) }}">{{ __('idioma.salidas') }} </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="{{ route('listaxematerials') }}">{{ __('idioma.almacen') }} </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="{{ route('novoproducto') }}">{{ __('idioma.produccion') }} </a>
      </li>

      <li class="nav-item dropdown"> {{-- Dropdown para los idiomas --}}
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Idioma
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="{{ route('idioma', 'es') }}">{{ __('idioma.spanish') }}</a>
          <a class="dropdown-item" href="{{ route('idioma', 'en') }}">{{ __('idioma.english') }}</a>
          <a class="dropdown-item" href="{{ route('idioma', 'gl') }}">@lang('idioma.galician')</a> 
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          {{ Auth::user()->name }} {{--Muestra el usuario legeado--}}
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown"> {{--Opciones de ver perfil o deslogearse--}}
          <a class="dropdown-item" href="/verperfil">{{ __('idioma.perfil') }}</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-dropdown-link :href="route('logout')"
                onclick="event.preventDefault();
                this.closest('form').submit();">
               {{ __('idioma.desconectar') }}
            </x-dropdown-link>
        </form>
        </div>
      </li>

      <li class="nav-item "> {{--Consulta API externa--}}
        <a class="nav-link" href="{{ route('verprediccion') }}">{{ __('idioma.verPrediccion') }} <span class="sr-only">(current)</span></a>
      </li>

    </ul>
  </div>
</nav>
