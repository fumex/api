<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
   protected $table='sucursals';
   protected $fillable=['nombre_sucursal','direccion','telefono','id_almacen','descripcion','estado','id_user'];
}
