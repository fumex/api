<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagoDetalle extends Model
{
    protected $table='pago_detalles';
    protected $fillable=['id_pago','id_producto','cantidad','id_unidad','precio_unitario'];
}
