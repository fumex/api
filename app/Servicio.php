<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table='servicios';
    protected $fillable=['code','id_documento','nroBoleta','tipo_pago','id_proveedor','descripcion','subtotal','igv','estado'];  
}
