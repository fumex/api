<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serie_venta');
            $table->string('tarjeta')->nullable();
            $table->integer('id_caja');
            $table->integer('id_cliente');
            $table->string('nro_documento');
            //$table->string('razon_social');
            $table->Decimal('total');
            $table->Decimal('pago_efectivo')->nullable();
            $table->Decimal('pago_tarjeta')->nullable();
            $table->integer('id_moneda');   
            $table->string('codigo_moneda'); 
            $table->float('igv')->nullable()->default(0);
            $table->float('isc')->nullable()->default(0);
            $table->float('otro')->nullable()->default(0);
            $table->boolean('resultado')->nullable();
            $table->boolean('estado');
            $table->integer('id_usuario');
            $table->string('letrado');
            $table->string('email');
            $table->integer('guia_remicion')->nullable()->default(null);
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
        Schema::dropIfExists('ventas');
    }
}
