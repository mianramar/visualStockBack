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

class PerfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Borramos la tabla e inicializamos a 1 el autoincremento
        DB::table('perfils')->delete();
        DB::statement('ALTER TABLE perfils AUTO_INCREMENT = 1');

        // Insertamos los predefinidos
        DB::table('perfils')->insert(['user_id' => '1', 'nombre' => 'Juan', 'apellido' => 'Romero', 'imagen' => 'imaxes/foto1.PNG']);
        DB::table('perfils')->insert(['user_id' => '2', 'nombre' => 'Marcos', 'apellido' => 'Loiro', 'imagen' => 'imaxes/foto2.PNG']);
        DB::table('perfils')->insert(['user_id' => '3', 'nombre' => 'Andrea', 'apellido' => 'Neira', 'imagen' => 'imaxes/foto3.PNG']);
        DB::table('perfils')->insert(['user_id' => '4', 'nombre' => 'Pablo', 'apellido' => 'Feroz', 'imagen' => 'imaxes/foto4.PNG']);
        DB::table('perfils')->insert(['user_id' => '5', 'nombre' => 'Nuria', 'apellido' => 'Morado', 'imagen' => 'imaxes/foto5.PNG']);
    }
}
