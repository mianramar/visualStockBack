<?php

/*

Título: Entrega Final

Autor: Miguel Ángel Rama Martínez.

Data modificación: 17/06/2024

Versión 1.0

*/

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\Perfil;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() //Método GET
    {
        $usuarios = User::all();
        return response()->json($usuarios, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //Método POST
    {
        try{
            //Validaciones
            $request->validate([
                'name' => 'required|string|max:20',
                'email' => 'required|email|max:30',
                'rol' => 'required|string',
                'password' => 'required|string',
            ], [
                'name.required' => 'El campo name es obligatorio',
                'name.max' => 'El campo name no puede tener mas de 20 caracteres',
                'email.required' => 'El campo email es obligatorio',
                'email.email' => 'El campo email debe ser una dirección de correo válida',
                'rol.required' => 'El campo rol es obligatorio',
                'password.required' => 'El campo password es obligatorio',
            ]);
            // Creamos nuevo usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'rol' => $request->rol,
                'password' => Hash::make($request->password),
            ]);
            event(new Registered($user));

            //Y nuevo perfil
            $perfil = new Perfil();
            $perfil->nombre = $request->input('nombre');
            $perfil->apellido = $request->input('apellido');
            //Imagen por defecto
            $perfil->imagen = 'imaxes/foto1.PNG';

            //Asignamos el perfil al usuario
            $user->perfil()->save($perfil);

            // Redirección co mensaxe
            return response()->json($user, 200);
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
    public function show($id) //Método GET Individual
    {
        $usuario = User::find($id); 
        // Busca el perfil asociado al user_id
        $perfil = Perfil::where('user_id', $usuario->id)->first();
        $usuario->perfil = $perfil;
        // Devolve a vista con datos
        return response()->json($usuario, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) //Método PUT
    {
        try{
            //Validaciones
            $request->validate([
                'name' => 'required|string|max:20',
                'email' => 'required|email|max:30',
                'rol' => 'required|string',
                'nombre' => 'required|string',
                'apellido' => 'required|string',
            ], [
                'name.required' => 'El campo name es obligatorio',
                'name.max' => 'El campo name no puede tener mas de 20 caracteres',
                'email.required' => 'El campo email es obligatorio',
                'email.email' => 'El campo email debe ser una dirección de correo válida',
                'rol.required' => 'El campo rol es obligatorio',
                'nombre.required' => 'El campo nombre es obligatorio',
                'apellido.required' => 'El campo apellido es obligatorio',

            ]);
            //Buscamos usuario y perfil y modificamos los campos con inputs
            $usuario = User::find($id);
            $usuario->name = $request->input('name');
            $usuario->email = $request->input('email');
            $usuario->rol = $request->input('rol');
            $usuario->save();

            $perfil = Perfil::where('user_id', $id)->first();
            $perfil->nombre = $request->input('nombre');
            $perfil->apellido = $request->input('apellido');
            $perfil->save();
            // Devolve a vista con datos
            return response()->json($usuario, 200);
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
        //Busca y elimina el usuario y el perfil al estar en cascade
        $usuario = User::findOrFail($id);
        $usuario->delete();
        // Redirección co mensaxe
        return response()->json(['mensaje' => 'El usuario se ha borrado correctamente'], 200);
    }

      /* FUNCIONS CORRESPONDENTES AS VISTAS*/


    /**
     * Mostrar os usuarios
     */
    public function listaxeusuarios()
    {
        //Muestra los usuario
        $usuarios = User::all();
        return view('usuarios.usuarios')->with('usuarios',$usuarios);
    }

    /**
     * Vista para crear un novo usuario
     */
    public function novousuario()
    {
        // Levanos a vista para crear un novo usuario
        return view('usuarios.novousuario');
    }

    /**
     * Funcion para crear un novo usuario
     */
    public function crearnovousuario(Request $request)
    {
        //Validaciones
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        //Creamos un nuevo Usuario y Perfil
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'rol' => $request->rol,
            'password' => Hash::make($request->password),
        ]);
        event(new Registered($user));

        $perfil = new Perfil();
        $perfil->nombre = $request->input('nombre');
        $perfil->apellido = $request->input('apellido');
        //Imagen por defecto
        $perfil->imagen = 'imaxes/foto1.PNG';

        //Asignamos el perfil al usuario
        $user->perfil()->save($perfil);

        // Redirección co mensaxe
        return redirect()-> to('listaxeusuarios')->with('success', 'O usuario foi creado correctamente.');
    }


    /**
     * Vista para editar un usuario
     */
    public function modusuario($id)
    {
        //Busca el usuario
        $usuario = User::find($id); 

        // Busca el perfil asociado al user_id
        $perfil = Perfil::where('user_id', $usuario->id)->first();
        // Devolve a vista con datos
        return view('usuarios.modusuario', [
            'usuario' => $usuario,
            'perfil' => $perfil,
        ]);
    }

    /**
     * Funcion para editar o usuario
     */
    public function editmodusuario(Request $request, $id)
    {
        //Validacións
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
        ]);

        // Busca el usuario y guarda los datos de usuario
        $usuario = User::find($id);
        $usuario->name = $request->input('name');
        $usuario->email = $request->input('email');
        $usuario->rol = $request->input('rol');
        $usuario->save();

        // Y los del perfil
        $perfil = Perfil::where('user_id', $id)->first();
        $perfil->nombre = $request->input('nombre');
        $perfil->apellido = $request->input('apellido');
        $perfil->save();

        // Redirección co mensaxe
        return redirect()->to('listaxeusuarios')->with('success', 'O usuario foi actualizada correctamente.');
    }

    /**
     * Elimina un material
     */
    public function eliminarusuario($id)
    {
        //Busca y elimina un usuario y perfil (cascade)
        $usuario = User::findOrFail($id);
        $usuario->delete();
        // Redirección co mensaxe
        return redirect()->to('listaxeusuarios')->with('success', 'O usuario foi eliminada correctamente.');
    }
}
