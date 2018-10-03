<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table='ventas';
    protected $fillable=['serie_venta','tarjeta','id_caja','id_user','id_caja','id_cliente','total','pago_efectivo','pago_tarjeta','resultado','estado','id_user'];
}
