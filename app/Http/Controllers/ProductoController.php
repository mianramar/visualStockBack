<?php

/*

Título: Entrega Final

Autor: Miguel Ángel Rama Martínez.

Data modificación: 17/06/2024

Versión 1.0

*/

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Albaran;
use App\Models\Intermedio;
use App\Models\Material;
use App\Models\Producto;
use App\Models\Terminado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() //Método GET
    {
        //Listamos todos los productos
        $productos = Producto::all();
        return response()->json($productos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //Método POST
    {
        try{
            //Validaciones
            $request->validate([
                'nombre' => 'required|string|max:50',
                'tipo' => 'required',
                'cantidad_producida' => 'required|integer|min:1',
            ], [
                'nombre.required' => 'El campo nombre es obligatorio',
                'nombre.max' => 'El campo nombre no puede tener mas de 50 caracteres',
                'tipo.required' => 'El campo tipo es obligatorio',
                'cantidad_producida.required' => 'Se debe producir alguna cantidad de producto',
                'cantidad_producida.min' => 'La cantidad producida debe ser mayor o igual a 1',
            ]);
            //Crea nuevo producto con los datos de los inputs y lo guarda
            $producto = new Producto();
            $producto->nombre = $request->input('nombre');
            $producto->tipo = $request->input('tipo');
            $producto->cantidad_disponible = $request->input('cantidad_producida');
            $producto->save();

            $tipo = $request->input('tipo');
            // Si es de tipo intermedio
            if ($tipo === "intermedio") {
                $request->validate([
                    'tratamiento' => 'required|string|max:50',
                ], [
                    'tratamiento.required' => 'El campo tratamiento es obligatorio',
                    'tratamiento.max' => 'El campo tratamiento no puede tener mas de 50 caracteres',
                ]);
                $intermedio = new Intermedio();
                $intermedio->tratamiento = $request->input('tratamiento');
                //Asociamos el producto intermedio al producto recien creado
                $intermedio->producto()->associate($producto);
                // Guardar el Intermedio en la base de datos
                $intermedio->save();
            } else { //Si no
                $request->validate([
                    'garantia' => 'required|string|max:50',
                ], [
                    'garantia.required' => 'El campo garantia es obligatorio',
                    'garantia.max' => 'El campo garantia no puede tener mas de 50 caracteres',
                ]);
                $terminado = new Terminado();
                $terminado->garantia = $request->input('garantia');
                //Asociamos el producto terminado al producto recien creado
                $terminado->producto()->associate($producto);
                // Guardar el Terminado en la base de datos
                $terminado->save();
            }

            //Datos de los materiales a consumir
            $materials = $request->input('materials');
            //Nuevo array donde meteremos los datos en formato correcto para añadir la relacion n:m con el metodo attach
            $nuevo_array_materiales = [];

            foreach ($materials as $material) {
                    // agregar al nuevo array
                    $idMaterial = $material['id'];
                    $nuevo_array_materiales[$idMaterial] = ['cantidad_consumida' => $material['cantidad_consumida'], 'cantidad_producida' => $request->input('cantidad_producida')];
            }
            // Asociar materiales con cantidades consumidas al Producto
            $producto->materiales()->attach($nuevo_array_materiales);

            //Actualizamos las cantidades de los materiales (se resta la cantidad que hemos consumido)
            //Para eso vamos recuperando los materiales y sincronizando la cantidad disponible
            foreach ($materials as $material) {
                    //buscamos el material que hay que actualizar
                    $idMaterial = $material['id'];
                    $materialAct = Material::find($idMaterial);
                    //Restamos la cantidad consumida a la disponible del material
                    $cantidad = intval($materialAct->cantidad_disponible) - intval($material['cantidad_consumida']);
                    //Actualizamos con la nueva cantidad
                    $materialAct->update(['cantidad_disponible' => $cantidad]);
            }

            //El producto se ha creado y lo devolvemos en la respuesta
            return response()->json($producto, 200);

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
    public function show($id) //Método GET Individual
    {
        // Para ver un producto individualmente con sus relaciones con materiales
        $producto = Producto::find($id);
        $producto->materiales = $producto->materiales()->withPivot('cantidad_consumida', 'cantidad_producida')->get();

        //Recuperamos el intermedio o el terminado segun corresponda
        if ($producto->tipo === 'intermedio') {
            $producto->intermedio = Intermedio::where('producto_id', $id)->first();

        } else {
            $producto->terminado = Terminado::where('producto_id', $id)->first();
        }

        return response()->json($producto, 200);
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
                'tipo' => 'required',
                'adicional' => 'required|string|max:50',
            ], [
                'nombre.required' => 'El campo nombre es obligatorio',
                'nombre.max' => 'El campo nombre no puede tener mas de 50 caracteres',
                'tipo.required' => 'El campo tipo es obligatorio',
                'adicional.required' => 'Se debe completar el campo adicional de tratamiento/garantía',
                'adicional.max' => 'El campo adicional no puede tener mas de 50 caracteres',
            ]);
            //Busca por id
            $producto = Producto::find($id);
            $producto->nombre = $request->input('nombre');

            //Si el tipo cambia, tenemos que eliminar uno, y crearlo de otro tipo
            if ($producto->tipo != $request->input('tipo')) {
                if ($producto->tipo === 'intermedio') { //Si era intermedio
                    $intermedio = Intermedio::where('producto_id', $id)->first();
                    $intermedio->delete();
                    $terminado = new Terminado(); //Creamos un Terminado
                    $terminado->garantia = $request->input('adicional'); //Y le introducimos la garantía que viene en la request
                    //Asociamos el producto terminado al producto modificado
                    $terminado->producto()->associate($producto);
                    $terminado->save();
                } else { //Si era terminado
                    $terminado = Terminado::where('producto_id', $id)->first();
                    $terminado->delete();
                    $intermedio = new Intermedio(); //Creamos un Intermedio
                    $intermedio->tratamiento = $request->input('adicional'); //Y le introducimos el tratamiento que viene en la request
                    //Asociamos el producto intermedio al producto modificado
                    $intermedio->producto()->associate($producto);
                    $intermedio->save();
                }
                $producto->tipo = $request->input('tipo'); // Le introducimos el tipo
            } else {
                //Si el tipo no cambia, recuperamos el tipo correspondiente y modificamos el campo correspondiente
                if ($producto->tipo === 'intermedio') {
                    $intermedio = Intermedio::where('producto_id', $id)->first();
                    $intermedio->tratamiento = $request->input('adicional');
                    $intermedio->save();
                } else {
                    $terminado = Terminado::where('producto_id', $id)->first();
                    $terminado->garantia = $request->input('adicional');
                    $terminado->save();
                }
            }
            //Guardamos el producto
            $producto->save();
            //Devolvemos el producto modificado
            return response()->json($producto, 200);
        
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
        //Busca el producto
        $producto = Producto::findOrFail($id);
        $albaranes = $producto->albaran()->withPivot('cantidad', 'precio')->get();

        if (!$albaranes->isEmpty()) {
            //Mensaje de error
            return response()->json(['error' => 'No se puede eliminar el producto porque tiene albaranes asociados'], 400);
        } else {
            //Recuperamos las producciones para ver las cantidades consumidas de materiales
            $materiales = $producto->materiales()->withPivot('cantidad_consumida', 'cantidad_producida')->get();
            //Recorremos las relaciones para comprobar la cantidad consumida del material y restaurarselo al material
            foreach($materiales as $material){
                //Recuperamos el material
                $materialAct = Material::find($material->pivot->material_id);
                $materialAct->cantidad_disponible = $materialAct->cantidad_disponible + $material->pivot->cantidad_consumida;
                $materialAct->save();
            }
            //Finalmente borramos el producto y sus relaciones con materiales
            $producto->delete();
        }

        //Se elimino correctamente y devolvemos la respuesta
        return response()->json(['mensaje' => 'El producto se borró correctamente'], 200);
    }



    /* FUNCIONS CORRESPONDENTES AS VISTAS*/


    /**
     * Mostrar os productos
     */
    public function listaxeproductos()
    {
        // Mostramos los productos
        $productos = Producto::all();
        return view('productos.productos', [
            'productos' => $productos,
            'mensajeError' => false,
            'mensajeSuccess' => false,
            'mensajeSuccessEdit' => false,
            'mensajeSuccessCrear' => false,
        ]);
    }

    /**
     * Vista para crear un novo producto
     */
    public function novoproducto()
    {
        $materiales = Material::all();

        // Levanos a vista para crear un novo producto
        return view('productos.novoproducto')->with('materiales',$materiales);
    }

    /**
     * Funcion para crear un novo producto
     */
    public function crearnovoproducto(Request $request)
    {
        // Validacións 
        $request->validate([
            'nombre' => 'required|max:50',
            'tipo' => 'required|in:terminado,intermedio',
            'cantidad_producida' => 'required|numeric',
            'adicional' => 'required',
        ]);
        //Creamos un nuevo producto con los datos de los inputs
        $producto = new Producto();
        $producto->nombre = $request->input('nombre');
        $producto->tipo = $request->input('tipo');
        $producto->cantidad_disponible = $request->input('cantidad_producida');

        //Datos de los materiales a consumir
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
                if ($material['cantidad']== null) {
                    return redirect()->back()->withErrors(['materials' => 'Debe especificar cantidad consumida']);
                } else {
                    $nuevo_array_materiales[$idMaterial] = ['cantidad_consumida' => $material['cantidad'], 'cantidad_producida' => $request->input('cantidad_producida')];
                }
            }
        }
        
        //Guardamos el producto
        $producto->save();

        $tipo = $request->input('tipo');
        if ($tipo === "intermedio") { //Si el tipo es intermedio
            $intermedio = new Intermedio();
            $intermedio->tratamiento = $request->input('adicional');

            //Asociamos el producto intermedio al producto recien creado
            $intermedio->producto()->associate($producto);
            // Guardar el Intermedio en la base de datos
            $intermedio->save();
        } else { //Si es terminado
            $terminado = new Terminado();
            $terminado->garantia = $request->input('adicional');

            //Asociamos el producto terminado al producto recien creado
            $terminado->producto()->associate($producto);
            // Guardar el Terminado en la base de datos
            $terminado->save();
        }

        // Asociar materiales con cantidades consumidas al Producto
        $producto->materiales()->attach($nuevo_array_materiales);

        //Actualizamos las cantidades de los materiales (se resta la cantidad que hemos consumido)
        //Para eso vamos recuperando los materiales y sincronizando la cantidad disponible
        foreach ($materials as $material) {
            //recorremos el array de materiales y comprobamos si su id se seleccionó o no
            if (in_array($material['id'], $materiales_seleccionados)) {
                //buscamos el material que hay que actualizar
                $idMaterial = $material['id'];
                $materialAct = Material::find($idMaterial);
                //Restamos la cantidad consumida a la disponible del material
                $cantidad = intval($materialAct->cantidad_disponible) - intval($material['cantidad']);
                //Actualizamos con la nueva cantidad
                $materialAct->update(['cantidad_disponible' => $cantidad]);
            }
        }

        // Redirección co mensaxe
        $productos = Producto::all();
        return view('productos.productos', [
            'productos' => $productos,
            'mensajeError' => false,
            'mensajeSuccess' => false,
            'mensajeSuccessEdit' => false,
            'mensajeSuccessCrear' => true,
        ]);
    }


    /**
     * Vista para editar un producto
     */
    public function modproducto($id)
    {
        //Busca el producto seleccionado
        $producto = Producto::find($id); 
        if ($producto->tipo === 'intermedio') { //Si es intermedio
            $intermedio = Intermedio::where('producto_id', $id)->first();
            $producto->campo_adicional = $intermedio->tratamiento;
        } else { //Si es terminado
            $terminado = Terminado::where('producto_id', $id)->first();
            $producto->campo_adicional = $terminado->garantia;
        }
        // Devolve a vista con datos
        return view('productos.modproducto')->with('producto',$producto);
    }

    /**
     * Funcion para editar o producto
     */
    public function editmodproducto(Request $request, $id)
    {
        //Validaciones
        $request->validate([
            'nombre' => 'required|max:50',
            'tipo' => 'required|in:terminado,intermedio',
            'adicional' => 'required',
        ]);

        //Buscamos el producto
        $producto = Producto::find($id);
        $producto->nombre = $request->input('nombre');

        //Si el tipo cambia, tenemos que eliminar uno, y crearlo de otro tipo
        if ($producto->tipo != $request->input('tipo')) {
            if ($producto->tipo === 'intermedio') { //Si es intermedio
                $intermedio = Intermedio::where('producto_id', $id)->first();
                $intermedio->delete(); //eliminamos y creamos uno terminado
                $terminado = new Terminado();
                $terminado->garantia = $request->input('adicional');
                //Asociamos el producto terminado al producto modificado
                $terminado->producto()->associate($producto);
                $terminado->save();
            } else { //Si es terminado
                $terminado = Terminado::where('producto_id', $id)->first();
                $terminado->delete(); //Eliminamos y creamos un Intermedio
                $intermedio = new Intermedio();
                $intermedio->tratamiento = $request->input('adicional');
                //Asociamos el producto intermedio al producto modificado
                $intermedio->producto()->associate($producto);
                $intermedio->save();
            }
            $producto->tipo = $request->input('tipo');
        } else {
            //Si el tipo no cambia, recuperamos el tipo correspondiente y modificamos el campo correspondiente
            if ($producto->tipo === 'intermedio') {
                $intermedio = Intermedio::where('producto_id', $id)->first();
                $intermedio->tratamiento = $request->input('adicional');
                $intermedio->save();
            } else {
                $terminado = Terminado::where('producto_id', $id)->first();
                $terminado->garantia = $request->input('adicional');
                $terminado->save();
            }
        }
        $producto->save();

        //Recuperamos los productos para redireccionar
        $productos = Producto::all();
        return view('productos.productos', [
            'productos' => $productos,
            'mensajeError' => false,
            'mensajeSuccess' => false,
            'mensajeSuccessEdit' => true,
            'mensajeSuccessCrear' => false,
        ]);
    }

    /**
     * Elimina un producto
     */
    public function eliminarproducto($id)
    {
        //Busca el producto
        $producto = Producto::findOrFail($id);
        $albaranes = $producto->albaran()->withPivot('cantidad', 'precio')->get();

        if (!$albaranes->isEmpty()) { //Si tiene albaranes de salida no se puede eliminar
            //Para volver a listarlos en el resultado
            $productos = Producto::all();
            return view('productos.productos', [
                'productos' => $productos,
                'mensajeError' => true,
                'mensajeSuccess' => false,
                'mensajeSuccessEdit' => false,
                'mensajeSuccessCrear' => false,
            ]);
        } else { //Si no está en albaranes de salida
            //Recuperamos las producciones para ver las cantidades consumidas de materiales
            $materiales = $producto->materiales()->withPivot('cantidad_consumida', 'cantidad_producida')->get();
            //Recorremos las relaciones para comprobar la cantidad consumida del material y restaurarselo al material
            foreach($materiales as $material){
                //Recuperamos el material
                $materialAct = Material::find($material->pivot->material_id);
                $materialAct->cantidad_disponible = $materialAct->cantidad_disponible + $material->pivot->cantidad_consumida;
                $materialAct->save();
            }
            //Finalmente borramos el producto y sus relaciones con materiales
            $producto->delete();
        }

        //Para volver a listarlos en el resultado
        $productos = Producto::all();
        // Redirección co mensaxe
        return view('productos.productos', [
            'productos' => $productos,
            'mensajeError' => false,
            'mensajeSuccess' => true,
            'mensajeSuccessEdit' => false,
            'mensajeSuccessCrear' => false,
        ]);
    }

    public function verproducto($id)
    {
        // Para ver un producto individualmente con sus relaciones con materiales
        $producto = Producto::find($id);
        $materiales = $producto->materiales()->withPivot('cantidad_consumida', 'cantidad_producida')->get();

        //Recuperamos el intermedio o el terminado segun corresponda
        $intermedio = Intermedio::where('producto_id', $id)->first();
        $terminado = Terminado::where('producto_id', $id)->first();

        //Devolvemos la vista
        return view('productos.verproducto', [
            'producto' => $producto,
            'materiales' => $materiales,
            'intermedio' => $intermedio,
            'terminado' => $terminado
        ]);
    }
}
