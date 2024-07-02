<?php

/*

Título: Entrega Final

Autor: Miguel Ángel Rama Martínez.

Data modificación: 14/03/2024

Versión 1.0

*/

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->delete();
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@administrador.es',
            'rol' => 'administrador',            
            'password' => Hash::make('12345678'),
        ]);

        User::factory(4)->create([
            'rol' => 'usuario',
            'password' => Hash::make('12345678'),
        ]);

        $this->call([
            PerfilSeeder::class,
            EmpresaSeeder::class,
            MaterialSeeder::class,
            ProductoSeeder::class,
            IntermedioSeeder::class,
            TerminadoSeeder::class,
            AlbaranSeeder::class,
        ]);
    }
}
