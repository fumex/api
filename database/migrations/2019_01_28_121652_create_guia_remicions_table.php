<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuiaRemicionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guia_remicions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serie_guia');
            $table->string('motivo_traslado');
            $table->string('DAM');
            $table->integer('tipo_documento');
            $table->string('razon_social');
            $table->string('tipo_transporte');
            $table->float('peso_bruto');
            $table->string('punto_partida');
            $table->string('direccion_partida');
            $table->string('punto_llegada');
            $table->string('direccion_llegada');
            $table->date('fecha_partida');   
            $table->string('observaciones'); 
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
        Schema::dropIfExists('guia_remicions');
    }
}
