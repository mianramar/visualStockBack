<?php

/*

Título: Entrega Final

Autor: Miguel Ángel Rama Martínez.

Data modificación: 14/03/2024

Versión 1.0

*/

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Borramos la tabla e inicializamos a 1 el autoincremento
        DB::table('productos')->delete();
        DB::statement('ALTER TABLE productos AUTO_INCREMENT = 1');

        // Insertamos los predefinidos
        DB::table('productos')->insert(['nombre' => 'Botella Inox', 'tipo' => 'intermedio', 'cantidad_disponible' => '4']);
        DB::table('productos')->insert(['nombre' => 'Mesa Aluminio', 'tipo' => 'terminado', 'cantidad_disponible' => '1']);
        DB::table('productos')->insert(['nombre' => 'Codo Inox', 'tipo' => 'intermedio', 'cantidad_disponible' => '3']);
        DB::table('productos')->insert(['nombre' => 'Cuchillo Acero', 'tipo' => 'intermedio', 'cantidad_disponible' => '10']);
        DB::table('productos')->insert(['nombre' => 'Llave Inglesa', 'tipo' => 'terminado', 'cantidad_disponible' => '3']);

        // Insertamos en la tabla pivote los productos resultado de los materiales usados
        DB::table('material_producto')->insert(['material_id' => '1', 'producto_id' => '2', 'cantidad_consumida' => '6', 'cantidad_producida' => '3']);
        DB::table('material_producto')->insert(['material_id' => '3', 'producto_id' => '5', 'cantidad_consumida' => '6', 'cantidad_producida' => '10']);
        DB::table('material_producto')->insert(['material_id' => '2', 'producto_id' => '1', 'cantidad_consumida' => '1', 'cantidad_producida' => '4']);
        DB::table('material_producto')->insert(['material_id' => '2', 'producto_id' => '3', 'cantidad_consumida' => '2', 'cantidad_producida' => '5']);
        DB::table('material_producto')->insert(['material_id' => '4', 'producto_id' => '4', 'cantidad_consumida' => '1', 'cantidad_producida' => '10']);
    }
}
