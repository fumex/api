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
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_venta');
            $table->integer('cantidad');
            $table->decimal('precio_unitario');
            $table->float('descuento');
            $table->float('igv')->nullable()->default(0);
            $table->integer('igv_id')->nullable()->default(0);
            $table->float('igv_porcentage')->nullable()->default(0);
            $table->float('isc')->nullable()->default(0);
            $table->integer('isc_id')->nullable()->default(0);
            $table->float('isc_porcentage')->nullable()->default(0);
            $table->float('otro')->nullable()->default(0);
            $table->integer('otro_id')->nullable()->default(0);
            $table->float('otro_porcentage')->nullable()->default(0);
            $table->integer('id_producto');
            $table->string('nombre_producto'); 
            $table->string('unidad_medida');
            //$table->integer('id_codigo_producto');
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
