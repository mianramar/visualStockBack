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
        Schema::create('albaran_material', function (Blueprint $table) {
            $table->unsignedBigInteger('albaran_id');
            $table->unsignedBigInteger('material_id');
            $table->integer('cantidad');
            $table->float('precio');
            $table->timestamps();

            // Claves foráneas
            $table->foreign('albaran_id')->references('id')->on('albarans')->onDelete('cascade');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');

            // Clave primaria composta
            $table->primary(['albaran_id', 'material_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albaran_material');
    }
};
