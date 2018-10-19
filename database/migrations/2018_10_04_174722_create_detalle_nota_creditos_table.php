<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleNotaCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_nota_creditos', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('id_nota_credito');
            $table->Integer('id_detalle_venta');
            $table->string('correccion')->nullable();
            $table->float('cantidad')->nullable();
            $table->float('igv')->nullable();
            $table->float('isc')->nullable();
            $table->float('otro')->nullable();
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
        Schema::dropIfExists('detalle_nota_creditos');
    }
}
