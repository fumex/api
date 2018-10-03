<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleCajasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_cajas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_caja');
            $table->integer('id_usuario');
            $table->string('monto_apertura');
            $table->boolean('abierta');
            $table->float('monto_actual')->nullable();
            $table->string('monto_cierre_efectivo')->nullable();
            $table->float('monto_cierre_tarjeta')->nullable();
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
        Schema::dropIfExists('detalle_cajas');
    }
}
