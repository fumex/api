<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotaCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nota_creditos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipo_nota');
            $table->string('serie_nota');
            $table->Integer('id_venta');
            $table->string('motivo');
            $table->string('email')->nullable();
            $table->string('correccion_ruc')->nullable();
            $table->float('descuento')->nullable();
            $table->Integer('id_usuario');
            $table->Integer('id_venta_nueva')->nullable();
            $table->Integer('resultado')->nullable();
            $table->sting('letrado');
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
        Schema::dropIfExists('nota_creditos');
    }
}
