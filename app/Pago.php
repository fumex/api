<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
 	protected $table='pagos';

 	protected $fillable=['code','id_documento','nroBoleta','id_proveedor','id_almacen','tipoPago','subtotal','igv','estado'];  
}
