<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientosDetalleAlmacensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos_detalle_almacens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_usuario');
            $table->integer('id_detalle_almacen');
            $table->float('descuento_anterior');
            $table->float('descuento_actual');
            $table->float('precio_anterior');
            $table->float('precio_actual');
            $table->float('precio_compra_anterior');
            $table->float('precio_compra_actual');
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
        Schema::dropIfExists('movimientos_detalle_almacens');
    }
}
