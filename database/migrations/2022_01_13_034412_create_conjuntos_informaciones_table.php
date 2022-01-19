<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConjuntosInformacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conjuntos_informaciones', function (Blueprint $table) {
            $table->id();
            $table->float('valor_administracion', 25, 2);
            $table->string('limite_pago');
            $table->float('interes_mora');
            $table->integer('numero_parqueaderos');
            $table->integer('horas_gratis');
            $table->float('valor_hora_adicional');
            $table->unsignedBigInteger('conjunto_id')->unique();
            $table->timestamps();
            $table->foreign('conjunto_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conjuntos_informaciones');
    }
}
