<?php

/*

Título: Entrega Final

Autor: Miguel Ángel Rama Martínez.

Data modificación: 17/06/2024

Versión 1.0

*/

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    // Fai login con usuario e contrasinal e devolve un JSON co token
    public function loginAPI(Request $request) {

        // Validación de los campos que recibe
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);


        // Tenta facer login coas credenciais enviadas
        if(!Auth::attempt($credentials)) {
            
            //En caso de que non poida facer login devolve 401
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Se fai login, crea un novo token para ese usuario
        $user = $request->user();
        $tokenResult = $user->createToken('API Access Token');
        $token = $tokenResult->plainTextToken;

        // Devolve o token e o tipo de token "Bearer" co código de estado 200
        return response()->json([
            'accessToken' => $token,
            'token_type' => 'Bearer',
            'nombre' => $user->name,
            'email' => $user->email,
            'rol' => $user->rol,
            'id' => $user->id,
        ], 200);
    }


    public function logoutAPI(): JsonResponse
    {

        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);



    }
}
