<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('id_categoria')->nullable();
            $table->string('nombre_producto');
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('observaciones')->nullable();
            $table->string('descripcion')->nullable();
            $table->Integer('id_unidad')->nullable();
            $table->string('codigo');
            $table->boolean('estado');
            $table->Integer('id_user');
            $table->string('imagen');
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
        Schema::dropIfExists('productos');
    }
}
