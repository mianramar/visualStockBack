{{-- 


    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
    <h1 class="font-semibold text-xl">{{ __('idioma.tituloPrediccion') }}</h1><br>

    <div class="row">
        <div class="col-md-12" >
            {{-- Este filtro convierte los saltos de línea (\n) en etiquetas HTML <br> --}}
            {!! nl2br($prediccion) !!}
        </div>

    </div>

        
@endsection
