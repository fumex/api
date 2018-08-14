<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class orden_depedido extends Model
{
    protected $table='orden_depedidos';
    protected $fillable=['id_almacen','id_proveedor','fecha_estimada_entrega','terminos','estado']; 
}
