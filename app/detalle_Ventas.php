<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class detalle_Ventas extends Model
{
    protected $table='detalle_ventas';
    protected $fillable=['id_venta','catidad','precio_unitario','descuento','igv','igv_id','isc','isc_id','otro','otro_id','id_producto','estado']; 
}
