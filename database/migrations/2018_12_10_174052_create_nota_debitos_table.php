<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotaDebitosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nota_debitos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipo_nota');
            $table->string('serie_nota');
            $table->Integer('id_venta');
            $table->string('motivo');
            $table->string('email')->nullable();
            $table->Integer('id_usuario');
            $table->Integer('resultado')->nullable();
            $table->string('letrado');
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
        Schema::dropIfExists('nota_debitos');
    }
}
