<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',7);
            $table->Integer('id_documento');
            $table->string('nroBoleta')->unique();
            $table->Integer('id_proveedor');
            $table->Integer('id_almacen');
            $table->string('tipoPago');
            $table->float('subtotal');
            $table->float('igv');
            $table->float('otro');
            $table->boolean('estado')->nullable()->default(true);
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
        Schema::dropIfExists('pagos');
    }
}
