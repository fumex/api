<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('razon_social');
            $table->string('nombre_comercial');
            $table->string('ruc');
            $table->string('ubigeo');
            $table->string('direccion');
            $table->string('departamento');
            $table->string('provincia');
            $table->string('distrito');
            $table->string('telefono1');
            $table->string('telefono2');
            $table->string('web');
            $table->string('email');
            $table->string('imagen')->nullable();
            $table->string('pfx')->nullable();
            $table->boolean('estado')->nullable()->default(true);
            $table->Integer('id_user');
            $table->boolean('agente_retencion')->nullable()->default(false);
            $table->boolean('agente_percepcion')->nullable()->default(false);
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
        Schema::dropIfExists('empresas');
    }
}
