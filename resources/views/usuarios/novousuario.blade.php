{{-- 

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 14/03/2024

    Versión 1.0

--}}
@extends('layouts.personalizada')

@section('contido')
    <h1 class="font-semibold text-xl">{{ __('idioma.nuevoUsuario') }}</h1><br>
    <div style="display: flex;   justify-content: flex-end; ">
        {{--Enlace para volver--}}
    <a class="font-medium py-2 px-4 rounded" style="border-width: 0.1em;" href="/listaxeusuarios">{{ __('idioma.volver') }}</a>
    </div>
    {{--Formulario de nuevo usuario--}}
    <form action="/novousuario" method="POST">
        @csrf

        <div class="mb-4">
            <label for="nombre" class="block text-gray-700 font-medium mb-2">{{ __('idioma.nombrePerfil') }}</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre' ) }}"
            class="form-control">
            </div>
            {{-- Mensaje de error de validaciones --}}
            @error('nombre')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
            @enderror
            
            <div class="mb-4">
            <label for="apellido" class="block text-gray-700 font-medium mb-2">{{ __('idioma.apellidoPerfil') }}</label>
            <input type="text" name="apellido" id="apellido" value="{{ old('email' ) }}"
            class="form-control">
            </div>
            {{-- Mensaje de error de validaciones --}}
            @error('apellido')
            <div class="text-danger">{{ $message }}</div><br> {{-- mensaxe en vermello de error --}}
            @enderror

        {{-- Name --}}
        <div class="mt-4">
            <label for="name" :value="__('Name')" >{{ __('idioma.nombreUsuario') }}</label>
            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email Address --}}
        <div class="mt-4">
            <label for="email" :value="__('Email')" >{{ __('idioma.emailUsuario') }}</label>
            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Rol --}}
        <div class="mt-4">
            <label for="rol" :value="__('Rol')" >{{ __('idioma.rolUsuario') }}</label>
            <select name="rol" id="rol" class="form-control">
                <option value="administrador" selected>Admin</option>
                <option value="usuario">Usuario</option>
            </select>
            <x-input-error :messages="$errors->get('rol')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div class="mt-4">
            <label for="password" :value="__('Password')" >{{ __('idioma.passUsuario') }}</label>

            <x-text-input id="password" class="form-control"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirm Password --}}
        <div class="mt-4">
            <label for="password_confirmation" :value="__('Confirm Password')" >{{ __('idioma.repetirPassUsuario') }}</label>

            <x-text-input id="password_confirmation" class="form-control"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div >
            <button class="font-medium py-2 px-4 rounded mt-3">
                {{ __('idioma.registrarUsuario') }} {{--Botón para registrar--}}
            </button>
        </div>
    </form>
@endsection
