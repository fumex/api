<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransporteGuiaRemicionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transporte_guia_remicions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nro_placa');
            $table->integer('id_tipo_documento');
            $table->string('nro_docuemnto');
            $table->string('nombre_conductor');
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
        Schema::dropIfExists('transporte_guia_remicions');
    }
}
