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

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Borramos la tabla e inicializamos a 1 el autoincremento
        DB::table('empresas')->delete();
        DB::statement('ALTER TABLE empresas AUTO_INCREMENT = 1');

        // Insertamos los predefinidos
        DB::table('empresas')->insert(['nombre' => 'Metalica', 'email' => 'administracion@metalica.es', 'telefono' => '689147852', 'direccion' => 'calle lumbados 11, Ourense']);
        DB::table('empresas')->insert(['nombre' => 'Metalworks', 'email' => 'contacto@metalworks.es', 'telefono' => '689348952', 'direccion' => 'calle trambo 23, Coruña']);
        DB::table('empresas')->insert(['nombre' => 'Fundius', 'email' => 'pedidos@fundius.es', 'telefono' => '689513321', 'direccion' => 'calle estrumber 44, Vigo']);
        DB::table('empresas')->insert(['nombre' => 'Grabametal', 'email' => 'grabametal@metalicas.es', 'telefono' => '689398877', 'direccion' => 'calle pendiente 10, Pontevedra']);
        DB::table('empresas')->insert(['nombre' => 'Metaloides', 'email' => 'metaloides@metal.es', 'telefono' => '689177133', 'direccion' => 'calle dolores 30, Lugo']);
    }
}
