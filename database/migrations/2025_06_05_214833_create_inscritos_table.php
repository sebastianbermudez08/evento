<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscritosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscritos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre_completo');
        $table->string('numero_documento')->unique();
        $table->integer('edad');
        $table->enum('genero', ['Masculino', 'Femenino', 'Otro']);
        $table->string('correo')->unique();
        $table->string('telefono');
        $table->string('profesion')->nullable();
        $table->string('empresa')->nullable();
        $table->date('fecha_registro');
        $table->string('comprobante_token')->unique(); // generado por Sanctum
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscritos');
    }
}
