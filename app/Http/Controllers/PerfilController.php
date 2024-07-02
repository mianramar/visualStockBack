<?php

/*

Título: Entrega Final

Autor: Miguel Ángel Rama Martínez.

Data modificación: 17/06/2024

Versión 1.0

*/

namespace App\Http\Controllers;

use App\Http\Requests\StorePerfilRequest;
use App\Http\Requests\UpdatePerfilRequest;
use App\Models\Perfil;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function editarimagen(Request $request)
    {
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            // Guardar el archivo en public/imaxes con un nombre aleatorio para que no se sobreescriban
            $ruta = $archivo->store('imaxes', 'public'); // 'imaxes' es el nombre del directorio dentro de la carpeta 'public'

            // Obtener el Id del usuario actual autenticado
            $userId = auth()->id();

            // Busca el perfil asociado al user_id
            $perfil = Perfil::where('user_id', $userId)->first();
            $perfil->imagen = $ruta;
            // Guardar los cambios en la base de datos
            $perfil->save();
            return response()->json($ruta, 200);
        }
        // Sino entra en el if da error
        return response()->json('Ha ocurrido un error', 422);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePerfilRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Perfil $perfil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePerfilRequest $request, Perfil $perfil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perfil $perfil)
    {
        //
    }



       /**
     * Display a listing of the resource.
     */
    public function verperfil()
    {
        // Obtener el Id del usuario actual autenticado
        $userId = auth()->id();

        // Busca el perfil asociado al user_id
        $perfil = Perfil::where('user_id', $userId)->first();

        return view('perfil.verPerfil')->with('perfil',$perfil);

    }


        /**
     * Update the specified resource in storage.
     */
    public function editmodperfil(Request $request)
    {
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            // Guardar el archivo en public/imaxes con un nombre aleatorio para que no se sobreescriban
            $ruta = $archivo->store('imaxes', 'public'); // 'imaxes' es el nombre del directorio dentro de la carpeta 'public'

            // Obtener el Id del usuario actual autenticado
            $userId = auth()->id();

            // Busca el perfil asociado al user_id
            $perfil = Perfil::where('user_id', $userId)->first();
            $perfil->imagen = $ruta;
            // Guardar los cambios en la base de datos
            $perfil->save();
            return $ruta;
        }
        // Sino entra en el if da error
        return -1;
    }

    public function editdatosperfil(Request $request, $id)
    {
        //Busca el eprfil que coincide con el id
        $perfil = Perfil::where('user_id', $id)->first();
        $perfil->nombre = $request->input('nombre');
        $perfil->apellido = $request->input('apellido');
        $perfil->save();

        // Redirección co mensaxe
        return redirect()->to('verperfil')->with('success', 'Os datos foron actualizados correctamente.');
    }

}
