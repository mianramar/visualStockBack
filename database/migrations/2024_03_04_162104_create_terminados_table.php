<?php

/*

Título: Entrega Final

Autor: Miguel Ángel Rama Martínez.

Data modificación: 14/03/2024

Versión 1.0

*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('terminados', function (Blueprint $table) {
            $table->unsignedBigInteger('producto_id');
            $table->string('garantia');
            $table->timestamps();

            // Clave foránea
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');

            // Clave primaria
            $table->primary('producto_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminados');
    }
};
