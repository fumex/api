<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleAlmacensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_almacen', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('id_almacen');
            $table->string('codigo');
            $table->Integer('id_producto');
            $table->Integer('stock')->default(0);
            $table->decimal('precio_compra')->nullable()->default(0);
            $table->decimal('precio_venta')->nullable()->default(0);
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
        Schema::dropIfExists('detalle_almacen');
    }
}
