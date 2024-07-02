{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}

<table class="table table-striped">
    {{-- Cabecera de la tabla --}}    
    <thead>
    <tr class="bg-gray-300">
        <th class="px-4 py-2">{{ __('idioma.emailMaterial') }}</th>    
        <th class="px-4 py-2">{{ __('idioma.metalMaterial') }}</th>    
        <th class="px-4 py-2">{{ __('idioma.dimensionesMaterial') }}</th>    
        <th class="px-4 py-2">{{ __('idioma.cantidadMaterialDisponible') }}</th>  

        @if (auth()->user()->rol == 'administrador') {{--Si es admin muestra la columna de acciones--}}
            <th class="px-4 py-2">{{ __('idioma.acciones') }}</th>
        @endif
    </tr>
    </thead>
    {{-- Mostramos los materiales --}}
    <tbody>
    @foreach($materiales as $material)
    <tr class="border-b border-gray-300">
        <td class="px-4 py-2">{{ $material->usuario_email }}</td>
        <td class="px-4 py-2">{{ $material->metal }}</td>
        <td class="px-4 py-2">{{ $material->dimensiones }}</td>
        <td class="px-4 py-2">{{ $material->getCantidadDisponible() }}</td>
        
        {{--Si es admin muestra las opciones modificar y eliminar--}}
        @if (auth()->user()->rol == 'administrador')
        <td class="px-4 py-2">
            <a href="/modmaterial/{{ $material->id }}">{{ __('idioma.editar') }}</a> | 
            <a href="/eliminarmaterial/{{ $material->id }}">{{ __('idioma.eliminar') }}</a>
        </td>
        @endif
    </tr>  
    @endforeach
    </tbody>  

<table>