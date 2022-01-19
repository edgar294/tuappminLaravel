<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidentesInformacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residentes_informaciones', function (Blueprint $table) {
            $table->id();
            $table->string('bloque')->nullable();
            $table->string('apartamento')->nullable();
            $table->string('nombre_propietario');
            $table->string('telefono')->nullable();
            $table->string('numero_casa')->nullable();
            $table->unsignedBigInteger('residente_id');
            $table->timestamps();
            $table->foreign('residente_id')->references('id')->on('residentes_conjuntos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('residentes_informaciones');
    }
}
