<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodigoProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codigo_productos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_detalle_almacen')->nullable()->default(null);
            $table->string('numero_de_serie')->nullable()->default(null);
            $table->string('codigo_interno')->nullable()->default(null);
            $table->string('codigo_automatico');
            $table->integer('id_usuario');
            $table->integer('id_detalle_pago');
            $table->boolean('vendible');
            $table->string('accion')->nullable();
            $table->string('id_detalle_tabla')->nullable();
            $table->date('fecha_vencimiento')->nullable()->default(null);
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
        Schema::dropIfExists('codigo_productos');
    }
}
