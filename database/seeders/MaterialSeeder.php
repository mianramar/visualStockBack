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

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Borramos la tabla e inicializamos a 1 el autoincremento
        DB::table('materials')->delete();
        DB::statement('ALTER TABLE materials AUTO_INCREMENT = 1');

        // Insertamos los predefinidos
        DB::table('materials')->insert([
            'user_id' => '2',
            'metal' => 'Aluminio',  
            'dimensiones' => '100x150x5',
            'cantidad_disponible' => '4', 
        ]);
        DB::table('materials')->insert([
            'user_id' => '2',
            'metal' => 'Inox', 
            'dimensiones' => '70x120x4', 
            'cantidad_disponible' => '7',  
        ]);
        DB::table('materials')->insert([
            'user_id' => '3',
            'metal' => 'Aluminio', 
            'dimensiones' => '85x135x3', 
            'cantidad_disponible' => '9', 
        ]);
        DB::table('materials')->insert([
            'user_id' => '4',
            'metal' => 'Acero',  
            'dimensiones' => '90x130x6', 
            'cantidad_disponible' => '9', 
        ]);
        DB::table('materials')->insert([
            'user_id' => '4',
            'metal' => 'Acero', 
            'dimensiones' => '80x120x5', 
            'cantidad_disponible' => '10', 
        ]);

    }
}
