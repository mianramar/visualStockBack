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

class AlbaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Borramos la tabla e inicializamos a 1 el autoincremento
        DB::table('albarans')->delete();
        DB::statement('ALTER TABLE albarans AUTO_INCREMENT = 1');

        // Insertamos los predefinidos
        DB::table('albarans')->insert(['empresa_id' => '1', 'numero' => '2024020101', 'tipo' => 'entrada', 'fecha' => '2024-02-01']);
        DB::table('albarans')->insert(['empresa_id' => '2', 'numero' => '2024030101', 'tipo' => 'salida', 'fecha' => '2024-03-01']);
        DB::table('albarans')->insert(['empresa_id' => '3', 'numero' => '2024020102', 'tipo' => 'entrada', 'fecha' => '2024-02-01']);
        DB::table('albarans')->insert(['empresa_id' => '4', 'numero' => '2024030102', 'tipo' => 'salida', 'fecha' => '2024-03-01']);
        DB::table('albarans')->insert(['empresa_id' => '5', 'numero' => '2024020103', 'tipo' => 'entrada', 'fecha' => '2024-02-01']);

        // Insertamos en la relación albaran-material
        DB::table('albaran_material')->insert(['albaran_id' => '1', 'material_id' => '1', 'cantidad' => '10', 'precio' => '100.00']);
        DB::table('albaran_material')->insert(['albaran_id' => '1', 'material_id' => '2', 'cantidad' => '10', 'precio' => '150.00']);
        DB::table('albaran_material')->insert(['albaran_id' => '3', 'material_id' => '3', 'cantidad' => '15', 'precio' => '100.00']);
        DB::table('albaran_material')->insert(['albaran_id' => '3', 'material_id' => '4', 'cantidad' => '10', 'precio' => '160.00']);
        DB::table('albaran_material')->insert(['albaran_id' => '5', 'material_id' => '5', 'cantidad' => '10', 'precio' => '130.00']);

        // Insertamos en la relación albaran-producto
        DB::table('albaran_producto')->insert(['albaran_id' => '2', 'producto_id' => '2', 'cantidad' => '1', 'precio' => '300.00']);
        DB::table('albaran_producto')->insert(['albaran_id' => '2', 'producto_id' => '5', 'cantidad' => '3', 'precio' => '35.00']);
        DB::table('albaran_producto')->insert(['albaran_id' => '4', 'producto_id' => '2', 'cantidad' => '1', 'precio' => '350.00']);
        DB::table('albaran_producto')->insert(['albaran_id' => '4', 'producto_id' => '3', 'cantidad' => '2', 'precio' => '130.00']);
        DB::table('albaran_producto')->insert(['albaran_id' => '4', 'producto_id' => '5', 'cantidad' => '4', 'precio' => '30.00']);


    }
}
