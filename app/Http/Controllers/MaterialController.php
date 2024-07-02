<?php

/*

Título: Entrega Final

Autor: Miguel Ángel Rama Martínez.

Data modificación: 17/06/2024

Versión 1.0

*/

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Albaran;
use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) //Método GET
    {
        $materiales = Material::all();

        //Para mostrar los email de los usuarios en lugar de los ID, buscamos el email del usuario que coincida con el id, y usamos "first()" para obtener una empresa específica.
        foreach($materiales as $material){

            $usuario = User::where('id', $material->user_id)->first();
            $material->usuario_email = $usuario->email;
        }

        return response()->json($materiales, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //Método POST
    {
        try{
            //Validaciones
            $request->validate([
                'metal' => 'required|string|max:50',
                'dimensiones' => ['required','string','max:50','regex:/.*x.*x.*/'],
            ], [
                'metal.required' => 'El campo metal es obligatorio',
                'metal.max' => 'El campo metal no puede tener mas de 50 caracteres',
                'dimensiones.required' => 'El campo dimensiones es obligatorio',
                'dimensiones.max' => 'El campo dimensiones no puede tener mas de 50 caracteres',
                'dimensiones.regex' => 'El campo dimensiones debe contener dos "x"',
            ]);

            //Crear nuevo material con los inputs
            $material = new Material();
            $material->metal = $request->input('metal');
            $material->dimensiones = $request->input('dimensiones');
            $material->cantidad_disponible = 0;
            //El usuario que crea el material será el que esta logeado
            $userId = auth()->id();
            $material->user_id = $userId;
            $material->save();

            // Redirección co mensaxe
            return response()->json($material, 200);
        //Capturamos la exception que salta si falla alguna validacion
        } catch (ValidationException $e) {
            //Capturamos los errores en un array
            $mensajesError = [];
            //Recuperamos en el array los mensajes de error
            foreach ($e->errors() as $campoError) {
                foreach ($campoError as $mensaje) {
                    $mensajesError[] = $mensaje;
                }
            }
            //Y devolvemos el array con los errores como respuesta
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $mensajesError,
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id) //Metodo GET Individual
    {
        //Busca el material seleccionado
        $material = Material::find($id); 
       
        return response()->json($material, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) //Método POST
    {
        try{
            //Validaciones
            $request->validate([
                'metal' => 'required|string|max:50',
                'dimensiones' => ['required','string','max:50','regex:/.*x.*x.*/'],
            ], [
                'metal.required' => 'El campo metal es obligatorio',
                'metal.max' => 'El campo metal no puede tener mas de 50 caracteres',
                'dimensiones.required' => 'El campo dimensiones es obligatorio',
                'dimensiones.max' => 'El campo dimensiones no puede tener mas de 50 caracteres',
                'dimensiones.regex' => 'El campo dimensiones debe contener dos "x"',
            ]);
            //Busca el material
            $material = Material::find($id);
            $material->metal = $request->input('metal');
            $material->dimensiones = $request->input('dimensiones');
            $material->save();

            // Devolvemos o resultado
            return response()->json($material, 200);
            //Capturamos la exception que salta si falla alguna validacion
        } catch (ValidationException $e) {
            //Capturamos los errores en un array
            $mensajesError = [];
            //Recuperamos en el array los mensajes de error
            foreach ($e->errors() as $campoError) {
                foreach ($campoError as $mensaje) {
                    $mensajesError[] = $mensaje;
                }
            }
            //Y devolvemos el array con los errores como respuesta
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $mensajesError,
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) //Método DELETE
    {
        //Busca el material con los datos de la N:M
        $materialAEliminar = Material::findOrFail($id);
        $productos = $materialAEliminar->productos()->withPivot('cantidad_consumida', 'cantidad_producida')->get();

        // Si no está vacía
        if (!$productos->isEmpty()) {
            //No se puede borrar porque tiene productos asociadoos
            return response()->json(['mensaje' => 'El material no se puede borrar porque tiene productos asociados'], 400);
        } else {
            //Si está vacía en al relacion borramos el material y sus relaciones con albaranes de entrada
            $materialAEliminar->delete();
        }

        // Redirección co mensaxe
        return response()->json(['mensaje' => 'El material se ha borrado correctamente'], 200);
    }



    /* FUNCIONS CORRESPONDENTES AS VISTAS*/


    /**
     * Mostrar os material
     */
    public function listaxematerials()
    {
        //Recuperamos los materiales
        $materiales = Material::all();

        //Para mostrar los email de los usuarios en lugar de los ID, buscamos el email del usuario que coincida con el id, y usamos "first()" para obtener una empresa específica.
        foreach($materiales as $material){

            $usuario = User::where('id', $material->user_id)->first();
            $material->usuario_email = $usuario->email;
        }

        return view('materiales.materials', [
            'materiales' => $materiales,
            'mensajeError' => false,
            'mensajeSuccess' => false,
        ]);
    }

    /**
     * Vista para crear un novo material
     */
    public function novomaterial()
    {
        // Levanos a vista para crear un novo material
        return view('materiales.novomaterial');
    }

    /**
     * Funcion para crear un novo material
     */
    public function crearnovomaterial(Request $request)
    {
        // Validacións 
        /*$request->validate([
            'nome' => 'required|unique:materials|max:50',
        ]);*/

        //Creamos un nuevo material
        $material = new Material();
        $material->metal = $request->input('metal');
        $material->dimensiones = $request->input('dimensiones');
        $material->cantidad_disponible = 0;
        //El usuario que crea el material será el que esta logeado
        $userId = auth()->id();
        $material->user_id = $userId;
        $material->save();

        // Redirección co mensaxe
        return redirect()-> to('/listaxematerials')->with('success', 'O material foi creado correctamente.');
    }


    /**
     * Vista para editar un material
     */
    public function modmaterial($id)
    {
        //Busca el amterial
        $material = Material::find($id); 
        // Devolve a vista con datos
        return view('materiales.modmaterial')->with('material',$material);
    }

    /**
     * Funcion para editar o material
     */
    public function editmodmaterial(Request $request, $id)
    {
        /*//Validacións
        $request->validate([
            'nome' => 'required|unique:materials|max:50',
        ]); */

        // Busca el material, cambia los valores y los guarda
        $material = Material::find($id);
        $material->metal = $request->input('metal');
        $material->dimensiones = $request->input('dimensiones');
        $material->save();

        // Redirección co mensaxe
        return redirect()->to('listaxematerials')->with('success', 'A material foi actualizada correctamente.');
    }

    /**
     * Elimina un material
     */
    public function eliminarmaterial($id)
    {
        //Busca el material con los vlaores de la pivote
        $materialAEliminar = Material::findOrFail($id);
        $productos = $materialAEliminar->productos()->withPivot('cantidad_consumida', 'cantidad_producida')->get();

        if (!$productos->isEmpty()) { //No se podrá eliminar
            //Para volver a listarlos en el resultado
            $materiales = Material::all();
            //Para mostrar los email de los usuarios en lugar de los ID, buscamos el email del usuario que coincida con el id, y usamos "first()" para obtener una empresa específica.
            foreach($materiales as $material){

                $usuario = User::where('id', $material->user_id)->first();
                $material->usuario_email = $usuario->email;
            }
            //Volvemos a la vista de los amteriales con el mensaje de error
            return view('materiales.materials', [
                'materiales' => $materiales,
                'mensajeError' => true,
                'mensajeSuccess' => false,
            ]);
        } else {
            //Finalmente borramos el material y sus relaciones con albaranes de entrada
            $materialAEliminar->delete();
        }

        //Para volver a listarlos en el resultado
        $materiales = Material::all();
        //Para mostrar los email de los usuarios en lugar de los ID, buscamos el email del usuario que coincida con el id, y usamos "first()" para obtener una empresa específica.
        foreach($materiales as $material){

            $usuario = User::where('id', $material->user_id)->first();
            $material->usuario_email = $usuario->email;
        }
        // Redirección co mensaxe success
        return view('materiales.materials', [
            'materiales' => $materiales,
            'mensajeError' => false,
            'mensajeSuccess' => true,
        ]);
    }


    public function buscarmaterials(Request $request){ //Función buscador ajax autocompletar
        if ($request->ajax()) {
            $materiales = Material::where('metal', 'LIKE', $request->metal.'%')->get();
            $output='';
            // Si encuentra materiales la lista cambia a un display:block
            if (count($materiales) >0) {
                $output = '<ul class="list-group" style="display:block;">';
                //Por cada material crea un li
                foreach($materiales as $material){
                    $output .= '<li class="list-group-item">' . $material->metal . '</li>';
                }
                $output .= '</ul>';
            } else { //Si no encuentra, mensaje de no se encontraron
                $output .= '<li class"list-group-item"> Non se atoparon resultados...</li>';
            } 
            return $output;
        }
        //Para volver a listarlos en el resultado
        $materiales = Material::all();
        //Para mostrar los email de los usuarios en lugar de los ID, buscamos el email del usuario que coincida con el id, y usamos "first()" para obtener una empresa específica.
        foreach($materiales as $material){

            $usuario = User::where('id', $material->user_id)->first();
            $material->usuario_email = $usuario->email;
        }
        // Redirección
        return view('materiales.buscarmaterial', [
            'materiales' => $materiales
        ]);
    }

    public function buscarmaterial(Request $request){ //Función buscador ajax al elegir el elemento
        if ($request->ajax()) {
            $materiales = Material::where('metal', 'LIKE', '%'.$request->input('metal').'%')->get();
            //Para mostrar los email de los usuarios en lugar de los ID, buscamos el email del usuario que coincida con el id, y usamos "first()" para obtener una empresa específica.
            if ($materiales->isNotEmpty()) {
                foreach ($materiales as $material) {
                    $usuario = User::where('id', $material->user_id)->first();
                    $material->usuario_email = $usuario->email;
                }
            } 
            return view('materiales.buscarmaterial', [
                'materiales' => $materiales
            ]);
        }
    }   


}
