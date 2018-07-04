<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
   protected $table='sucursals';
   protected $fillable=['nombre_sucursal','direccion','telefono','telefono2','id_almacen','descripcion','estado','id_user'];
}
