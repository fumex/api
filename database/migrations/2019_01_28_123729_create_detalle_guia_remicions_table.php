<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleGuiaRemicionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_guia_remicions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_guia');
            $table->integer('id_producto');
            $table->string('codigo_producto');
            $table->string('nombre_producto');
            $table->string('unidad_medida');
            $table->integer('cantidad');
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
        Schema::dropIfExists('detalle_guia_remicions');
    }
}
