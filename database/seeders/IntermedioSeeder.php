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

class IntermedioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('intermedios')->insert(['producto_id' => 1, 'tratamiento' => 'roscado']);
        DB::table('intermedios')->insert(['producto_id' => 3, 'tratamiento' => 'montado']);
        DB::table('intermedios')->insert(['producto_id' => 4, 'tratamiento' => 'desbastado']);
    }
}
