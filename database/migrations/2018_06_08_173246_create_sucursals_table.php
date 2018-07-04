<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSucursalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursals', function (Blueprint $table) {
            $table->increments('id');
            $table->String('nombre_sucursal')->unique();
            $table->String('direccion');
            $table->String('telefono')->nullable();
            $table->String('telefono2')->nullable();
            $table->Integer('id_almacen');
            $table->String('descripcion')->nullable();
            $table->boolean('estado')->nullable()->default(true);
            $table->Integer('id_user');
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
        Schema::dropIfExists('sucursals');
    }
}
