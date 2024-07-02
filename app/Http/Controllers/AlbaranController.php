<?php

/*

Título: Entrega Final

Autor: Miguel Ángel Rama Martínez.

Data modificación: 17/06/2024

Versión 1.0

*/

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlbaranRequest;
use App\Http\Requests\UpdateAlbaranRequest;
use App\Models\Albaran;
use App\Models\Empresa;
use App\Models\Material;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

class AlbaranController extends Controller
{
    // Devolve un arquivo JSON con todos os recursos co código 200
    public function index(Request $request)
    {
        $tipo = $request->query('tipo'); // Recuperamos el tipo para filtrar por tipo de albarán

        if ($tipo != null) { // Si pasamos parámetro, filtra, senon, mostramos todos
            $albarans = Albaran::where('tipo', '=', $tipo)->get();
        } else {
            $albarans = Albaran::all();
        }

        //Para mostrar los nombres de las empresas, buscamos el nombre de la empresa que coincida con el id, y usamos "first()" para obtener una empresa específica.
        foreach($albarans as $albaran){

            $empresa = Empresa::where('id', $albaran->empresa_id)->first();
            $albaran->empresa_nome = $empresa->nombre;
        }

        //Respuesta de todo correcto
        return response()->json($albarans, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //Método POST para crear nuevo albaran y guardamos con los datos básicos
    {
        try{
            //Validaciones
            $request->validate([
                'numero' => 'required|string|max:20',
                'empresa_id' => 'required',
                'fecha' => 'required',
                'tipo' => 'required',
            ], [
                'numero.required' => 'El campo numero es obligatorio',
                'numero.max' => 'El campo numero no puede tener mas de 20 caracteres',
                'empresa_id.required' => 'Se debe elegir una empresa',
                'fecha.required' => 'El campo fecha es obligatorio',
                'tipo.required' => 'El albarán tiene que ser de tipo entrada o salida',
            ]);

            //Crea un nuevo albarán con los campos introducidos y lo guarda
            $albaran = new Albaran();
            $albaran->numero = $request->input('numero');
            $albaran->fecha = $request->input('fecha');
            $albaran->empresa_id = $request->input('empresa_id');
            $albaran->tipo = $request->input('tipo');
            $albaran->save();

            //Logica para albaranes de entrada
            if($request->input('tipo') === 'entrada') {

                //Datos de los materiales para agregar al albaran
                $materials =  $request->input('materials');
                $nuevo_array_materiales = []; //Se construye un array para el método atach
                foreach ($materials as $material) {
                    $nuevo_array_materiales[$material['id']] = ['cantidad' => $material['cantidad'], 'precio' => $material['precio']];
                }
                // Asociar materiales con cantidades y precios al albarán
                $albaran->materials()->attach($nuevo_array_materiales);
                //Actualizamos las cantidades de los materiales (se suma la cantidad que hemos añadido)
                //Para eso vamos recuperando los materiales de la request y sincronizando la cantidad disponible
                foreach ($materials as $material) {
                    //buscamos el material que hay que actualizar
                    $idMaterial = $material['id'];
                    $materialAct = Material::find($idMaterial);
                    //Sumamos la cantidad añadida a la cantidad disponible del material
                    $cantidad = intval($materialAct->cantidad_disponible) + intval($material['cantidad']);
                    //Actualizamos con la nueva cantidad
                    $materialAct->update(['cantidad_disponible' => $cantidad]);
                }
            }else { //logica para albaranes de salida
                //Datos de los productos para agregar al albaran
                $productos =  $request->input('productos');
                //Nuevo array donde meteremos los datos en formato correcto para añadir la relacion n:m con el metodo attach
                $nuevo_array_productos = [];
                foreach ($productos as $producto) { //Por cada producto de la request
                    $idProducto = $producto['id'];
                    $nuevo_array_productos[$idProducto] = ['cantidad' => $producto['cantidad'], 'precio' => $producto['precio']];
                }
                // Asociar productos con cantidades y precios al Albarán
                $albaran->productos()->attach($nuevo_array_productos);

                //Actualizamos las cantidades de los productos (se resta la cantidad que hemos añadido)
                //Para eso vamos recuperando los productos y sincronizando la cantidad disponible
                foreach ($productos as $producto) {
                    //buscamos el producto que hay que actualizar
                    $idProducto = $producto['id'];
                    $productoAct = Producto::find($idProducto);
                    //Restamos la cantidad que sale a la disponible del producto
                    $cantidad = intval($productoAct->cantidad_disponible) - intval($producto['cantidad']);
                    //Actualizamos con la nueva cantidad
                    $productoAct->update(['cantidad_disponible' => $cantidad]);
                }
            }

            //Para mostrar el nombre de la empresa
            $empresa = Empresa::where('id', $albaran->empresa_id)->first();
            $albaran->empresa_nome = $empresa->nombre;
            
            // Devolvemos el json del nuevo albaran
            return response()->json($albaran, 200);

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
    public function show( $id) //Metodo GET para ver un albaran
    {
        $albaran = Albaran::find($id);
        //Para mostrar los nombres de las empresas en lugar de los ID, buscamos el nombre de la empresa que coincida con el id, y usamos "first()" para obtener una empresa específica.
        $empresa = Empresa::where('id', $albaran->empresa_id)->first();
        $albaran->empresa_nome = $empresa->nombre;
        //Inicializamos precioAlbaran
        $precioAlbaran = 0;

        if ($albaran->tipo === 'entrada') {
            $materiales = $albaran->materials()->withPivot('cantidad', 'precio')->get();
            //Calculamos el precio total del albarán para mostrarlo en el detalle
            foreach ($materiales as $material) { //para cada uno calculamos el precio total y los sumamos material a material
                $precioMaterial = $material->pivot->cantidad * $material->pivot->precio; 
                $precioAlbaran= $precioAlbaran + $precioMaterial;
            }
            // Asignamos los valores
            $albaran->materials = $materiales;
            $albaran->precioTotal = $precioAlbaran;

        } else {
            $productos = $albaran->productos()->withPivot('cantidad', 'precio')->get();
            //Calculamos el precio total del albarán para mostrarlo en el detalle
            foreach ($productos as $producto) { //para cada uno calculamos el precio total y los sumamos producto a producto
                $precioproducto = $producto->pivot->cantidad * $producto->pivot->precio; 
                $precioAlbaran= $precioAlbaran + $precioproducto;
            }
            // Asignamos los valores
            $albaran->productos = $productos;
            $albaran->precioTotal = $precioAlbaran;
        }
        return response()->json($albaran, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) // Metodo PUT para modificar albaran
    {
        try{
            //Validaciones
            $request->validate([
                'numero' => 'required|string|max:20',
                'empresa_id' => 'required',
                'fecha' => 'required',
            ], [
                'numero.required' => 'El campo numero es obligatorio',
                'numero.max' => 'El campo numero no puede tener mas de 20 caracteres',
                'empresa_id.required' => 'Se debe elegir una empresa',
                'fecha.required' => 'El campo fecha es obligatorio',
            ]);

            // Buscamos el albaran a modificar
            $albaran = Albaran::find($id);
            $albaran->numero = $request->input('numero');
            $albaran->fecha = $request->input('fecha');
            $albaran->empresa_id = $request->input('empresa_id');
            //Guardamos el albaran con los datos basicos
            $albaran->save();

            //Logica para albaranes de entrada
            if($request->input('tipo') === 'entrada') {
                //Obtenemos los materiales de la request
                $materials =  $request->input('materials');
                //Recorremos todos los materiales y comprobamos si se ha utilizado en alguna produccion. Si se ha utilizado, el material no se puede modificar
                foreach($materials as $material){

                    //Encontramos el material que se quiere modificar
                    $materialAct = Material::find($material['id']);
                    $productos = $materialAct->productos()->withPivot('cantidad_consumida', 'cantidad_producida')->get();
                    //Si devuelve algun resultado, impedimos la edición
                    if (!$productos->isEmpty()) {
                        //Devolvemos mensaje de error
                        return response()->json(['error' => 'No se puede modificar el material porque tiene productos creados'], 400);
                    } else { //Si no se recuperó ningun producto, se puede modificar la cantidad del material y recalcular sus unidades en el almacen
                        //Recuperamos la relacion de albaran_material para recuperar la cantidad previa del material
                        $relacionMaterial = $albaran->materials()
                            ->wherePivot('material_id', $material['id'])
                            ->withPivot('cantidad', 'precio')
                            ->first();
                        
                        //Recálculo de las unidades disponibles
                        $cantidadTotal = $materialAct->cantidad_disponible - $relacionMaterial->pivot->cantidad + $material['cantidad'];
                        $materialAct->cantidad_disponible = $cantidadTotal;
                        $materialAct->save();

                        //Actualizamos la relacion N:M
                        $albaran->materials()->updateExistingPivot($material['id'], [
                            'cantidad' => $material['cantidad'],
                            'precio' => $material['precio'],
                        ]);
                    }
                }
            } else { //logica para albaranes de salida
                //Obtenemos los productos de la request
                $productosRequest =  $request->input('productos');
                foreach($productosRequest as $producto){
                    //Recuperamos el producto por id para recalcular su cantidad disponible
                    //Recuperamos la relacion de albaran_producto para recuperar la cantidad previa
                    $relacionProducto= $albaran->productos()
                    ->wherePivot('producto_id', $producto['id'])
                    ->withPivot('cantidad', 'precio')
                    ->first();

                    $productoAct = Producto::find($producto['id']);
                    //Recalculamos la cantidad de productos disponibles
                    $cantidadTotal = $productoAct->cantidad_disponible + $relacionProducto->pivot->cantidad - $producto['cantidad'];
                    //Actualizamos la cantidad del producto
                    $productoAct->cantidad_disponible = $cantidadTotal;
                    $productoAct->save();

                    //Actualizamos la relacion
                    $albaran->productos()->updateExistingPivot($producto['id'], [
                    'cantidad' => $producto['cantidad'],
                    'precio' => $producto['precio'],
                    ]);
                }
            }

            //Devolvemos respuesta correcta
            return response()->json($albaran, 200);
        
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
    public function destroy($id) // Método DELETE
    {
        //Recuperamos el albaran que queremos eliminar
        $albaran = Albaran::find($id);
        //Logica para albaranes de entrada
        if($albaran->tipo === 'entrada') {
            //Recuperamos sus materiales
            $materiales = $albaran->materials()->withPivot('cantidad', 'precio')->get();
            //Recorremos todos los materiales y comprobamos si se ha utilizado en alguna produccion. Si se ha utilizado, el albaran no se puede eliminar
            foreach($materiales as $material){
                //Recuperamos los productos relacionados con el material
                $materialAct = Material::find($material->pivot->material_id);
                $productos = $materialAct->productos()->withPivot('cantidad_consumida', 'cantidad_producida')->get();
                //Si devuelve algun resultado, impedimos la eliminacion
                if (!$productos->isEmpty()) {
                        // Redirección co mensaxe de error
                        return response()->json(['error' => 'No se puede eliminar el albarán porque alguno de sus productos ya ha sido consumido'], 400);
                } else {
                    //No se recuperó ningun producto, se puede recalcular las unidades del material
                    $cantidadTotal = $materialAct->cantidad_disponible - $material->pivot->cantidad;
                    $materialAct->cantidad_disponible = $cantidadTotal;
                    $materialAct->save();
                }
            }
        } else { //Logica para albaranes de salida
                //Recuperamos sus productos
                $productos = $albaran->productos()->withPivot('cantidad', 'precio')->get();
                //Recorremos todos los productos para recalcular las cantidades
                foreach($productos as $producto){
                        //Recuperamos los productos relacionados con el material
                        $productoAct = Producto::find($producto->pivot->producto_id);
                        //Recalculamos las unidades del producto en el almacen
                        $cantidadTotal = $productoAct->cantidad_disponible + $producto->pivot->cantidad;
                        $productoAct->cantidad_disponible = $cantidadTotal;
                        $productoAct->save();
                }
        }
        //Finalmente eliminamos el albaran
        $albaran->delete();
        // Redirección co mensaxe
        return response()->json(['mensaje' => 'El albarán se borró correctamente'], 200);
    }


/* FUNCIONS CORRESPONDENTES AS VISTAS*/


    /**
     * Mostrar os albarans
     */
    public function listaxealbarans(Request $request)
    {
        // Llamar al controlador y ejecutar la ruta internamente
        $tipo = $request->query('tipo'); // En caso de que pasemos un parámetro o gardamos nuna variable

        if ($tipo != null) { // Si pasamos parámetro, filtra, senon, mostramos todos
            $albarans = Albaran::where('tipo', '=', $tipo)->get();
        } else {
            $albarans = Albaran::all();
        }

        //Para mostrar los nombres de las empresas en lugar de los ID, buscamos el nombre de la empresa que coincida con el id, y usamos "first()" para obtener una empresa específica.
        foreach($albarans as $albaran){

            $empresa = Empresa::where('id', $albaran->empresa_id)->first();
            $albaran->empresa_nome = $empresa->nombre;
        }
        // Redirección co mensaxe
        return view('albaranes.albarans', [
            'albarans' => $albarans,
            'tipo' => $tipo,
            'mensajeSuccess' => false,
            'mensajeEditarSuccess' => false,
            'mensajeEliminarSuccess' => false,
            'mensajeEliminarError' => false
        ]);
        
    }

    /**
     * Vista para crear un novo albaran
     */
    public function novoalbaran()
    {
        // Levanos a vista para crear un novo albaran
        // Necesitamos el listado de las empresas para que nos las muestre al crear un nuevo albaran

        $empresas = Empresa::all();

        //Recuperamos el listado de los materiales

        $materiales = Material::all();

        //Devolvemos la vista con datos
        return view('albaranes.novoalbaran', [
            'empresas' => $empresas,
            'materials' => $materiales
        ]);
    }

    /**
     * Funcion para crear un novo albaran
     */
    public function crearnovoalbaran(Request $request)
    {
        // Validacións 
        $request->validate([
            'numero' => 'required|unique:albarans|max:10',
            'fecha' => 'required',
            'materiales_seleccionados' => 'required|array', // Para controlar que al menos se elige un material
        ]);

        //Creamos un nuevo albarán y lo guardamos con los datos básicos
        $albaran = new Albaran();
        $albaran->numero = $request->input('numero');
        $albaran->fecha = $request->input('fecha');
        $albaran->empresa_id = $request->input('empresa_id');
        $albaran->tipo = "entrada";


        //Datos de los materiales para agregar al albaran
        $materials =  $request->input('materials');
        //Listado de ids de los materiales que se han seleccionado
        $materiales_seleccionados =  $request->input('materiales_seleccionados');
        //Nuevo array donde meteremos los datos en formato correcto para añadir la relacion n:m con el metodo attach
        $nuevo_array_materiales = [];

        foreach ($materials as $material) {
            //recorremos el array de materiales y comprobamos si su id se seleccionó o no
            if (in_array($material['id'], $materiales_seleccionados)) {
                // Si el ID está presente en materiales_seleccionados, agregar al nuevo array
                $idMaterial = $material['id'];
                //Comprobamos para los campos de ese material en el formulario que se ha especificado cantidad y precio
                if ($material['cantidad'] == null || $material['precio'] == null) {
                    return redirect()->back()->withErrors(['materials' => 'Debe especificar cantidad y precio']);
                } else {
                    $nuevo_array_materiales[$idMaterial] = ['cantidad' => $material['cantidad'], 'precio' => $material['precio']];
                }
            }
        }

        //Guardamos el albaran
        $albaran->save();
        // Asociar materiales con cantidades y precios al albaran
        $albaran->materials()->attach($nuevo_array_materiales);


        //Actualizamos las cantidades de los materiales (se suma la cantidad que hemos añadido)
        //Para eso vamos recuperando los materiales y sincronizando la cantidad disponible
        foreach ($materials as $material) {
            //recorremos el array de materiales y comprobamos si su id se seleccionó o no
            if (in_array($material['id'], $materiales_seleccionados)) {
                //buscamos el material que hay que actualizar
                $idMaterial = $material['id'];
                $materialAct = Material::find($idMaterial);
                //Sumamos la cantidad añadida a la cantidad disponible del material
                $cantidad = intval($materialAct->cantidad_disponible) + intval($material['cantidad']);
                //Actualizamos con la nueva cantidad
                $materialAct->update(['cantidad_disponible' => $cantidad]);
            }
        }
        //Recuperamos nuevamente los albaranes de entrada
        $albarans = Albaran::where('tipo', '=', 'entrada')->get();
        //Para mostrar los nombres de las empresas en lugar de los ID, buscamos el nombre de la empresa que coincida con el id, y usamos "first()" para obtener una empresa específica.
        foreach($albarans as $albaran){
            $empresa = Empresa::where('id', $albaran->empresa_id)->first();
            $albaran->empresa_nome = $empresa->nombre;
        }
        // Redirección co mensaxe
        return view('albaranes.albarans', [
            'albarans' => $albarans,
            'tipo' => 'entrada',
            'mensajeSuccess' => true,
            'mensajeEditarSuccess' => false,
            'mensajeEliminarSuccess' => false,
            'mensajeEliminarError' => false,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function veralbaran($id)
    {
        // Para ver un albaran individualmente y recuperamos los datos con las tablas pivot, ya sea de entrada o salida
        $albaran = Albaran::find($id);
        $materiales = $albaran->materials()->withPivot('cantidad', 'precio')->get();
        $productos = $albaran->productos()->withPivot('cantidad', 'precio')->get();
        $empresa = Empresa::where('id', $albaran->empresa_id)->first();
        $albaran->empresa_nome = $empresa->nombre;

        //Calculamos el precio total del albarán para mostrarlo en el detalle

        $precioAlbaran = 0;

        // Si es de entrada
        foreach ($materiales as $material) {
            $precioMaterial = $material->pivot->cantidad * $material->pivot->precio; 
            $precioAlbaran= $precioAlbaran + $precioMaterial;
        }

        // Si es de salida
        foreach ($productos as $producto) {
            $precioproducto = $producto->pivot->cantidad * $producto->pivot->precio; 
            $precioAlbaran= $precioAlbaran + $precioproducto;

        }

        //Devolvemos la vista con datos
        return view('albaranes.veralbaran', [
            'albaran' => $albaran,
            'materials' => $materiales,
            'tipo' => $albaran->tipo,
            'productos' => $productos,
            'precio' => $precioAlbaran
        ]);
    }

    /**
     * Vista para editar un albaran
     */
    public function modalbaran($id)
    {
        
        // Para ver un albaran de entrada individualmente
        $albaran = Albaran::find($id);
        $materiales = $albaran->materials()->withPivot('cantidad', 'precio')->get();
        $empresa = Empresa::where('id', $albaran->empresa_id)->first();
        $albaran->empresa_nome = $empresa->nombre;
        $empresas = Empresa::all();

        //Devolvemos la vista con datos
        return view('albaranes.modalbaran', [
            'albaran' => $albaran,
            'materials' => $materiales,
            'empresas' => $empresas,
            'mensajeError' => false
        ]);
    }

    /**
     * Funcion para editar o albaran
     */
    public function editmodalbaran(Request $request, $id)
    {
        // Validacións 
        $request->validate([
            'numero' => 'required|max:10',
            'fecha' => 'required',
        ]);

        //Recuperamos el albaran para ver los valores anteriores a la modificacion (no el tipo, este no se puede cambiar)
        $albaran = Albaran::find($id);
        $albaran->numero = $request->input('numero');
        $albaran->fecha = $request->input('fecha');
        $albaran->empresa_id = $request->input('empresa_id');
        //Guardamos el albaran con los datos basicos
        $albaran->save();

        //Recuperamos sus materiales
        $materiales = $albaran->materials()->withPivot('cantidad', 'precio')->get();
        //Obtenemos los materiales del formulario
        $materials =  $request->input('materials');
        //Recorremos todos los materiales y comprobamos si se ha utilizado en alguna produccion. Si se ha utilizado, el material no se puede modificar
        foreach($materiales as $material){
             //Comprobamos el valor antiguo y el valor nuevo del campo cantidad para saber si dicho material se quiere modificar
            if ($material->pivot->cantidad != $materials[$material->pivot->material_id]['cantidad']) {
                //Volvemos a recuperar los datos de las empresas para rellenar la vista
                $empresa = Empresa::where('id', $albaran->empresa_id)->first();
                $albaran->empresa_nome = $empresa->nombre;
                $empresas = Empresa::all();
                //Recuperamos los productos relacionados con el material
                $materialAct = Material::find($material->pivot->material_id);
                $productos = $materialAct->productos()->withPivot('cantidad_consumida', 'cantidad_producida')->get();
                //Si devuelve algun resultado, impedimos la edición
                if (!$productos->isEmpty()) {
                    //Devolvemos mensaje de error
                    return view('albaranes.modalbaran', [
                        'albaran' => $albaran,
                        'materials' => $materiales,
                        'empresas' => $empresas,
                        'mensajeError' => true,
                        'infoError' => $materialAct->metal.'|'.$materialAct->dimensiones
                    ]);

                } else {
                    //No se recuperó ningun producto, se puede modificar la cantidad del material y recalcular sus unidades en el almacen
                    //Comprobamos para los campos de ese material en el formulario que se ha especificado cantidad y precio
                    if ($materials[$material->pivot->material_id]['cantidad'] == null || $materials[$material->pivot->material_id]['precio'] == null) {
                        return redirect()->back()->withErrors(['materials' => 'Debe especificar cantidad y precio']);
                    } else {
                        $cantidadTotal = $materialAct->cantidad_disponible - $material->pivot->cantidad + $materials[$material->pivot->material_id]['cantidad'];
                        $materialAct->cantidad_disponible = $cantidadTotal;
                        $materialAct->save();
    
                        //Actualizamos la relacion
                        $tablaPivot = $material->pivot;
                        $tablaPivot->cantidad = $materials[$material->pivot->material_id]['cantidad'];
                        $tablaPivot->precio = $materials[$material->pivot->material_id]['precio'];
                        $tablaPivot->save();
                    }

                }
            }

        }

        //Recuperamos nuevamente los albaranes de entrada
        $albarans = Albaran::where('tipo', '=', 'entrada')->get();
        //Para mostrar los nombres de las empresas en lugar de los ID, buscamos el nombre de la empresa que coincida con el id, y usamos "first()" para obtener una empresa específica.
        foreach($albarans as $albaran){
            $empresa = Empresa::where('id', $albaran->empresa_id)->first();
            $albaran->empresa_nome = $empresa->nombre;
        }
        // Redirección co mensaxe
        return view('albaranes.albarans', [
            'albarans' => $albarans,
            'tipo' => 'entrada',
            'mensajeSuccess' => false,
            'mensajeEditarSuccess' => true,
            'mensajeEliminarSuccess' => false,
            'mensajeEliminarError' => false
        ]);

    }

    /**
     * Elimina un albaran
     */
    public function eliminaralbaran($id)
    {
        //Recuperamos el albaran
        $albaran = Albaran::find($id);
        //Recuperamos sus materiales
        $materiales = $albaran->materials()->withPivot('cantidad', 'precio')->get();
        //Recorremos todos los materiales y comprobamos si se ha utilizado en alguna produccion. Si se ha utilizado, el albaran no se puede eliminar
        foreach($materiales as $material){
                //Recuperamos los productos relacionados con el material
                $materialAct = Material::find($material->pivot->material_id);
                $productos = $materialAct->productos()->withPivot('cantidad_consumida', 'cantidad_producida')->get();
                //Si devuelve algun resultado, impedimos la eliminacion
                if (!$productos->isEmpty()) {
                        //Recuperamos nuevamente los albaranes de entrada
                        $albarans = Albaran::where('tipo', '=', 'entrada')->get();
                        //Para mostrar los nombres de las empresas en lugar de los ID, buscamos el nombre de la empresa que coincida con el id, y usamos "first()" para obtener una empresa específica.
                        foreach($albarans as $albaran){
                            $empresa = Empresa::where('id', $albaran->empresa_id)->first();
                            $albaran->empresa_nome = $empresa->nombre;
                        }
                        // Redirección co mensaxe de error
                        return view('albaranes.albarans', [
                            'albarans' => $albarans,
                            'tipo' => 'entrada',
                            'mensajeSuccess' => false,
                            'mensajeEditarSuccess' => false,
                            'mensajeEliminarSuccess' => false,
                            'mensajeEliminarError' => true
                        ]);

                } else {
                    //No se recuperó ningun producto, se puede recalcular sus unidades en el almacen
                    $cantidadTotal = $materialAct->cantidad_disponible - $material->pivot->cantidad;
                    $materialAct->cantidad_disponible = $cantidadTotal;
                    $materialAct->save();
                }

        }

        //Finalmente eliminamos el albaran
        $albaran->delete();
        //Recuperamos nuevamente los albaranes de entrada
        $albarans = Albaran::where('tipo', '=', 'entrada')->get();
        //Para mostrar los nombres de las empresas en lugar de los ID, buscamos el nombre de la empresa que coincida con el id, y usamos "first()" para obtener una empresa específica.
        foreach($albarans as $albaran){
            $empresa = Empresa::where('id', $albaran->empresa_id)->first();
            $albaran->empresa_nome = $empresa->nombre;
        }
        // Redirección co mensaxe
        return view('albaranes.albarans', [
            'albarans' => $albarans,
            'tipo' => 'entrada',
            'mensajeSuccess' => false,
            'mensajeEditarSuccess' => false,
            'mensajeEliminarSuccess' => true,
            'mensajeEliminarError' => false
        ]);
    }

        /**
     * Elimina un albaran de salida
     */
    public function eliminaralbaransalida($id)
    {
        //Recuperamos el albaran para ver los valores anteriores a la modificacion
        $albaran = Albaran::find($id);
        //Recuperamos sus productos
        $productos = $albaran->productos()->withPivot('cantidad', 'precio')->get();
        //Recorremos todos los productos para recalcular las cantidades
        foreach($productos as $producto){
            //Recuperamos los productos relacionados con el producto
            $productoAct = Producto::find($producto->pivot->producto_id);
            //Recalculamos las unidades del producto en el almacen
            $cantidadTotal = $productoAct->cantidad_disponible + $producto->pivot->cantidad;
            $productoAct->cantidad_disponible = $cantidadTotal;
            $productoAct->save();
        }
        //Finalmente eliminamos el albaran
        $albaran->delete();
        //Recuperamos nuevamente los albaranes de salida
        $albarans = Albaran::where('tipo', '=', 'salida')->get();
        //Para mostrar los nombres de las empresas en lugar de los ID, buscamos el nombre de la empresa que coincida con el id, y usamos "first()" para obtener una empresa específica.
        foreach($albarans as $albaran){
            $empresa = Empresa::where('id', $albaran->empresa_id)->first();
            $albaran->empresa_nome = $empresa->nombre;
        }
        // Redirección co mensaxe
        return view('albaranes.albarans', [
            'albarans' => $albarans,
            'tipo' => 'salida',
            'mensajeSuccess' => false,
            'mensajeEditarSuccess' => false,
            'mensajeEliminarSuccess' => true,
            'mensajeEliminarError' => false
        ]);
    }


    /**
     * Vista para crear un novo albaran
     */
    public function novoalbaransalida()
    {
        // Levanos a vista para crear un novo albaran
        // Necesitamos el listado de las empresas para que nos las muestre al crear un nuevo albaran

        $empresas = Empresa::all();

        //Recuperamos el listado de los productos

        $productos = Producto::all();

        //Devolvemos la vista
        return view('albaranes.novoalbaransalida', [
            'empresas' => $empresas,
            'productos' => $productos
        ]);
    }

    /**
     * Funcion para crear un novo albaran
     */
    public function crearnovoalbaransalida(Request $request)
    {
        // Validacións 
        $request->validate([
            'numero' => 'required|unique:albarans|max:10',
            'fecha' => 'required',
            'productos_seleccionados' => 'required|array', // Para controlar que al menos se elige un producto
        ]);

        //Creamos nuevo albaran con datos basicos
        $albaran = new Albaran();
        $albaran->numero = $request->input('numero');
        $albaran->fecha = $request->input('fecha');
        $albaran->empresa_id = $request->input('empresa_id');
        $albaran->tipo = "salida";
        //Datos de los productos para agregar al albaran
        $productos =  $request->input('productos');
        //Listado de ids de los productos que se han seleccionado
        $productos_seleccionados =  $request->input('productos_seleccionados');
        //Nuevo array donde meteremos los datos en formato correcto para añadir la relacion n:m con el metodo attach
        $nuevo_array_productos = [];

        foreach ($productos as $producto) {
            //recorremos el array de productos y comprobamos si su id se seleccionó o no
            if (in_array($producto['id'], $productos_seleccionados)) {
                // Si el ID está presente en productos_seleccionados, agregar al nuevo array
                $idProducto = $producto['id'];
                //Comprobamos para los campos de ese producto en el formulario que se ha especificado cantidad y precio
                if ($producto['cantidad'] == null || $producto['precio'] == null) {
                    return redirect()->back()->withErrors(['productos' => 'Debe especificar cantidad y precio']);
                } else {
                    $nuevo_array_productos[$idProducto] = ['cantidad' => $producto['cantidad'], 'precio' => $producto['precio']];
                }
            }
        }
        //Guardamos el albaran
        $albaran->save();
        // Asociar productos con cantidades y precios al albaran
        $albaran->productos()->attach($nuevo_array_productos);

        //Actualizamos las cantidades de los productos (se resta la cantidad que hemos añadido)
        //Para eso vamos recuperando los productos y sincronizando la cantidad disponible
        foreach ($productos as $producto) {
            //recorremos el array de productos y comprobamos si su id se seleccionó o no
            if (in_array($producto['id'], $productos_seleccionados)) {
                //buscamos el producto que hay que actualizar
                $idProducto = $producto['id'];
                $productoAct = Producto::find($idProducto);
                //Restamos la cantidad que sale a la disponible del producto
                $cantidad = intval($productoAct->cantidad_disponible) - intval($producto['cantidad']);
                //Actualizamos con la nueva cantidad
                $productoAct->update(['cantidad_disponible' => $cantidad]);
            }
        }


        //Recuperamos nuevamente los albaranes de salida
        $albarans = Albaran::where('tipo', '=', 'salida')->get();
        //Para mostrar los nombres de las empresas en lugar de los ID, buscamos el nombre de la empresa que coincida con el id, y usamos "first()" para obtener una empresa específica.
        foreach($albarans as $albaran){
            $empresa = Empresa::where('id', $albaran->empresa_id)->first();
            $albaran->empresa_nome = $empresa->nombre;
        }
        // Redirección co mensaxe
        return view('albaranes.albarans', [
            'albarans' => $albarans,
            'tipo' => 'salida',
            'mensajeSuccess' => true,
            'mensajeEditarSuccess' => false,
            'mensajeEliminarSuccess' => false,
            'mensajeEliminarError' => false
        ]);
    }


    /**
     * Vista para editar un albaran
     */
    public function modalbaransalida($id)
    {
        // Para ver un albaran individualmente recuperando los datos con la tabla pivot
        $albaran = Albaran::find($id);
        $productos = $albaran->productos()->withPivot('cantidad', 'precio')->get();
        $empresa = Empresa::where('id', $albaran->empresa_id)->first();
        $albaran->empresa_nome = $empresa->nombre;
        $empresas = Empresa::all();

        //Devolvemos la vista
        return view('albaranes.modalbaransalida', [
            'albaran' => $albaran,
            'productos' => $productos,
            'empresas' => $empresas,
            'mensajeError' => false
        ]);
    }

    /**
     * Funcion para editar o albaran
     */
    public function editmodalbaransalida(Request $request, $id)
    {
        // Validacións 
        $request->validate([
            'numero' => 'required|max:10',
            'fecha' => 'required',
        ]);

        //Recuperamos el albaran para ver los valores anteriores a la modificacion
        $albaran = Albaran::find($id);
        $albaran->numero = $request->input('numero');
        $albaran->fecha = $request->input('fecha');
        $albaran->empresa_id = $request->input('empresa_id');
        //Guardamos el albaran con los datos basicos
        $albaran->save();

        //Recuperamos sus productos con precio y cantidad de la tabla pivot
        $productos = $albaran->productos()->withPivot('cantidad', 'precio')->get();
        //Obtenemos los productos del formulario
        $productosFormulario =  $request->input('productos');
        //Recorremos todos los productos
        foreach($productos as $producto){
            //Comprobamos para los campos de ese producto en el formulario que se ha especificado cantidad y precio
            if ($productosFormulario[$producto->pivot->producto_id]['cantidad'] == null || $productosFormulario[$producto->pivot->producto_id]['precio'] == null) {
                return redirect()->back()->withErrors(['productos' => 'Debe especificar cantidad y precio']);
            } else {
                //Comprobamos el valor antiguo y el valor nuevo del campo cantidad para saber si dicho producto se quiere modificar
                if ($producto->pivot->cantidad != $productosFormulario[$producto->pivot->producto_id]['cantidad']) {
                    //Recuperamos el producto por id para recalcular su cantidad disponible
                    $productoAct = Producto::find($producto->pivot->producto_id);
                    $cantidadTotal = $productoAct->cantidad_disponible + $producto->pivot->cantidad - $productosFormulario[$producto->pivot->producto_id]['cantidad'];
                    
                    //Actualizamos la cantidad del producto
                    $productoAct->cantidad_disponible = $cantidadTotal;
                    $productoAct->save();

                    //Actualizamos la relacion
                    $tablaPivot = $producto->pivot;
                    $tablaPivot->cantidad =  $productosFormulario[$producto->pivot->producto_id]['cantidad'];
                    $tablaPivot->precio =  $productosFormulario[$producto->pivot->producto_id]['precio'];
                    $tablaPivot->save();
                    
                }
            }
        }
        //Recuperamos nuevamente los albaranes de salida
        $albarans = Albaran::where('tipo', '=', 'salida')->get();
        //Para mostrar los nombres de las empresas en lugar de los ID, buscamos el nombre de la empresa que coincida con el id, y usamos "first()" para obtener una empresa específica.
        foreach($albarans as $albaran){
            $empresa = Empresa::where('id', $albaran->empresa_id)->first();
            $albaran->empresa_nome = $empresa->nombre;
        }
        // Redirección co mensaxe
        return view('albaranes.albarans', [
            'albarans' => $albarans,
            'tipo' => 'salida',
            'mensajeSuccess' => false,
            'mensajeEditarSuccess' => true,
            'mensajeEliminarSuccess' => false,
            'mensajeEliminarError' => false

        ]);

    }

}
