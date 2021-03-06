<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
 	protected $table='pagos';

 	protected $fillable=['code','fecha','id_documento','nroBoleta','id_proveedor','id_almacen','tipoPago','subtotal','igv','exonerados','gravados','otro','estado'];  
}
