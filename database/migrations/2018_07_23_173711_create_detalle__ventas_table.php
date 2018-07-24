<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle__ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_venta');
            $table->integer('cantidad');
            $table->decimal('precio_unitario');
            $table->decimal('impuestos');
            $table->integer('id_producto');
            $table->boolean('estado');
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
        Schema::dropIfExists('detalle__ventas');
    }
}
