<?php

/*

Título: Entrega Final

Autor: Miguel Ángel Rama Martínez.

Data modificación: 17/06/2024

Versión 1.0

*/

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() //Método GET
    {
        // Recuperamos todas las empresas
        $empresas = Empresa::all();

        return response()->json($empresas, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //Metodo POST
    {
        try{
            //Validaciones
            $request->validate([
                'nombre' => 'required|string|max:50',
                'email' => 'required|email|max:50',
                'telefono' => 'required|numeric',
            ], [
                'nombre.required' => 'El campo nombre es obligatorio',
                'email.required' => 'El campo email es obligatorio',
                'email.email' => 'El campo email debe ser una dirección de correo válida',
                'telefono.required' => 'El campo telefono es obligatorio',
                'telefono.numeric' => 'El campo telefono solo acepta números',
            ]);
            //Creamos una nueva empresa con los datos
            $empresa = new Empresa();
            $empresa->nombre = $request->input('nombre');
            $empresa->email = $request->input('email');
            $empresa->telefono = $request->input('telefono');
            $empresa->direccion = $request->input('direccion');

            $empresa->save();

            // Redirección co mensaxe
            return response()->json($empresa, 200);
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
    public function show($id) // Get individual
    {
        //Busca la empresa por ID
        $empresa = Empresa::find($id); 
        
        return response()->json($empresa, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) //Método PUT
    {
        try{
            //Validaciones
            $request->validate([
                'nombre' => 'required|string|max:50',
                'email' => 'required|email|max:50',
                'telefono' => 'required|numeric',
            ], [
                'nombre.required' => 'El campo nombre es obligatorio',
                'email.required' => 'El campo email es obligatorio',
                'email.email' => 'El campo email debe ser una dirección de correo válida',
                'telefono.required' => 'El campo telefono es obligatorio',
                'telefono.numeric' => 'El campo telefono solo acepta números',
            ]);

            //Busca la empresa y con sus valores
            $empresa = Empresa::find($id);
            $empresa->nombre = $request->input('nombre');
            $empresa->email = $request->input('email');
            $empresa->telefono = $request->input('telefono');
            $empresa->direccion = $request->input('direccion');
            $empresa->save();

            // Redirección co mensaxe
            return response()->json($empresa, 200);
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
        // Busca la empresa por id y la elimina
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();
        // Redirección co mensaxe
        return response()->json(['mensaje' => 'La empresa se ha borrado correctamente'], 200);
    }




    /* FUNCIONS CORRESPONDENTES AS VISTAS*/


    /**
     * Mostrar as empresas
     */
    public function listaxeempresas()
    {
        $empresas = Empresa::all();
        return view('empresas.empresas')->with('empresas',$empresas);
    }

    /**
     * Vista para crear un novo empresa
     */
    public function novaempresa()
    {
        // Levanos a vista para crear unha nova empresa
        return view('empresas.novaempresa');
    }

    /**
     * Funcion para crear un novo empresa
     */
    public function crearnovaempresa(Request $request)
    {
        // Validacións 
        /*$request->validate([
            'nome' => 'required|unique:etiquetas|max:50',
        ]);*/

        // Creamos una nueva empresa con los datos introducidos y la guardamos
        $empresa = new Empresa();
        $empresa->nombre = $request->input('nombre');
        $empresa->email = $request->input('email');
        $empresa->telefono = $request->input('telefono');
        $empresa->direccion = $request->input('direccion');

        $empresa->save();

        // Redirección co mensaxe
        return redirect()-> to('listaxeempresas')->with('success', 'A empresa foi creada correctamente.');
    }


    /**
     * Vista para editar un empresa
     */
    public function modempresa($id)
    {
        //BUscamos la empresa por id
        $empresa = Empresa::find($id); 
        // Devolve a vista con datos
        return view('empresas.modempresa')->with('empresa',$empresa);
    }

    /**
     * Funcion para editar o empresa
     */
    public function editmodempresa(Request $request, $id)
    {
        //Validacións
        /*$request->validate([
            'nome' => 'required|unique:etiquetas|max:50',
        ]);*/

        // Busca la emrpesa por id y la sobreescribe con los datos introducidos
        $empresa = Empresa::find($id);
        $empresa->nombre = $request->input('nombre');
        $empresa->email = $request->input('email');
        $empresa->telefono = $request->input('telefono');
        $empresa->direccion = $request->input('direccion');
        $empresa->save();

        // Redirección co mensaxe
        return redirect()->to('listaxeempresas')->with('success', 'A empresa foi actualizada correctamente.');
    }

    /**
     * Elimina una empresa
     */
    public function eliminarempresa($id)
    {
        //Busca la empresa y la elimina
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();
        // Redirección co mensaxe
        return redirect()->to('listaxeempresas')->with('success', 'A empresa foi eliminada correctamente.');
    }

}
