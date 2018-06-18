<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('apellidos');
            $table->Integer('id_documento');
            $table->Integer('numero_documento');
            $table->string('direccion');
            $table->Integer('telefono');
            $table->string('email')->unique();
            $table->date('nacimiento');
            $table->string('rol');
            $table->string('password');
            $table->boolean('estado');
            $table->Integer('strd');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
