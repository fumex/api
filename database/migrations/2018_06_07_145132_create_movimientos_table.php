<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tabla_nombre');
            $table->integer('id_tabla');
            $table->string('almacen_nombre')->nullable();
            $table->string('productos_nombre');
            $table->integer('productos_id');
            $table->integer('id_usuario');
            $table->integer('valor')->nullable();
            $table->integer('valor_antiguo')->nullable();
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
        Schema::dropIfExists('movimientos');
    }
}
