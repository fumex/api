<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenDepedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_depedidos', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('id_almacen');
            $table->Integer('id_proveedor');
            $table->date('fecha_estimada_entrega');
            $table->string('terminos')->nullable();
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
        Schema::dropIfExists('orden_depedidos');
    }
}
