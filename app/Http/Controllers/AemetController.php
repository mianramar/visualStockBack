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
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AemetController extends Controller{

    public function aemet() //Método GET
    {
        try {
            //URL a la que vamos a hacer la request
            $apiUrl = 'https://opendata.aemet.es/opendata/api/prediccion/nacional/manana';
            //Incorporamos el APiKey en la cabecera
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'api_key' => 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJsYW1hczY3NkBob3RtYWlsLmNvbSIsImp0aSI6Ijg1NDIwY2ZkLTU3NTUtNDdmNC1iZGE5LWMyNDlkZjAxYTkyZiIsImlzcyI6IkFFTUVUIiwiaWF0IjoxNzEwMzE1MjU1LCJ1c2VySWQiOiI4NTQyMGNmZC01NzU1LTQ3ZjQtYmRhOS1jMjQ5ZGYwMWE5MmYiLCJyb2xlIjoiIn0.GkhmJ9Geck2gbrqAOpkVfUmz-M5b2Yob1GY3PQvr4uU'
            ])->get($apiUrl);
            
            //Guardamos la respuesta en una variable
            $prediccion = $response->json();
            //Obtenemos el contenido que está en "datos" de la predicción porque nos devuelve una URL que tenemos que consultar
            if($prediccion != null && $prediccion['datos'] != null) {
                // Intentamos obtener el contenido
                $contenido = file_get_contents($prediccion['datos']);
                // Codificamos para ver bien los acentos
                $contenido = mb_convert_encoding($contenido, 'UTF-8', 'ISO-8859-1');
            } else {
                $contenido = 'No se han podido obtener datos de la AEMET';
            }
        } catch (Exception $e) {
            // Capturamos cualquier excepción que pueda ocurrir
            $contenido = 'Error al obtener o procesar los datos: ' . $e->getMessage();
        }

        //Devolvemos la vista con el contenido de la predicción
        return response()->json($contenido, 200);
    }
    /**
     * Display a listing of the resource.
     */
    public function verprediccion() //Funcion para llamar al metodo de la api externa
    {

        try {
            //URL a la que vamos a hacer la request
            $apiUrl = 'https://opendata.aemet.es/opendata/api/prediccion/nacional/manana';
            //Incorporamos el APiKey en la cabecera
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'api_key' => 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJsYW1hczY3NkBob3RtYWlsLmNvbSIsImp0aSI6Ijg1NDIwY2ZkLTU3NTUtNDdmNC1iZGE5LWMyNDlkZjAxYTkyZiIsImlzcyI6IkFFTUVUIiwiaWF0IjoxNzEwMzE1MjU1LCJ1c2VySWQiOiI4NTQyMGNmZC01NzU1LTQ3ZjQtYmRhOS1jMjQ5ZGYwMWE5MmYiLCJyb2xlIjoiIn0.GkhmJ9Geck2gbrqAOpkVfUmz-M5b2Yob1GY3PQvr4uU'
            ])->get($apiUrl);
            
            //Guardamos la respuesta en una variable
            $prediccion = $response->json();
            //Obtenemos el contenido que está en "datos" de la predicción porque nos devuelve una URL que tenemos que consultar
            if($prediccion != null && $prediccion['datos'] != null) {
                // Intentamos obtener el contenido
                $contenido = file_get_contents($prediccion['datos']);
                // Codificamos para ver bien los acentos
                $contenido = mb_convert_encoding($contenido, 'UTF-8', 'ISO-8859-1');
            } else {
                $contenido = 'No se han podido obtener datos de la AEMET';
            }
        } catch (Exception $e) {
            // Capturamos cualquier excepción que pueda ocurrir
            $contenido = 'Error al obtener o procesar los datos: ' . $e->getMessage();
        }

        //Devolvemos la vista con el contenido de la predicción
        return view('aemet.prediccion', ['prediccion' => $contenido]);
    }

}